<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupMagicLinksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'magic-links:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired magic link tokens';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $magicLinkService = new \App\Services\MagicLinkService;
        $deletedCount = $magicLinkService->cleanupExpiredTokens();

        $this->info("Cleaned up {$deletedCount} expired magic link tokens.");

        return 0;
    }
}
