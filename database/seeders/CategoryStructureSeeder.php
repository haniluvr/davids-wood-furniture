<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoryStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing categories (disable foreign key checks temporarily)
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [
            [
                'id' => 1,
                'name' => 'Beds',
                'slug' => 'beds',
                'description' => 'Various types of beds and sleeping furniture',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Cabinets',
                'slug' => 'cabinets',
                'description' => 'Storage furniture including wardrobes and drawers',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'id' => 3,
                'name' => 'Chairs',
                'slug' => 'chairs',
                'description' => 'Seating furniture for indoor and outdoor use',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'id' => 4,
                'name' => 'Tables',
                'slug' => 'tables',
                'description' => 'Various types of tables for different purposes',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'id' => 5,
                'name' => 'Shelves',
                'slug' => 'shelves',
                'description' => 'Storage and display shelving units',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'id' => 6,
                'name' => 'Sofas',
                'slug' => 'sofas',
                'description' => 'Comfortable seating for living spaces',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        // Create main categories
        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create subcategories
        $subcategories = [
            // Beds subcategories (category_id = 1)
            ['name' => 'King/Queen Sized Bed', 'slug' => 'king-queen-sized-bed', 'parent_id' => 1],
            ['name' => 'Bunk Bed', 'slug' => 'bunk-bed', 'parent_id' => 1],
            ['name' => 'Bed with Storage', 'slug' => 'bed-with-storage', 'parent_id' => 1],
            ['name' => 'Loft Bed', 'slug' => 'loft-bed', 'parent_id' => 1],
            ['name' => 'Sofa Bed', 'slug' => 'sofa-bed', 'parent_id' => 1],
            ['name' => 'Day Bed', 'slug' => 'day-bed', 'parent_id' => 1],
            ['name' => 'Nursery Bed', 'slug' => 'nursery-bed', 'parent_id' => 1],

            // Cabinets subcategories (category_id = 2)
            ['name' => 'Chest Drawer', 'slug' => 'chest-drawer', 'parent_id' => 2],
            ['name' => 'Wardrobe', 'slug' => 'wardrobe', 'parent_id' => 2],
            ['name' => 'Cupboard', 'slug' => 'cupboard', 'parent_id' => 2],
            ['name' => 'Sideboard', 'slug' => 'sideboard', 'parent_id' => 2],
            ['name' => 'Display Cabinet', 'slug' => 'display-cabinet', 'parent_id' => 2],

            // Chairs subcategories (category_id = 3)
            ['name' => 'Armchair', 'slug' => 'armchair', 'parent_id' => 3],
            ['name' => 'Dining Chair', 'slug' => 'dining-chair', 'parent_id' => 3],
            ['name' => 'Stool', 'slug' => 'stool', 'parent_id' => 3],
            ['name' => 'Outdoor Chair', 'slug' => 'outdoor-chair', 'parent_id' => 3],
            ['name' => 'Sunlounge', 'slug' => 'sunlounge', 'parent_id' => 3],

            // Tables subcategories (category_id = 4)
            ['name' => 'Dressing Table', 'slug' => 'dressing-table', 'parent_id' => 4],
            ['name' => 'Console Table', 'slug' => 'console-table', 'parent_id' => 4],
            ['name' => 'Dining Table', 'slug' => 'dining-table', 'parent_id' => 4],
            ['name' => 'Coffee Table', 'slug' => 'coffee-table', 'parent_id' => 4],
            ['name' => 'Night Table', 'slug' => 'night-table', 'parent_id' => 4],
            ['name' => 'Side Table', 'slug' => 'side-table', 'parent_id' => 4],
            ['name' => 'Desk', 'slug' => 'desk', 'parent_id' => 4],
            ['name' => 'Bar Table', 'slug' => 'bar-table', 'parent_id' => 4],
            ['name' => 'Outdoor Table', 'slug' => 'outdoor-table', 'parent_id' => 4],

            // Shelves subcategories (category_id = 5)
            ['name' => 'Wall Shelf', 'slug' => 'wall-shelf', 'parent_id' => 5],
            ['name' => 'Bookcase', 'slug' => 'bookcase', 'parent_id' => 5],

            // Sofas subcategories (category_id = 6)
            ['name' => 'Indoor Sofa', 'slug' => 'indoor-sofa', 'parent_id' => 6],
            ['name' => 'Outdoor Sofa', 'slug' => 'outdoor-sofa', 'parent_id' => 6],
        ];

        foreach ($subcategories as $subcategory) {
            Category::create([
                'name' => $subcategory['name'],
                'slug' => $subcategory['slug'],
                'description' => null,
                'is_active' => true,
                'sort_order' => 0,
                'parent_id' => $subcategory['parent_id'],
            ]);
        }
    }
}
