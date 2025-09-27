<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define main categories
        $mainCategories = [
            [
                'name' => 'Beds',
                'slug' => 'beds',
                'description' => 'Wooden beds and bedroom furniture.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Cabinets',
                'slug' => 'cabinets', 
                'description' => 'Storage solutions including dressers, wardrobes, and storage cabinets.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Chairs',
                'slug' => 'chairs',
                'description' => 'Handcrafted wooden chairs for dining, office, and relaxation.',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Tables',
                'slug' => 'tables',
                'description' => 'Beautiful tables for dining, coffee, and office use.',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Shelves',
                'slug' => 'shelves',
                'description' => 'Bookshelves, display shelves, and wall-mounted storage.',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Sofas',
                'slug' => 'sofas',
                'description' => 'Comfortable wooden sofas and seating arrangements.',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        $mainCategoryObjects = [];
        foreach ($mainCategories as $category) {
            $mainCategoryObjects[] = Category::create($category);
        }

        // Define subcategories structure
        $subCategories = [
            // Beds subcategories
            1 => [
                ['name' => 'Queen/King Sized Bed', 'slug' => 'queen-king-bed'],
                ['name' => 'Sofabed', 'slug' => 'sofabed'],
                ['name' => 'Daybed', 'slug' => 'daybed'],
                ['name' => 'Loft Bed', 'slug' => 'loft-bed'],
                ['name' => 'Bed with Storage', 'slug' => 'bed-storage'],
                ['name' => 'Bunk Bed', 'slug' => 'bunk-bed'],
                ['name' => 'Nursery Bed', 'slug' => 'nursery-bed'],
            ],
            // Cabinets subcategories
            2 => [
                ['name' => 'Chest Drawer', 'slug' => 'chest-drawer'],
                ['name' => 'Wardrobe', 'slug' => 'wardrobe'],
                ['name' => 'Cupboard', 'slug' => 'cupboard'],
                ['name' => 'Sideboard', 'slug' => 'sideboard'],
                ['name' => 'Display Cabinet', 'slug' => 'display-cabinet'],
            ],
            // Chairs subcategories
            3 => [
                ['name' => 'Arm Chair', 'slug' => 'arm-chair'],
                ['name' => 'Dining Chair', 'slug' => 'dining-chair'],
                ['name' => 'Stool', 'slug' => 'stool'],
                ['name' => 'Sunlongue', 'slug' => 'sunlongue'],
                ['name' => 'Outdoor Chair', 'slug' => 'outdoor-chair'],
            ],
            // Tables subcategories
            4 => [
                ['name' => 'Dressing Table', 'slug' => 'dressing-table'],
                ['name' => 'Console Table', 'slug' => 'console-table'],
                ['name' => 'Dining Table', 'slug' => 'dining-table'],
                ['name' => 'Coffee Table', 'slug' => 'coffee-table'],
                ['name' => 'Nightstand', 'slug' => 'nightstand'],
                ['name' => 'Desk', 'slug' => 'desk'],
                ['name' => 'Side Table', 'slug' => 'side-table'],
                ['name' => 'Bar Table', 'slug' => 'bar-table'],
                ['name' => 'Outdoor Table', 'slug' => 'outdoor-table'],
            ],
            // Shelves subcategories
            5 => [
                ['name' => 'Wall Shelf', 'slug' => 'wall-shelf'],
                ['name' => 'Bookcase', 'slug' => 'bookcase'],
            ],
            // Sofas subcategories
            6 => [
                ['name' => 'Indoor Sofa', 'slug' => 'indoor-sofa'],
                ['name' => 'Outdoor Sofa', 'slug' => 'outdoor-sofa'],
            ],
        ];

        // Create subcategories
        foreach ($subCategories as $mainCategoryIndex => $subCats) {
            $mainCategory = $mainCategoryObjects[$mainCategoryIndex - 1];
            
            foreach ($subCats as $index => $subCat) {
                Category::create([
                    'name' => $subCat['name'],
                    'slug' => $subCat['slug'],
                    'description' => $subCat['name'],
                    'parent_id' => $mainCategory->id,
                    'is_active' => true,
                    'sort_order' => $mainCategory->sort_order,
                    'category_order' => $index + 1,
                ]);
            }
        }
    }
}
