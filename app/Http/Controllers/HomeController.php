<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Http\Request;
use App\Jobs\ProcessImportedData;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Imports\CSVImport;
use App\Models\BTL;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $databaseName = DB::connection()->getDatabaseName();
        $tables = DB::select('SHOW TABLES');
        $tables = array_map(function ($table) use ($databaseName) {
            return $table->{'Tables_in_' . $databaseName};
        }, $tables);
        $tablesToHide = ['users', 'password_resets', 'migrations', 'failed_jobs', 'sessions','cache','cache_locks','jobs','job_batches', 'password_reset_tokens','notifications','social_logins'];
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

    public function edit_btl($id)
    {
        $btls = BTL::find($id);
        return view('edit_btl',compact('btls'));
    }
    public function post_edit_btl(BTL $btls)
    {
        //
        $btls->update([
            'reg_no'=>request('reg_no'),
            'name'=>request('name'),
            'cnic'=>request('cnic'),
            'mobile'=>request('mobile'),
            'tell_no'=>request('tell_no'),
            'project_type'=>request('project_type'),
            'phase'=>request('phase'),
            'plot_size'=>request('plot_size'),
            'sector'=>request('sector'),
            'plot_no'=>request('plot_no'),
        ]);

        return redirect('/home')->with('success','Your Report is edit successfully');
    }
    public function delete(BTL $btls)
    {
        $btls->delete();

        return redirect('/home');
    }
}
