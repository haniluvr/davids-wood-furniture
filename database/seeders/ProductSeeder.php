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

        // Product name generation similar to ProductRepopulationSeeder
        $prefixes = ['Premium', 'Classic', 'Modern', 'Elegant', 'Contemporary', 'Luxury', 'Deluxe', 'Executive', 'Professional', 'Signature'];
        $materials = ['Oak', 'Pine', 'Walnut', 'Mahogany', 'Teak', 'Maple', 'Cherry', 'Ash', 'Birch', 'Cedar'];

        // Reasonable PHP price ranges per category/subcategory (min, max)
        $priceRanges = [
            1 => [ // Beds
                1 => [18000, 45000], // King/Queen Sized Bed
                2 => [12000, 25000], // Bunk Bed
                3 => [18000, 40000], // Bed with Storage
                4 => [14000, 26000], // Loft Bed
                5 => [14000, 30000], // Sofa Bed
                6 => [10000, 18000], // Day Bed
                7 => [8000, 15000], // Nursery Bed
            ],
            2 => [ // Cabinets
                1 => [7000, 15000], // Chest Drawer
                2 => [15000, 35000], // Wardrobe
                3 => [6000, 14000], // Cupboard
                4 => [9000, 22000], // Sideboard
                5 => [12000, 28000], // Display Cabinet
            ],
            3 => [ // Chairs
                1 => [5000, 15000], // Armchair
                2 => [2000,  7000], // Dining Chair
                3 => [800,  3000], // Stool
                4 => [1500,  5000], // Outdoor Chair
                5 => [6000, 15000], // Sunlounge
            ],
            4 => [ // Tables
                1 => [8000, 18000], // Dressing Table
                2 => [6000, 15000], // Console Table
                3 => [12000, 35000], // Dining Table
                4 => [3000,  9000], // Coffee Table
                5 => [1500,  5000], // Night Table
                6 => [2000,  6000], // Side Table
                7 => [7000, 20000], // Desk
                8 => [6000, 15000], // Bar Table
                9 => [4000, 12000], // Outdoor Table
            ],
            5 => [ // Shelves
                1 => [1500,  6000], // Wall Shelf
                2 => [5000, 15000], // Bookcase
            ],
            6 => [ // Sofas
                1 => [20000, 60000], // Indoor Sofa
                2 => [15000, 40000], // Outdoor Sofa
            ],
        ];

        $productCount = 0;
        $targetCount = 200;

        // SKU helpers: map main category to starting subcategory code (per CategoryStructureSeeder order)
        // Beds: 07-13, Cabinets: 14-18, Chairs: 19-23, Tables: 24-32, Shelves: 33-34, Sofas: 35-36
        $subcategoryCodeStart = [
            1 => 7,   // Beds
            2 => 14,  // Cabinets
            3 => 19,  // Chairs
            4 => 24,  // Tables
            5 => 33,  // Shelves
            6 => 35,  // Sofas
        ];

        // Keep per-subcategory item counters to generate nn (item no.)
        $skuItemCounters = [];

        // Distribute products across categories
        $productsPerCategory = ceil($targetCount / 6);

        foreach ($categoryMappings as $categoryId => $categoryData) {
            $subcategoryIds = array_keys($categoryData['subcategories']);
            $productsForThisCategory = min($productsPerCategory, $targetCount - $productCount);

            for ($i = 0; $i < $productsForThisCategory && $productCount < $targetCount; $i++) {
                $subcategoryId = $subcategoryIds[array_rand($subcategoryIds)];
                $subcategoryData = $categoryData['subcategories'][$subcategoryId];

                // Generate product name (similar to ProductRepopulationSeeder)
                $prefix = $prefixes[($productCount) % count($prefixes)];
                $material = $materials[($productCount) % count($materials)];
                $productName = "{$prefix} {$material} {$subcategoryData['name']}";

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
                $length = rand($dimensions['length'][0], $dimensions['length'][1]);
                $width = rand($dimensions['width'][0], $dimensions['width'][1]);
                $height = rand($dimensions['height'][0], $dimensions['height'][1]);
                $dimensionsString = "{$length}x{$width}x{$height} cm";

                // Generate weight
                $weight = rand($categoryData['weight_range'][0], $categoryData['weight_range'][1]);

                // Generate pricing (in PHP) — subcategory-specific, reasonable
                $range = $priceRanges[$categoryId][$subcategoryId] ?? [5000, 20000];
                $basePrice = rand($range[0], $range[1]);
                $costPrice = (int) round($basePrice * (rand(50, 70) / 100), 2); // 50–70% of base
                // 25% chance of sale; 10–20% discount if on sale
                if (rand(1, 100) <= 25) {
                    $discountPercent = rand(10, 20);
                    $salePrice = (int) round($basePrice * (100 - $discountPercent) / 100, 2);
                } else {
                    $salePrice = null;
                }

                // Generate stock quantity
                $stockQuantity = rand($subcategoryData['min_qty'], $subcategoryData['min_qty'] + 20);

                // Generate room categories (can be multiple)
                $roomCount = rand(1, count($categoryData['room_categories']));
                $roomCategories = array_slice($categoryData['room_categories'], 0, $roomCount);

                // Generate images (using IKEA data as reference)
                $ikeaItem = $ikeaData[array_rand($ikeaData)];
                $mainImage = $ikeaItem['Plp-image Image'] ?? $ikeaItem['Image'] ?? 'https://via.placeholder.com/400x300?text=Product+Image';
                $galleryImages = [];

                // Generate 2-4 additional gallery images
                $galleryCount = rand(2, 4);
                for ($j = 0; $j < $galleryCount; $j++) {
                    $galleryItem = $ikeaData[array_rand($ikeaData)];
                    $galleryImages[] = $galleryItem['Plp-image Image'] ?? $galleryItem['Image'] ?? 'https://via.placeholder.com/400x300?text=Gallery+Image';
                }

                // Generate description: 3 sentences, category-aware, no dimensions
                $material = $categoryData['materials'][array_rand($categoryData['materials'])];
                switch ($categoryId) {
                    case 1: // Beds
                        $description = "This {$subcategoryData['name']} is crafted from quality {$material} to deliver lasting comfort. ".
                            'Its refined profile blends easily with both modern and classic bedrooms. '.
                            'Built for nightly use with sturdy joinery and a smooth, low-maintenance finish.';

                        break;
                    case 2: // Cabinets
                        $description = "Organize your space with this {$subcategoryData['name']} in durable {$material}. ".
                            'Clean lines and thoughtful storage make it easy to keep essentials within reach. '.
                            'Finished to resist daily wear while complementing a wide range of interiors.';

                        break;
                    case 3: // Chairs
                        $description = "Enjoy comfortable seating with this {$subcategoryData['name']} made from reliable {$material}. ".
                            'The balanced proportions support everyday use at home or in shared spaces. '.
                            'A versatile silhouette that pairs well with contemporary and traditional décor.';

                        break;
                    case 4: // Tables
                        $description = "A dependable {$subcategoryData['name']} built from sturdy {$material} for daily tasks and gatherings. ".
                            'The design focuses on stability and a clean look that suits many room styles. '.
                            'Finished for easy care to keep surfaces looking fresh.';

                        break;
                    case 5: // Shelves
                        $description = "Display and store with this {$subcategoryData['name']} crafted from {$material}. ".
                            'Its straightforward form showcases books, décor, and essentials without clutter. '.
                            'Designed for simple installation and a tidy, balanced look.';

                        break;
                    case 6: // Sofas
                        $description = "Settle in with this {$subcategoryData['name']} constructed on a robust {$material} frame. ".
                            'Cushions and support are tuned for everyday comfort in living spaces. '.
                            'A timeless profile that holds up to frequent use.';

                        break;
                    default:
                        $description = "Thoughtfully built from {$material} for everyday reliability. ".
                            'A clean design that blends into a variety of interiors. '.
                            'Finished to be easy to live with and simple to maintain.';

                        break;
                }

                $shortDescription = "Premium {$subcategoryData['name']} in {$material} with a clean, versatile design.";

                // Generate SKU in format nnnnn (A-BB-CC):
                // A = main category (1-6), BB = subcategory code (07-36), CC = item number (01-99)
                $subCodeStart = $subcategoryCodeStart[$categoryId] ?? 0;
                $subCode = $subCodeStart + ($subcategoryId - 1);

                // Next item number for this subcategory code
                $nextItem = ($skuItemCounters[$subCode] ?? 0) + 1;

                // Ensure uniqueness just in case by incrementing until free (cap at 99)
                do {
                    if ($nextItem > 99) {
                        $nextItem = 1;
                    }
                    $candidateSku = sprintf('%d%02d%02d', $categoryId, $subCode, $nextItem);
                    $exists = Product::where('sku', $candidateSku)->exists();
                    if ($exists) {
                        $nextItem++;
                    }
                } while ($exists && $nextItem <= 100);

                $skuItemCounters[$subCode] = $nextItem;
                $sku = sprintf('%d%02d%02d', (int) $categoryId, (int) $subCode, (int) $nextItem);

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
                    'barcode' => str_pad(rand(100000000000, 999999999999), 13, '0', STR_PAD_LEFT),
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
                    'featured' => (rand(1, 100) <= 20), // 20% chance of being featured
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
