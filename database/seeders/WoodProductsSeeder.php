<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class WoodProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $woodProducts = $this->getWoodProducts();
        
        foreach ($woodProducts as $productData) {
            $category = Category::where('slug', $productData['category_slug'])->first();
            if (!$category) {
                echo "Category not found:" . $productData['category_slug'] . "\n";
                continue;
            }
            
            // Generate unique slug
            $productData['slug'] = Str::slug($productData['name']);
            
            Product::create([
                'category_id' => $category->id,
                'room_category' => $productData['room_category'],
                'name' => $productData['name'],
                'slug' => $productData['slug'],
                'description' => $productData['description'],
                'short_description' => $productData['short_description'] ?? null,
                'price' => $productData['price'],
                'sale_price' => $productData['sale_price'] ?? null,
                'sku' => $productData['sku'],
                'stock_quantity' => $productData['stock_quantity'],
                'manage_stock' => $productData['manage_stock'],
                'in_stock' => $productData['in_stock'],
                'weight' => $productData['weight'],
                'dimensions' => $productData['dimensions'],
                'material' => $productData['material'],
                'images' => $productData['images'],
                'featured' => $productData['featured'] ?? false,
                'is_active' => $productData['is_active'] ?? true,
                'sort_order' => rand(0, 99),
                'meta_data' => $productData['meta_data'],
            ]);
        }
    }

    private function getWoodProducts()
    {
        return [
            // BEDS (Main Category 1)
            [
                'category_slug' => 'queen-king-bed',
                'room_category' => 1,
                'name' => 'Solid Oak Queen Bed Frame',
                'slug' => 'solid-oak-queen-bed',
                'description' => 'Handcrafted solid oak queen bed frame with elegant grain patterns and sturdy construction. Perfect for any bedroom with timeless Scandinavian design.',
                'short_description' => 'Premium solid oak queen bed with quality joinery and European finish.',
                'price' => 28500.00,
                'sale_price' => 25500.00,
                'sku' => '1101QOB',
                'stock_quantity' => 15,
                'manage_stock' => true,
                'in_stock' => true,
                'weight' => '45 kg',
                'dimensions' => '160 x 200 x 85 cm',
                'material' => 'Solid Oak Wood',
                'images' => ['oak-queen-bed-main.jpg', 'oak-queen-bed-side.jpg'],
                'featured' => true,
                'is_active' => true,
                'meta_data' => [
                    'wood_type' => 'Oak',
                    'construction' => 'Solid Wood',
                    'finish' => 'Natural Wood Stain',
                    'assembly_required' => true
                ]
            ],
            [
                'category_slug' => 'daybed',
                'room_category' => 1,
                'name' => 'Pine Wood Daybed',
                'slug' => 'pine-daybed-classic',
                'description' => 'Versatile pine wood daybed perfect for guest sleeping or casual lounging. Features classic timber construction with natural pine finish.',
                'short_description' => 'Multi-purpose pine daybed with pull-out trundle for extra sleeping space.',
                'price' => 8500.00,
                'sale_price' => null,
                'sku' => '1301PDB',
                'stock_quantity' => 8,
                'manage_stock' => true,
                'in_stock' => true,
                'weight' => '25 kg',
                'dimensions' => '200 x 90 x 40 cm',
                'material' => 'Pine Wood Solid',
                'images' => ['pine-daybed-front.jpg', 'pine-daybed-angled.jpg'],
                'featured' => false,
                'is_active' => true,
                'meta_data' => [
                    'wood_type' => 'Pine',
                    'construction' => 'Solid Wood Joinery',
                    'finish' => 'Clear Pine Stain',
                    'storage_compartment' => true
                ]
            ],
            [
                'category_slug' => 'bunk-bed',
                'room_category' => 1,
                'name' => 'Solid Pine Twin Bunk Bed',
                'slug' => 'pine-twin-bunk-bed',
                'description' => 'Children\'s pine bunk bed with guardrails, ladder under, and optional pull-out trundler. Max support for each level 140kg.',
                'short_description' => 'Safe and sturdy children\'s pine bunk bed with full guardrails.',
                'price' => 18900.00,
                'sale_price' => 16900.00,
                'sku' => '1601BUN',
                'stock_quantity' => 8,
                'manage_stock' => true,
                'in_stock' => true,
                'weight' => '67 kg',
                'dimensions' => '200 x 105 x 168 cm',
                'material' => 'Solida Pine Wood',
                'images' => ['bunkbed-angled.jpg', 'bunkbed-safety-rail-closeup.jpg'],
                'featured' => false,
                'is_active' => true,
                'meta_data' => [
                    'wood_type' => 'Pine',
                    'max_support_weight' => '140kg per level',
                    'safety_standard' => 'Children\'s Safety',
                    'age_range' => '3+ years',
                    'includes' => 'Safety guardrails'
                ]
            ],
            // Continue building the product list
            [
                'category_slug' => 'wardrobe',
                'room_category' => 1,
                'name' => 'Solid Oak 4-Door Wardrobe',
                'slug' => 'oak-wardrobe-4-door',
                'description' => 'Spacious hardwood wardrobe featuring solid oak construction with 4 hinged doors, interior shelving, and hanging space. Premium dovetail jointed drawers.',
                'short_description' => 'Spacious oak wardrobe with drawers and hanging space for complete bedroom storage.',
                'price' => 42000.00,
                'sale_price' => 37500.00,
                'sku' => '2202WAR',
                'stock_quantity' => 6,
                'manage_stock' => true,
                'in_stock' => true,
                'weight' => '135 kg',
                'dimensions' => '200 x 60 x 200 cm',
                'material' => 'Solid Oak Hardwood',
                'images' => ['oak-wardrobe-front.jpg', 'oak-wardrobe-open.jpg', 'oak-wardrobe-side.jpg'],
                'featured' => true,
                'is_active' => true,
                'meta_data' => [
                    'wood_type' => 'Oak',
                    'construction' => 'Solid Hardwood',
                    'hardware' => 'Premium Brass Hinges',
                    'storage_zones' => ['hanging', 'folded', 'accessories']
                ]
            ],
            [
                'category_slug' => 'chest-drawer',
                'room_category' => 1,
                'name' => 'Drawer Chest Pine Wood',
                'slug' => 'pine-chest-6-drawers',
                'description' => 'Handcrafted pine chest of drawers with 6 spacious drawers featuring durable wooden runners. Natural pine grain visible through clear protective finish.',
                'short_description' => 'Traditional pine chest of 6 drawers with wooden runners and pine finish.',
                'price' => 12900.00,
                'sale_price' => null,
                'sku' => '2101CHS',
                'stock_quantity' => 12,
                'manage_stock' => true,
                'in_stock' => true,
                'weight' => '38 kg',
                'dimensions' => '90 x 45 x 100 cm',
                'material' => 'Solid Pine Wood',
                'images' => ['pine-chest-front.jpg', 'pine-chest-open.jpg'],
                'featured' => false,
                'is_active' => true,
                'meta_data' => [
                    'wood_type' => 'Pine',
                    'drawer_count' => 6,
                    'runner_type' => 'Wood Ball Bearing',
                    'finish' => 'Clear Pine Stain'
                ]
            ],
            [
                'category_slug' => 'dining-chair',
                'room_category' => 3,
                'name' => 'Oak Dining Chair Set of 4',
                'slug' => 'oak-dining-chair-set',
                'description' => 'Solid oak dining chairs featuring curved backrests and woven natural fiber seats. Set of 4 chairs with mortise & tenon joints and oak finish.',
                'short_description' => 'Set of 4 solid oak dining chairs with traditional craftsmanship.',
                'price' => 18500.00,
                'sale_price' => 16500.00,
                'sku' => '3204OCI',
                'stock_quantity' => 10,
                'manage_stock' => true,
                'in_stock' => true,
                'weight' => '18 kg (set)',
                'dimensions' => '45 x 42 x 95 cm each',
                'material' => 'Solid Oak + Natural Fiber',
                'images' => ['oak-dining-set-4.jpg', 'oak-dining-chair-single.jpg'],
                'featured' => true,
                'is_active' => true,
                'meta_data' => [
                    'wood_type' => 'Oak',
                    'chair_count' => 4,
                    'seat_material' => 'Natural Fiber',
                    'joint_type' => 'Mortise & Tenon'
                ]
            ],
            [
                'category_slug' => 'arm-chair',
                'room_category' => 2,
                'name' => 'Mahogany Armchair Lounge',
                'slug' => 'mahogany-armchair-lounge',
                'description' => 'Elegant mahogany armchair with high backrest and handwoven rattan seat. Premium hardwood construction with carved details and protective oil finish.',
                'short_description' => 'Premium mahogany armchair with carved details and rattan seating.',
                'price' => 22500.00,
                'sale_price' => null,
                'sku' => '3101MAR',
                'stock_quantity' => 5,
                'manage_stock' => true,
                'in_stock' => true,
                'weight' => '22 kg',
                'dimensions' => '65 x 70 x 110 cm',
                'material' => 'Solid Mahogany + Natural Rattan',
                'images' => ['mahogany-armchair-front.jpg', 'mahogany-armchair-angle.jpg'],
                'featured' => false,
                'is_active' => true,
                'meta_data' => [
                    'wood_type' => 'Mahogany',
                    'upholstery' => 'Natural Rattan',
                    'grade' => 'Premium Hardwood',
                    'finish' => 'Tung Oil'
                ]
            ],
            // TABLES (Main Category 4)
            [
                'category_slug' => 'dining-table',
                'room_category' => 3,
                'name' => '50" Oak Dining Table',
                'slug' => 'solid-oak-dining-table-50',
                'description' => 'Solid oak dining table featuring 2" thick slab top with live edge detail and tapered legs. Includes matching pair of oak benches included.',
                'short_description' => 'Solid oak slab dining table with matching benches, reveals natural wood grain and character.',
                'price' => 45000.00,
                'sale_price' => 39500.00,
                'sku' => '4303OAK',
                'stock_quantity' => 4,
                'manage_stock' => true,
                'in_stock' => true,
                'weight' => '95 kg',
                'dimensions' => '200 x 100 x 75 cm',
                'material' => '100% Solid Oak Slab',
                'images' => ['oak-table-top.jpg', 'oak-table-angle.jpg', 'oak-table-with-benches.jpg'],
                'featured' => true,
                'is_active' => true,
                'meta_data' => [
                    'wood_type' => 'Oak Slab',
                    'thickness' => '50mm',
                    'edge_detail' => 'Live Edge',
                    'set_includes' => ['Table', 'Bench x 2'],
                    'special_features' => 'Natural Bark Edge'
                ]
            ],
            [
                'category_slug' => 'coffee-table',
                'room_category' => 2,
                'name' => 'Walnut Coffee Table Mid-Century',
                'slug' => 'walnut-coffee-table-mid-century',
                'description' => 'Mid-century modern walnut coffee table with splayed legs and compartment storage. Features bookmatched walnut grain and Danish oil finish.',
                'short_description' => 'Bookshelf underside coffee table in premium walnut with storage.',
                'price' => 18500.00,
                'sale_price' => 16200.00,
                'sku' => '4404WAL',
                'stock_quantity' => 7,
                'manage_stock' => true,
                'in_stock' => true,
                'weight' => '28 kg',
                'dimensions' => '120 x 60 x 45 cm',
                'material' => 'Solid Walnut Wood',
                'images' => ['walnut-coffee-front.jpg', 'walnut-coffee-open.jpg'],
                'featured' => false,
                'is_active' => true,
                'meta_data' => [
                    'wood_type' => 'Walnut',
                    'design_style' => 'Mid-Century',
                    'storage_feature' => 'Bottom Shelf',
                    'finish' => 'Danish Oil'
                ]
            ],
            [
                'category_slug' => 'desk',
                'room_category' => 5,
                'name' => 'Pine Writing Desk',
                'slug' => 'pine-writing-desk-office',
                'description' => 'Spacious pine writing desk with 4 drawers and open shelf space, finished in light natural pine stain. Assembly required.',
                'short_description' => 'Complete home office desk in solid pine with drawers and storage.',
                'price' => 8500.00,
                'sale_price' => null,
                'sku' => '4601PWD',
                'stock_quantity' => 15,
                'manage_stock' => true,
                'in_stock' => true,
                'weight' => '35 kg',
                'dimensions' => '120 x 60 x 75 cm',
                'material' => 'Solid Pine Wood',
                'images' => ['pine-desk-front.jpg', 'pine-desk-angle.jpg'],
                'featured' => false,
                'is_active' => true,
                'meta_data' => [
                    'wood_type' => 'Pine',
                    'drawer_count' => 4,
                    'desk_depth' => '60cm',
                    'assembly' => 'Required'
                ]
            ],
            [
                'category_slug' => 'nightstand',
                'room_category' => 1,
                'name' => 'Oak Wood Nightstand Table',
                'slug' => 'oak-nightstand-drawer',
                'description' => 'Single-drawer oak nightstand table with concealed drawer runners. Organic lines with live-edge top detail.',
                'short_description' => 'Single-drawer solid oak nightstand with live wooden edge top.',
                'price' => 8500.00,
                'sale_price' => null,
                'sku' => '4501NST',
                'stock_quantity' => 20,
                'manage_stock' => true,
                'in_stock' => true,
                'weight' => '15 kg',
                'dimensions' => '40 x 35 x 55 cm',
                'material' => 'Solid Oak Wood',
                'images' => ['nightstand-oak-front.jpg', 'nightstand-oak-open.jpg'],
                'featured' => false,
                'is_active' => true,
                'meta_data' => [
                    'wood_type' => 'Oak',
                    'drawer_count' => 1,
                    'edge_style' => 'Live Wood Edge',
                    'runner' => 'Soft-close Pre-mounted'
                ]
            ],
            
            // SHELVES (Main Category 5)
            [
                'category_slug' => 'bookcase',
                'room_category' => 5,
                'name' => 'Oak Bookcase 5-Shelf',
                'slug' => 'solid-oak-bookcase-5-shelf',
                'description' => '5-shelf oak bookcase with solid construction and adjustable shelf heights. Perfect for home office or library storage.',
                'short_description' => '5-shelf oak bookcase with adjustable shelves and concealed joinery.',
                'price' => 15500.00,
                'sale_price' => null,
                'sku' => '5201OKB',
                'stock_quantity' => 9,
                'manage_stock' => true,
                'in_stock' => true,
                'weight' => '42 kg',
                'dimensions' => '80 x 30 x 180 cm',
                'material' => 'Solid Oak Wood',
                'images' => ['oak-bookcase-empty.jpg', 'oak-bookcase-filled.jpg'],
                'featured' => false,
                'is_active' => true,
                'meta_data' => [
                    'wood_type' => 'Oak',
                    'shelf_count' => 5,
                    'adjustable' => true,
                    'weight_capacity_per_shelf' => '15kg'
                ]
            ],
            [
                'category_slug' => 'wall-shelf',
                'room_category' => 2,
                'name' => 'Floating Pine Shelf 3-Tier',
                'slug' => 'floating-pine-shelf-3-tier',
                'description' => 'Set of 3 floating pine shelves with concealed bracket mounting system. Minimal wall mounting hardware included.',
                'short_description' => 'Set of 3 floating pine wall shelves with concealed mounting.',
                'price' => 3800.00,
                'sale_price' => null,
                'sku' => '5101FPS',
                'stock_quantity' => 25,
                'manage_stock' => true,
                'in_stock' => true,
                'weight' => '8 kg (set)',
                'dimensions' => '100 x 20 x 12 cm each',
                'material' => 'Solid Pine + Steel Brackets',
                'images' => ['pine-shelf-3-pieces.jpg', 'pine-shelf-mounted.jpg'],
                'featured' => false,
                'is_active' => true,
                'meta_data' => [
                    'wood_type' => 'Pine',
                    'shelf_count' => 3,
                    'mounting' => 'Concealed Bracket',
                    'wall_types' => ['Gypsum', 'Concrete']
                ]
            ],
            
            // SOFAS (Main Category 6)
            [
                'category_slug' => 'indoor-sofa',
                'room_category' => 2,
                'name' => 'Oak Wood Loveseat Sofa',
                'slug' => 'oak-wood-loveseat-sofa',
                'description' => 'Handcrafted oak frame loveseat featuring sprung seat construction and tapestried cushions with wooden arms.',
                'short_description' => 'Loveseat with oak frame and removable slip-covered cushions.',
                'price' => 28500.00,
                'sale_price' => 24900.00,
                'sku' => '6101LVO',
                'stock_quantity' => 6,
                'manage_stock' => true,
                'in_stock' => true,
                'weight' => '48 kg',
                'dimensions' => '150 x 70 x 85 cm',
                'material' => 'Oak Frame + Cotton Blend Cushions',
                'images' => ['oak-loveseat-front.jpg', 'oak-loveseat-angle.jpg'],
                'featured' => true,
                'is_active' => true,
                'meta_data' => [
                    'wood_type' => 'Oak',
                    'seating_capacity' => 2,
                    'upholstery' => 'Cotton Blend Slip-covers',
                    'seat_construction' => 'Spring System'
                ]
            ],
            [
                'category_slug' => 'outdoor-sofa',
                'room_category' => 6,
                'name' => 'Teak Outdoor Daybed Sofa',
                'slug' => 'teak-outdoor-daybed-sofa',
                'description' => 'Premium teak wood outdoor daybed with weather-resistant finish. Perfect for garden or balcony seating. Water-resistant cushions not included.',
                'short_description' => 'Teak wood frame daybed built for all-weather outdoor use.',
                'price' => 32500.00,
                'sale_price' => null,
                'sku' => '6202TEO',
                'stock_quantity' => 3,
                'manage_stock' => true,
                'in_stock' => true,
                'weight' => '62 kg',
                'dimensions' => '200 x 80 x 35 cm',
                'material' => 'Premium Teak Wood',
                'images' => ['teak-daybed-front.jpg', 'teak-daybed-angled.jpg'],
                'featured' => false,
                'is_active' => true,
                'meta_data' => [
                    'wood_type' => 'Teak',
                    'weather_resistance' => 'High',
                    'use' => 'Outdoor',
                    'coating' => 'Teak Oil Finish',
                    'brazing' => 'Bronze'
                ]
            ],
            
            // Additional varying products
            [
                'category_slug' => 'stool',
                'room_category' => 3,
                'name' => 'Oak Counter Stool 3-Piece',
                'slug' => 'oak-counter-stool-3-piece',
                'description' => 'Set of 3 solid oak counter stools with Pry bar seating area and leg stretcher connecting structure.',
                'short_description' => 'Three solid oak stools for kitchen island or bar seating.',
                'price' => 12950.00,
                'sale_price' => null,
                'sku' => '3303COK',
                'stock_quantity' => 15,
                'manage_stock' => true,
                'in_stock' => true,
                'weight' => '36 kg (set)',
                'dimensions' => '40 x 40 x 94 cm high each',
                'material' => 'Solid Oak Wood',
                'images' => ['oak-counter-3-stools.jpg', 'oak-stool-single-top-angle.jpg'],
                'featured' => false,
                'is_active' => true,
                'meta_data' => [
                    'wood_type' => 'Oak',
                    'stool_count' => 3,
                    'height' => '94cm Bar Stool Height',
                    'connection' => 'Leg Stretchers'
                ]
            ],
            [
                'category_slug' => 'outdoor-chair',
                'room_category' => 6,
                'name' => 'Hardwood Outdoor Rocker',
                'slug' => 'cedar-outdoor-rocker-chair',
                'description' => 'Single cedar outdoor rocker chair oiled for exterior weather resistance. Rocker leg support beneath',
                'short_description' => 'Cedar rocker perfect for porch or deck seating in any season.',
                'price' => 15500.00,
                'sale_price' => 13950.00,
                'sku' => '3504CDR',
                'stock_quantity' => 20,
                'manage_stock' => true,
                'in_stock' => true,
                'weight' => '25kg',
                'dimensions' => '68 × 85 × 94cm',
                'material' => 'Solid Cedar Wood',
                'images' => ['wooden-rocker.jpg', 'rocker-silhouette-side.jpg'],
                'featured' => false,
                'is_active' => true,
                'meta_data' => [
                    'wood_type' => 'Cedar',
                    'weather_treatment' => 'Oiled Exterior Grade',
                    'features' => ['Rocking Motion', 'Arm Rests']
                ]
            ],
        ];
    }
}
