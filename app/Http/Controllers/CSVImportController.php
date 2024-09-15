<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\ProcessImportedData;
use App\Jobs\UpdateProgressJob;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Imports\CSVImport;
use Throwable;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class CSVImportController extends Controller
{
    public function index()
    {
        return view('csv_import');
    }


    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv'
        ]);
        $file = $request->file('csv_file');
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

    // Store the file in the 'public/uploads' directory
    $storedFile = $file->move(public_path('uploads'), $fileName);

    // Get the correct path for processing the file
    $path = $storedFile->getPathname();

        // Read the CSV file
        // $data = array_map('str_getcsv', file($path));
        $data = array_map(function($line) {
            // Detect encoding and convert to UTF-8
            $encoding = mb_detect_encoding($line, mb_list_encodings(), true) ?: 'UTF-8';
            return str_getcsv(mb_convert_encoding($line, 'UTF-8', $encoding));
        }, file($path));

        $header = array_shift($data);

        // Handle duplicate column names
        $header = $this->handleDuplicateColumns($header);

        if (Schema::hasTable($fileName)) {
            // Drop the table if it exists
            Schema::drop($fileName);
        }
        // Create the table
        try {
            Schema::create($fileName, function (Blueprint $table) use ($header) {
                $table->id();
                foreach ($header as $column) {
                    $table->string($column, 255)->nullable();
                }
                // $table->timestamps(no);
            });
        } catch (\Exception $e) {
            return response()->json(['error' => 'Table creation error: ' . $e->getMessage()]);
        }

        // Insert data into the table
        try {
            // Process the data in chunks using batch processing
            $totalRows = count($data);
            $processedRows = 0;
            $batchSize = 500;

            $batch = Bus::batch([])->onQueue('high')->dispatch();

            foreach (array_chunk($data, $batchSize) as $chunk) {
                $job = new ProcessImportedData($chunk, $fileName, $totalRows, $processedRows, $header);
// Set the job to be dispatched on the 'high' queue
                $batch->add($job->onQueue('high'));
                $processedRows += count($chunk);
            }

            Notification::create([
                'type' => $fileName,
                'data' => $fileName . ' has been uploaded .',
                'read' => false,
                'is_new' => true, // Mark as new
            ]);
            return response()->json([
                'status' =>'success',
                'message' => 'Import started',
                'batchId' => $batch->id
            ]);

            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'File uploaded and import started successfully!'
            // ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data insertion error: ' . $e->getMessage()]);
        }
        // return redirect('/home')->with('success', 'File uploaded successfully!');
        // return redirect()->route('tables', ['table' => $fileName])->with('success', 'File imported successfully!');

    }

    private function handleDuplicateColumns($columns)
{
    $uniqueColumns = [];
    $counts = [];

    foreach ($columns as $column) {
        // Ensure column names are valid SQL identifiers
        $column = preg_replace('/[^a-zA-Z0-9_]/', '_', $column);
        $column = trim($column); // Remove any leading/trailing whitespace
        if (empty($column)) {
            $column = 'column'; // Give a default name if empty
        }

        if (isset($counts[$column])) {
            $counts[$column]++;
        } else {
            $counts[$column] = 1;
        }

        $uniqueColumn = $column . ($counts[$column] > 1 ? '_' . $counts[$column] : '');
        $uniqueColumns[] = $uniqueColumn;
    }

    return $uniqueColumns;
}
    public function showTable($table)
    {
        if (!Schema::hasTable($table)) {
            abort(404);
        }

        $columns = Schema::getColumnListing($table);
        $columns = array_diff($columns, ['id']);
        $count = DB::table($table)->get();
        $data = DB::table($table)->paginate(20);
        $importSuccess = session('importSuccess', false);
        if ($importSuccess) {
            session()->forget('importSuccess');
        }
        return view('table', compact('importSuccess','columns', 'data', 'table','count'));
    }



    public function tables()
    {

        $databaseName = DB::connection()->getDatabaseName();
        $tables = DB::select('SHOW TABLES');
        $tables = array_map(function ($table) use ($databaseName) {
            return $table->{'Tables_in_' . $databaseName};
        }, $tables);
        $tablesToHide = ['users', 'password_resets', 'migrations','notifications', 'failed_jobs', 'sessions','cache','cache_locks','jobs','job_batches', 'password_reset_tokens','social_logins'];
        $filteredTables = array_filter($tables, function ($table) use ($tablesToHide) {
            return !in_array($table, $tablesToHide);
        });
        usort($filteredTables, function ($a, $b) use ($tables) {
            $aCreationTime = array_search($a, $tables);
            $bCreationTime = array_search($b, $tables);
            return $bCreationTime - $aCreationTime;
        });
        // $lastMigration = DB::table('migrations')->orderBy('batch', 'desc')->first();
        // $lastInsertedTable = $lastMigration ? $lastMigration->migration : null;
        $lastInsertedTable = DB::select('SELECT TABLE_NAME
                                FROM information_schema.TABLES
                                WHERE TABLE_SCHEMA = ?
                                ORDER BY CREATE_TIME DESC
                                LIMIT 1', [$databaseName])[0]->TABLE_NAME;

                $importSuccess = session('importSuccess', false);
                if ($importSuccess) {
                    session()->forget('importSuccess');
                }
        return view('tables',compact('importSuccess'),  ['tables' => $filteredTables, 'lastInsertedTable' => $lastInsertedTable]);

    }

   /// Show table



    public function search(Request $request, $table){

        $searchFields = $request->except([ '_token','page','theme']);
        $query = DB::table($table);

        foreach ($searchFields as $key => $value) {

            if (!empty($value)) { // Only apply filter if a value is provided
                $query->where($key, 'like', '%' . $value . '%');
            }
        }
        $columns = Schema::getColumnListing($table);
        $columns = array_diff($columns, ['id']);
        $count = $query->get();
        $data = $query->paginate(20);

 // Append search fields to pagination links
 foreach ($searchFields as $key => $value) {
    if (!empty($value)) {
        $data->appends($key, $value , $count);
    }
}

        if ($data->isEmpty()) {
            // No data found, return an error message
            return view('table', [
                'table' => $table,
                'columns' => $columns,
                'count'=>$count,
                'data' => [],
                'error' => 'No records found for the given search criteria.'
            ])->with('error','No records found for the given search criteria.');
        }



    return view('table', compact('table', 'columns', 'data','count'));
    }
    // edit table
    public function editTableItem(Request $request, $table, $id)
   {
    // Fetch the item by ID
    $item = DB::table($table)->where('id', $id)->first();

    // Fetch the table columns dynamically
    $columns = Schema::getColumnListing($table);
    $columns = array_diff($columns, ['id']); // Exclude 'id' column

    // Pass the item and columns to the view for editing
    return view('edit_btl', compact('table', 'item', 'columns'));
   }


   public function updateTableItem(Request $request, $table)
{//// Ensure you exclude `_token` and `_method` from the update data
    $data = $request->except('_token', '_method', 'id');

    // Assume 'id' is sent with the request to identify the record to be updated
    $id = $request->input('id');

    if (!$id) {
        return redirect()->route('table.show', ['table' => $table])->with('error', 'Record ID is required for updating.');
    }

    // Wrap column names in backticks to handle cases where they might contain numbers or special characters
    // $sanitizedData = [];
    // foreach ($data as $column => $value) {
    //     $sanitizedData["`$column`"] = $value;
    // }

    // Perform the update
    DB::table($table)->where('id', $id)->update($data);

    return redirect()->route('table.show', ['table' => $table])->with('success', 'Record updated successfully');
}


public function deleteTableItem(Request $request, $table, $id)
{
    // Delete the item by ID
    DB::table($table)->where('id', $id)->delete();

    return redirect()->route('table.show', $table)->with('success', 'Item deleted successfully.');
}

// Notification function
public function showNotifications()
{
    // $notifications = Auth::user()->notifications()->where('read', false)->latest()->take(10)->get();
    // return view('vendor.tablar.partials.header.notifications', compact('notifications'));
    $notifications = Notification::where('read', false)->latest()->take(10)->get();
    return view('tablar::partials.header.notifications', compact('notifications'));
}
// C:\laragon\www\tabler\resources\views\vendor\tablar\partials\header\notifications.blade.php
// vendor\tablar\partials\header\
//update notification
public function markAsRead(Request $request, $id)
{
    $notification = Notification::find($id);
    if ($notification) {
        $notification->update(['is_new' => false]);
    }

    return response()->json(['status' => 'success']);
}
}
