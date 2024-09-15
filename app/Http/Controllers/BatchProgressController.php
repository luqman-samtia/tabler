<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Batch;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Bus;

class BatchProgressController extends Controller
{
    public function checkImportProgress($batchId)
    {
        $batch = Bus::findBatch($batchId);
    $progress = Cache::get('import_progress_' . $batchId, 0);

    return response()->json([
        'progress' => round($progress, 2),
        'finished' => $batch->finished(),
        'failed' => $batch->hasFailures(),
        'processed' => $batch->processedJobs(),
        'total' => $batch->totalJobs
    ]);
    }
}
