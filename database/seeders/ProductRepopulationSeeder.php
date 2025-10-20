<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductRepopulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing products (disable foreign key checks temporarily)
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define the product specifications with quantities
        $productSpecs = [
            // Beds (category_id = 1)
            'king-queen-sized-bed' => ['count' => 8, 'category_id' => 1, 'subcategory_id' => 7],
            'bunk-bed' => ['count' => 4, 'category_id' => 1, 'subcategory_id' => 8],
            'bed-with-storage' => ['count' => 5, 'category_id' => 1, 'subcategory_id' => 9],
            'loft-bed' => ['count' => 4, 'category_id' => 1, 'subcategory_id' => 10],
            'sofa-bed' => ['count' => 7, 'category_id' => 1, 'subcategory_id' => 11],
            'day-bed' => ['count' => 4, 'category_id' => 1, 'subcategory_id' => 12],
            'nursery-bed' => ['count' => 3, 'category_id' => 1, 'subcategory_id' => 13],

            // Cabinets (category_id = 2)
            'chest-drawer' => ['count' => 8, 'category_id' => 2, 'subcategory_id' => 14],
            'wardrobe' => ['count' => 8, 'category_id' => 2, 'subcategory_id' => 15],
            'cupboard' => ['count' => 5, 'category_id' => 2, 'subcategory_id' => 16],
            'sideboard' => ['count' => 5, 'category_id' => 2, 'subcategory_id' => 17],
            'display-cabinet' => ['count' => 5, 'category_id' => 2, 'subcategory_id' => 18],

            // Chairs (category_id = 3)
            'armchair' => ['count' => 8, 'category_id' => 3, 'subcategory_id' => 19],
            'dining-chair' => ['count' => 7, 'category_id' => 3, 'subcategory_id' => 20],
            'stool' => ['count' => 3, 'category_id' => 3, 'subcategory_id' => 21],
            'outdoor-chair' => ['count' => 5, 'category_id' => 3, 'subcategory_id' => 22],
            'sunlounge' => ['count' => 3, 'category_id' => 3, 'subcategory_id' => 23],

            // Tables (category_id = 4)
            'dressing-table' => ['count' => 8, 'category_id' => 4, 'subcategory_id' => 24],
            'console-table' => ['count' => 6, 'category_id' => 4, 'subcategory_id' => 25],
            'dining-table' => ['count' => 8, 'category_id' => 4, 'subcategory_id' => 26],
            'coffee-table' => ['count' => 6, 'category_id' => 4, 'subcategory_id' => 27],
            'night-table' => ['count' => 5, 'category_id' => 4, 'subcategory_id' => 28],
            'side-table' => ['count' => 5, 'category_id' => 4, 'subcategory_id' => 29],
            'desk' => ['count' => 4, 'category_id' => 4, 'subcategory_id' => 30],
            'bar-table' => ['count' => 3, 'category_id' => 4, 'subcategory_id' => 31],
            'outdoor-table' => ['count' => 3, 'category_id' => 4, 'subcategory_id' => 32],

            // Shelves (category_id = 5)
            'wall-shelf' => ['count' => 5, 'category_id' => 5, 'subcategory_id' => 33],
            'bookcase' => ['count' => 3, 'category_id' => 5, 'subcategory_id' => 34],

            // Sofas (category_id = 6)
            'indoor-sofa' => ['count' => 4, 'category_id' => 6, 'subcategory_id' => 35],
            'outdoor-sofa' => ['count' => 3, 'category_id' => 6, 'subcategory_id' => 36],
        ];

        // Load IKEA data for reference
        $ikeaData = json_decode(file_get_contents('ikea_data.json'), true);
        $ikeaIndex = 0;

        foreach ($productSpecs as $subcategorySlug => $spec) {
            $subcategory = Category::find($spec['subcategory_id']);
            $category = Category::find($spec['category_id']);

            for ($i = 1; $i <= $spec['count']; $i++) {
                // Get IKEA data for reference
                $ikeaItem = $ikeaData[$ikeaIndex % count($ikeaData)];
                $ikeaIndex++;

                // Generate product data
                $productName = $this->generateProductName($subcategory->name, $i);
                $slug = Str::slug($productName);

                // Determine room categories based on subcategory
                $roomCategories = $this->getRoomCategories($spec['subcategory_id']);

                // Generate pricing in Philippine Peso
                $price = $this->generatePrice($spec['subcategory_id'], $i);

                // Generate material
                $material = $this->generateMaterial($spec['subcategory_id']);

                // Generate images
                $images = $this->generateImages($subcategorySlug, $i);
                $gallery = $this->generateGallery($subcategorySlug, $i);

                Product::create([
                    'category_id' => $spec['category_id'],
                    'subcategory_id' => $spec['subcategory_id'],
                    'room_category' => $roomCategories,
                    'name' => $productName,
                    'slug' => $slug,
                    'description' => $this->generateDescription($productName, $subcategory->name, $material),
                    'short_description' => $this->generateShortDescription($productName, $subcategory->name),
                    'price' => $price,
                    'sku' => $this->generateSKU($spec['category_id'], $spec['subcategory_id'], $i),
                    'stock_quantity' => rand(5, 50),
                    'manage_stock' => true,
                    'in_stock' => true,
                    'material' => $material,
                    'images' => $images,
                    'gallery' => $gallery,
                    'featured' => $i <= 2, // First 2 products of each subcategory are featured
                    'is_active' => true,
                    'sort_order' => $i,
                    'meta_data' => $this->generateMetaData($spec['subcategory_id'], $material),
                ]);
            }
        }
    }

    private function generateProductName($subcategoryName, $index)
    {
        $prefixes = ['Premium', 'Classic', 'Modern', 'Elegant', 'Contemporary', 'Luxury', 'Deluxe', 'Executive'];
        $materials = ['Oak', 'Pine', 'Walnut', 'Mahogany', 'Teak', 'Maple', 'Cherry', 'Ash'];

        $prefix = $prefixes[($index - 1) % count($prefixes)];
        $material = $materials[($index - 1) % count($materials)];

        return "{$prefix} {$material} {$subcategoryName}";
    }

    private function getRoomCategories($subcategoryId)
    {
        $roomMappings = [
            // Beds
            7, 8, 9, 10, 11, 12, 13 => ['bedroom'],
            // Cabinets
            14, 15, 16, 17, 18 => ['bedroom', 'living-room'],
            // Chairs
            19 => ['living-room', 'bedroom'],
            20 => ['dining-room'],
            21 => ['dining-room', 'kitchen'],
            22, 23 => ['garden-and-balcony'],
            // Tables
            24 => ['bedroom'],
            25 => ['living-room', 'bedroom'],
            26 => ['dining-room'],
            27 => ['living-room'],
            28 => ['bedroom'],
            29 => ['living-room', 'bedroom'],
            30 => ['office-and-study'],
            31 => ['living-room', 'dining-room'],
            32 => ['garden-and-balcony'],
            // Shelves
            33, 34 => ['living-room', 'bedroom', 'office-and-study'],
            // Sofas
            35 => ['living-room'],
            36 => ['garden-and-balcony'],
        ];

        return $roomMappings[$subcategoryId] ?? ['living-room'];
    }

    private function generatePrice($subcategoryId, $index)
    {
        $basePrices = [
            // Beds
            7 => [15000, 25000], 8 => [8000, 15000], 9 => [12000, 20000], 10 => [10000, 18000],
            11 => [18000, 30000], 12 => [6000, 12000], 13 => [5000, 10000],
            // Cabinets
            14 => [3000, 8000], 15 => [12000, 25000], 16 => [5000, 12000], 17 => [8000, 18000], 18 => [10000, 22000],
            // Chairs
            19 => [5000, 12000], 20 => [2000, 6000], 21 => [1500, 4000], 22 => [3000, 8000], 23 => [8000, 15000],
            // Tables
            24 => [8000, 15000], 25 => [5000, 12000], 26 => [10000, 25000], 27 => [4000, 10000],
            28 => [2000, 6000], 29 => [3000, 8000], 30 => [8000, 18000], 31 => [6000, 15000], 32 => [5000, 12000],
            // Shelves
            33 => [2000, 6000], 34 => [5000, 12000],
            // Sofas
            35 => [15000, 35000], 36 => [12000, 25000],
        ];

        $range = $basePrices[$subcategoryId] ?? [5000, 15000];
        $basePrice = rand($range[0], $range[1]);

        // Add variation based on index
        $variation = ($index - 1) * 500;

        return $basePrice + $variation;
    }

    private function generateMaterial($subcategoryId)
    {
        $materials = [
            'Solid Oak Wood',
            'Pine Wood',
            'Walnut Wood',
            'Mahogany Wood',
            'Teak Wood',
            'Maple Wood',
            'Cherry Wood',
            'Ash Wood',
            'MDF with Wood Veneer',
            'Particle Board with Laminate',
        ];

        return $materials[array_rand($materials)];
    }

    private function generateImages($subcategorySlug, $index)
    {
        return [
            "{$subcategorySlug}-{$index}-1.jpg",
            "{$subcategorySlug}-{$index}-2.jpg",
        ];
    }

    private function generateGallery($subcategorySlug, $index)
    {
        return [
            "{$subcategorySlug}-{$index}-1.jpg",
            "{$subcategorySlug}-{$index}-2.jpg",
            "{$subcategorySlug}-{$index}-3.jpg",
            "{$subcategorySlug}-{$index}-4.jpg",
        ];
    }

    private function generateDescription($productName, $subcategoryName, $material)
    {
        return "A beautifully crafted {$subcategoryName} made from premium {$material}. This {$productName} features excellent craftsmanship and durability, perfect for any home. Designed with both style and functionality in mind, it will enhance your living space while providing years of reliable service.";
    }

    private function generateShortDescription($productName, $subcategoryName)
    {
        return "Premium {$subcategoryName} with elegant design and superior quality materials.";
    }

    private function generateSKU($categoryId, $subcategoryId, $index)
    {
        return sprintf('%d%02d%02d', $categoryId, $subcategoryId, $index);
    }

    private function generateMetaData($subcategoryId, $material)
    {
        return [
            'warranty' => '2 years',
            'assembly_required' => true,
            'material' => $material,
            'origin' => 'Philippines',
            'care_instructions' => 'Clean with dry cloth, avoid direct sunlight',
            'delivery_time' => '3-5 business days',
        ];
    }
}
