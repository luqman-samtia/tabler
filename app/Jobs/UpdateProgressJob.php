<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\Cache;

class UpdateProgressJob implements ShouldQueue
{
    
        use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;
    
        protected $totalRows;
        protected $processedRows;
    
        public function __construct(int $totalRows, int $processedRows)
        {
            $this->totalRows = $totalRows;
            $this->processedRows = $processedRows;
        }
    
        public function handle(): void
        {
            $progress = ($this->processedRows / $this->totalRows) * 100;
            Cache::put('import_progress_' . $this->batch()->id, $progress, 3600);
        }
}
