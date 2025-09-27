<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductIdFormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example products demonstrating the new ID format
        $examples = [
            // Beds (main category 1) - Queen/King bed (subcategory 1)
            [
                'custom_id' => '1101',
                'room_category' => 1, // Bedroom
                'subcategory' => 'queen-king-bed'
            ],
            // Cabinets (main category 2) - Wardrobe (subcategory 2)
            [
                'custom_id' => '2201',
                'room_category' => 1, // Bedroom
                'subcategory' => 'wardrobe'
            ],
            // Chairs (main category 3) - Dining Chair (subcategory 2)
            [
                'custom_id' => '3201',
                'room_category' => 3, // Dining Room
                'subcategory' => 'dining-chair'
            ],
            // Tables (main category 4) - Coffee Table (subcategory 4)
            [
                'custom_id' => '4401',
                'room_category' => 2, // Living Room
                'subcategory' => 'coffee-table'
            ],
            // Shelves (main category 5) - Wall Shelf (subcategory 1)
            [
                'custom_id' => '5101',
                'room_category' => 5, // Office and Study
                'subcategory' => 'wall-shelf'
            ],
            // Sofas (main category 6) - Indoor Sofa (subcategory 1)
            [
                'custom_id' => '6101',
                'room_category' => 2, // Living Room
                'subcategory' => 'indoor-sofa'
            ]
        ];

        foreach ($examples as $example) {
            $category = Category::where('slug', $example['subcategory'])->first();
            if ($category) {
                echo "Example Custom Product ID: {$example['custom_id']}\n";
                echo "- Main Category: " . substr($example['custom_id'], 0, 1) . "\n";
                echo "- Sub Category: " . substr($example['custom_id'], 1, 1) . "\n";
                echo "- Product Number: " . substr($example['custom_id'], 2, 2) . "\n";
                echo "- Room Category: " . $example['room_category'] . "\n";
                echo "- Category: {$category->name}\n";
                echo "---\n";
            }
        }

        echo "\nProduct categorization system setup complete!\n";
        echo "Custom ID format: AABBCC where:\n";
        echo "- AA: Main category ID (1-6)\n";
        echo "- BB: Subcategory ID within main category\n"; 
        echo "- CC: Product sequence number (01-99)\n";
        echo "\nRoom categories downloaded:\n";
        echo "1 - Bedroom\n";
        echo "2 - Living Room\n";
        echo "3 - Dining Room\n";
        echo "4 - Bathroom\n";
        echo "5 - Office and Study\n";
        echo "6 - Garden and Balcony\n";
    }
}
