<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load IKEA data for reference
        $ikeaData = json_decode(file_get_contents(base_path('ikea_data.json')), true);

        // Define category and subcategory mappings
        $categoryMappings = [
            1 => [ // Beds
                'subcategories' => [
                    1 => ['name' => 'King/Queen Sized Bed', 'min_qty' => 8],
                    2 => ['name' => 'Bunk Bed', 'min_qty' => 4],
                    3 => ['name' => 'Bed with Storage', 'min_qty' => 5],
                    4 => ['name' => 'Loft Bed', 'min_qty' => 4],
                    5 => ['name' => 'Sofa Bed', 'min_qty' => 7],
                    6 => ['name' => 'Day Bed', 'min_qty' => 4],
                    7 => ['name' => 'Nursery Bed', 'min_qty' => 3],
                ],
                'room_categories' => ['bedroom'],
                'materials' => ['Solid Oak', 'Pine Wood', 'MDF with Oak Veneer', 'Birch Plywood', 'Ash Wood'],
                'weight_range' => [25, 80],
                'dimensions_range' => [
                    'length' => [180, 220],
                    'width' => [90, 200],
                    'height' => [30, 50],
                ],
            ],
            2 => [ // Cabinets
                'subcategories' => [
                    1 => ['name' => 'Chest Drawer', 'min_qty' => 8],
                    2 => ['name' => 'Wardrobe', 'min_qty' => 8],
                    3 => ['name' => 'Cupboard', 'min_qty' => 5],
                    4 => ['name' => 'Sideboard', 'min_qty' => 5],
                    5 => ['name' => 'Display Cabinet', 'min_qty' => 5],
                ],
                'room_categories' => ['bedroom', 'living-room', 'dining-room'],
                'materials' => ['Solid Oak', 'Pine Wood', 'MDF with Oak Veneer', 'Birch Plywood', 'Ash Wood'],
                'weight_range' => [15, 60],
                'dimensions_range' => [
                    'length' => [60, 180],
                    'width' => [35, 60],
                    'height' => [70, 200],
                ],
            ],
            3 => [ // Chairs
                'subcategories' => [
                    1 => ['name' => 'Armchair', 'min_qty' => 8],
                    2 => ['name' => 'Dining Chair', 'min_qty' => 7],
                    3 => ['name' => 'Stool', 'min_qty' => 3],
                    4 => ['name' => 'Outdoor Chair', 'min_qty' => 5],
                    5 => ['name' => 'Sunlounge', 'min_qty' => 3],
                ],
                'room_categories' => ['dining-room', 'living-room', 'garden-and-balcony', 'office-and-study'],
                'materials' => ['Solid Oak', 'Pine Wood', 'Ash Wood', 'Teak Wood', 'Rattan'],
                'weight_range' => [3, 15],
                'dimensions_range' => [
                    'length' => [40, 60],
                    'width' => [40, 60],
                    'height' => [80, 110],
                ],
            ],
            4 => [ // Tables
                'subcategories' => [
                    1 => ['name' => 'Dressing Table', 'min_qty' => 8],
                    2 => ['name' => 'Console Table', 'min_qty' => 6],
                    3 => ['name' => 'Dining Table', 'min_qty' => 8],
                    4 => ['name' => 'Coffee Table', 'min_qty' => 6],
                    5 => ['name' => 'Night Table', 'min_qty' => 5],
                    6 => ['name' => 'Side Table', 'min_qty' => 5],
                    7 => ['name' => 'Desk', 'min_qty' => 4],
                    8 => ['name' => 'Bar Table', 'min_qty' => 3],
                    9 => ['name' => 'Outdoor Table', 'min_qty' => 3],
                ],
                'room_categories' => ['dining-room', 'living-room', 'bedroom', 'office-and-study', 'garden-and-balcony'],
                'materials' => ['Solid Oak', 'Pine Wood', 'MDF with Oak Veneer', 'Birch Plywood', 'Ash Wood', 'Teak Wood'],
                'weight_range' => [8, 45],
                'dimensions_range' => [
                    'length' => [60, 200],
                    'width' => [40, 100],
                    'height' => [40, 80],
                ],
            ],
            5 => [ // Shelves
                'subcategories' => [
                    1 => ['name' => 'Wall Shelf', 'min_qty' => 5],
                    2 => ['name' => 'Bookcase', 'min_qty' => 3],
                ],
                'room_categories' => ['living-room', 'bedroom', 'office-and-study'],
                'materials' => ['Solid Oak', 'Pine Wood', 'MDF with Oak Veneer', 'Birch Plywood'],
                'weight_range' => [5, 25],
                'dimensions_range' => [
                    'length' => [60, 200],
                    'width' => [20, 40],
                    'height' => [30, 200],
                ],
            ],
            6 => [ // Sofas
                'subcategories' => [
                    1 => ['name' => 'Indoor Sofa', 'min_qty' => 4],
                    2 => ['name' => 'Outdoor Sofa', 'min_qty' => 3],
                ],
                'room_categories' => ['living-room', 'garden-and-balcony'],
                'materials' => ['Solid Oak Frame', 'Pine Wood Frame', 'Teak Wood Frame', 'Ash Wood Frame'],
                'weight_range' => [35, 80],
                'dimensions_range' => [
                    'length' => [150, 300],
                    'width' => [60, 100],
                    'height' => [70, 90],
                ],
            ],
        ];

        // Product name templates based on IKEA style
        $productNames = [
            'beds' => [
                'AURORA', 'NORDIC', 'FOREST', 'RIVER', 'MOUNTAIN', 'VALLEY', 'MEADOW', 'SUNRISE', 'MOONLIGHT', 'STARLIGHT',
                'BIRCH', 'OAK', 'PINE', 'ASH', 'MAPLE', 'WALNUT', 'CHERRY', 'MAHOGANY', 'TEAK', 'CEDAR',
            ],
            'cabinets' => [
                'STORAGE', 'HARMONY', 'ELEGANCE', 'CLASSIC', 'MODERN', 'VINTAGE', 'RUSTIC', 'CONTEMPORARY', 'TRADITIONAL', 'MINIMALIST',
                'CHEST', 'WARDROBE', 'CUPBOARD', 'SIDEBOARD', 'DISPLAY', 'CABINET', 'DRESSER', 'COMMODE', 'BUFFET', 'CONSOLE',
            ],
            'chairs' => [
                'COMFORT', 'RELAX', 'DINING', 'OFFICE', 'STUDY', 'LOUNGE', 'PATIO', 'GARDEN', 'BALCONY', 'TERRACE',
                'SEAT', 'CHAIR', 'STOOL', 'BENCH', 'ROCKER', 'SWIVEL', 'FOLDING', 'STACKING', 'OUTDOOR', 'INDOOR',
            ],
            'tables' => [
                'DINING', 'COFFEE', 'SIDE', 'CONSOLE', 'DESK', 'WORK', 'STUDY', 'NIGHT', 'BEDROOM', 'LIVING',
                'TABLE', 'DESK', 'STAND', 'SURFACE', 'TOP', 'WORKTOP', 'COUNTER', 'BAR', 'PATIO', 'OUTDOOR',
            ],
            'shelves' => [
                'WALL', 'FLOOR', 'BOOK', 'DISPLAY', 'STORAGE', 'FLOATING', 'CORNER', 'MODULAR', 'ADJUSTABLE', 'FIXED',
                'SHELF', 'SHELVES', 'BOOKCASE', 'CABINET', 'UNIT', 'RACK', 'STAND', 'HOLDER', 'STORAGE', 'DISPLAY',
            ],
            'sofas' => [
                'COMFORT', 'LUXURY', 'MODERN', 'CLASSIC', 'SECTIONAL', 'LOUNGE', 'RECLINER', 'FUTON', 'LOVESEAT', 'CHAISE',
                'SOFA', 'COUCH', 'SETTEE', 'DIVAN', 'CHESTERFIELD', 'CHESTER', 'DIVAN', 'OTTOMAN', 'FOOTSTOOL', 'BENCH',
            ],
        ];

        $productCount = 0;
        $targetCount = 200;

        // Distribute products across categories
        $productsPerCategory = ceil($targetCount / 6);

        foreach ($categoryMappings as $categoryId => $categoryData) {
            $subcategoryIds = array_keys($categoryData['subcategories']);
            $productsForThisCategory = min($productsPerCategory, $targetCount - $productCount);

            for ($i = 0; $i < $productsForThisCategory && $productCount < $targetCount; $i++) {
                $subcategoryId = fake()->randomElement($subcategoryIds);
                $subcategoryData = $categoryData['subcategories'][$subcategoryId];

                // Generate product name
                $categoryNames = ['beds', 'cabinets', 'chairs', 'tables', 'shelves', 'sofas'];
                $categoryName = $categoryNames[$categoryId - 1];
                $namePrefix = fake()->randomElement($productNames[$categoryName]);
                $nameSuffix = fake()->randomElement($productNames[$categoryName]);
                $productName = $namePrefix.' '.$subcategoryData['name'];

                // Generate unique slug
                $baseSlug = Str::slug($productName);
                $slug = $baseSlug;
                $counter = 1;
                while (Product::where('slug', $slug)->exists()) {
                    $slug = $baseSlug.'-'.$counter;
                    $counter++;
                }

                // Generate dimensions
                $dimensions = $categoryData['dimensions_range'];
                $length = fake()->numberBetween($dimensions['length'][0], $dimensions['length'][1]);
                $width = fake()->numberBetween($dimensions['width'][0], $dimensions['width'][1]);
                $height = fake()->numberBetween($dimensions['height'][0], $dimensions['height'][1]);
                $dimensionsString = "{$length}x{$width}x{$height} cm";

                // Generate weight
                $weight = fake()->numberBetween($categoryData['weight_range'][0], $categoryData['weight_range'][1]);

                // Generate pricing (in PHP)
                $basePrice = fake()->numberBetween(1500, 25000);
                $costPrice = $basePrice * fake()->randomFloat(2, 0.4, 0.7); // 40-70% of base price
                $salePrice = fake()->optional(0.3)->numberBetween($basePrice * 0.7, $basePrice * 0.9); // 30% chance of sale

                // Generate stock quantity
                $stockQuantity = fake()->numberBetween($subcategoryData['min_qty'], $subcategoryData['min_qty'] + 20);

                // Generate room categories (can be multiple)
                $roomCategories = fake()->randomElements($categoryData['room_categories'], fake()->numberBetween(1, count($categoryData['room_categories'])));

                // Generate images (using IKEA data as reference)
                $ikeaItem = fake()->randomElement($ikeaData);
                $mainImage = $ikeaItem['Plp-image Image'] ?? $ikeaItem['Image'] ?? 'https://via.placeholder.com/400x300?text=Product+Image';
                $galleryImages = [];

                // Generate 2-4 additional gallery images
                for ($j = 0; $j < fake()->numberBetween(2, 4); $j++) {
                    $galleryItem = fake()->randomElement($ikeaData);
                    $galleryImages[] = $galleryItem['Plp-image Image'] ?? $galleryItem['Image'] ?? 'https://via.placeholder.com/400x300?text=Gallery+Image';
                }

                // Generate description
                $material = fake()->randomElement($categoryData['materials']);
                $description = "Beautifully crafted {$subcategoryData['name']} made from premium {$material}. ".
                    "Perfect for {$categoryData['room_categories'][0]} and other living spaces. ".
                    "Dimensions: {$dimensionsString}. ".
                    'Features solid construction and elegant design that complements any interior style.';

                $shortDescription = "Premium {$material} {$subcategoryData['name']} - {$dimensionsString}";

                // Generate unique SKU
                $skuPrefix = strtoupper(substr($namePrefix, 0, 3));
                $sku = $skuPrefix.'-'.str_pad($productCount + 1, 4, '0', STR_PAD_LEFT);
                $counter = 1;
                while (Product::where('sku', $sku)->exists()) {
                    $sku = $skuPrefix.'-'.str_pad($productCount + 1, 3, '0', STR_PAD_LEFT).$counter;
                    $counter++;
                }

                // Generate meta data
                $metaData = [
                    'keywords' => implode(', ', array_merge([$subcategoryData['name'], $material], $roomCategories)),
                    'og_title' => $productName,
                    'og_description' => $shortDescription,
                    'og_image' => $mainImage,
                ];

                Product::create([
                    'category_id' => $categoryId,
                    'subcategory_id' => $subcategoryId,
                    'room_category' => $roomCategories,
                    'name' => $productName,
                    'slug' => $slug,
                    'description' => $description,
                    'short_description' => $shortDescription,
                    'price' => $basePrice,
                    'cost_price' => $costPrice,
                    'sale_price' => $salePrice,
                    'sku' => $sku,
                    'barcode' => fake()->ean13(),
                    'stock_quantity' => $stockQuantity,
                    'low_stock_threshold' => $subcategoryData['min_qty'],
                    'manage_stock' => true,
                    'in_stock' => $stockQuantity > 0,
                    'weight' => $weight,
                    'dimensions' => $dimensionsString,
                    'tax_class' => 'standard',
                    'material' => $material,
                    'images' => [$mainImage],
                    'gallery' => $galleryImages,
                    'featured' => fake()->boolean(20), // 20% chance of being featured
                    'is_active' => true,
                    'sort_order' => $productCount + 1,
                    'meta_data' => $metaData,
                    'created_by' => 1, // Assuming admin user ID 1
                    'updated_by' => 1,
                ]);

                $productCount++;
            }
        }

        $this->command->info("Created {$productCount} products successfully!");
    }
}
