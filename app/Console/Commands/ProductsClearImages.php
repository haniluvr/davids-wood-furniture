<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProductsClearImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:clear-images {--dry-run : Show how many rows would be updated without changing data} {--empty : Set columns to empty array []} {--null : Set columns to NULL}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the images and gallery columns for all products (set to NULL)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $count = DB::table('products')->count();
        $this->info("Products found: {$count}");

        if ($this->option('dry-run')) {
            $this->info('Dry run complete. No data changed.');

            return self::SUCCESS;
        }

        if ($this->option('null')) {
            $payload = ['images' => null, 'gallery' => null];
        } elseif ($this->option('empty')) {
            $payload = ['images' => json_encode([]), 'gallery' => json_encode([])];
        } else {
            // Default to file placeholder path so UI can render and can be removed per-product
            $placeholder = 'products/landscape-placeholder.svg';
            $payload = ['images' => json_encode([$placeholder]), 'gallery' => json_encode([$placeholder])];
        }

        $updated = DB::table('products')->update($payload);

        $this->info("Rows updated: {$updated}");

        return self::SUCCESS;
    }
}
