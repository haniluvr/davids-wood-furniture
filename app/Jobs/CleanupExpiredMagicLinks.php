<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CleanupExpiredMagicLinks implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $magicLinkService = new \App\Services\MagicLinkService;
        $deletedCount = $magicLinkService->cleanupExpiredTokens();

        \Log::info('Cleaned up expired magic link tokens', [
            'deleted_count' => $deletedCount,
            'timestamp' => now(),
        ]);
    }
}
