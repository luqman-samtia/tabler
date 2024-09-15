<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Batchable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;



class ProcessImportedData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;


    // protected $dataChunk;
    // protected $fileName;
    // protected $batchId;

    protected $chunk;
    protected $fileName;
    protected $totalRows;
    protected $processedRows;
    protected $header;
    /**
     * Create a new job instance.
     */
    public function __construct(array $chunk, string $fileName, int $totalRows, int $processedRows, array $header)
    {
        // old
        // $this->dataChunk = $dataChunk;
        // $this->fileName = $fileName;
        $this->chunk = $chunk;
        $this->fileName = $fileName;
        $this->totalRows = $totalRows;
        $this->processedRows = $processedRows;
        $this->header = $header;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // $batchId = $this->batchId;

        // try {
        //     DB::table($this->fileName)->insert($this->dataChunk);
        //      // Update the batch progress
        //      $this->updateBatchProgress();
        // } catch (Exception $e) {
        //     Log::error('Data insertion error: ' . $this->fileName . ' - ' . $e->getMessage());
        //     throw $e;
        // }
        // DB::table($this->fileName)->insert($this->dataChunk);
        // DB::beginTransaction();

// new_old
        // try {
        //     $data = array_map(function ($row) {
        //         return array_combine($this->header, $row);
        //     }, $this->chunk);

        //     DB::table($this->fileName)->insert($data);
        //      // Update progress
        //      $this->updateProgress(count($data));
        //     // Log successful insertion
        //     Log::info("Successfully inserted chunk for {$this->fileName}");
        //     // DB::commit();
        // } catch (Exception $e) {
        //     // DB::rollBack();
        //     Log::error("Data insertion error for {$this->fileName}: " . $e->getMessage());
        //     Log::error("Stack trace: " . $e->getTraceAsString());
        //     throw $e;
        // }


        try {
            $data = array_map(function ($row) {
                // Ensure the row has the same number of elements as the header
                if (count($this->header) === count($row)) {
                    return array_combine($this->header, $row);
                } else {
                    // Log the mismatch or handle it as necessary
                    Log::warning('Row skipped due to mismatched column count.', [
                        'row' => $row,
                        'expected_columns' => $this->header,
                    ]);
                    return null; // Return null for rows that don't match
                }
            }, $this->chunk);

            // Filter out null values resulting from skipped rows
            $data = array_filter($data);

            if (!empty($data)) {
                DB::table($this->fileName)->insert($data);
                // Update progress
                $this->updateProgress(count($data));
                // Log successful insertion
                Log::info("Successfully inserted chunk for {$this->fileName}");
            }

        } catch (Exception $e) {
            Log::error("Data insertion error for {$this->fileName}: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }



    //     DB::beginTransaction();
    // try {
    //     foreach (array_chunk($this->header, 1000) as $chunk) {
    //         DB::table($this->fileName)->insert($chunk);
    //     }
    //     DB::commit();
    //     $this->updateProgress(count($this->chunk));
    // } catch (Exception $e) {
    //     DB::rollBack();
    //     Log::error("Data insertion error: " . $e->getMessage());
    //     throw $e;
    // }

    }
    public function failed(Exception $exception)
    {
        // Log the error or take any necessary actions
        Log::error('Job failed: ' . $exception->getMessage());
    }
    private function updateProgress($processedCount)
    {
        $newProcessedRows = $this->processedRows + $processedCount;
        $progress = ($newProcessedRows / $this->totalRows) * 100;
        Cache::put('import_progress_' . $this->batch()->id, $progress, 3600);
    }
    public function updateBatchProgress()
    {
        // Get the batch instance
        $batch = Batch::find($this->batchId);

        // Update the batch progress
        $batch->progress = ($batch->totalJobs - $batch->pendingJobs) / $batch->totalJobs * 100;

        // Save the batch progress
        $batch->save();
    }
    // public function batchId(): string
    // {
    //     return $this->batchId;
    // }

    // public function withBatchId(string $batchId): self
    // {
    //     $this->batchId = $batchId;
    //     return $this;
    // }

}
