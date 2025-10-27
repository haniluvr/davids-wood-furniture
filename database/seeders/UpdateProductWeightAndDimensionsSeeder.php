<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class UpdateProductWeightAndDimensionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Read IKEA data
        $jsonPath = base_path('ikea_data.json');
        if (! File::exists($jsonPath)) {
            $this->command->error('IKEA data file not found!');

            return;
        }

        $ikeaData = json_decode(File::get($jsonPath), true);
        $this->command->info('Loaded '.count($ikeaData).' IKEA products');

        // Create a mapping of product names to IKEA data
        $ikeaMapping = $this->createIkeaMapping($ikeaData);

        // Get all products
        $products = Product::all();
        $updated = 0;

        foreach ($products as $product) {
            $weightAndDimensions = $this->getWeightAndDimensions($product, $ikeaMapping);

            if ($weightAndDimensions) {
                $product->weight = $weightAndDimensions['weight'];
                $product->dimensions = $weightAndDimensions['dimensions'];
                $product->save();
                $updated++;

                $this->command->info("Updated: {$product->name} - {$weightAndDimensions['dimensions']} - {$weightAndDimensions['weight']}kg");
            }
        }

        $this->command->info("Successfully updated {$updated} products");
    }

    /**
     * Create a mapping of IKEA products by name.
     */
    private function createIkeaMapping($ikeaData)
    {
        $mapping = [];

        foreach ($ikeaData as $item) {
            $name = $item['Notranslate'] ?? '';
            $description = $item['Description'] ?? $item['Plp-image Description'] ?? '';

            if ($name && $description) {
                if (! isset($mapping[$name])) {
                    $mapping[$name] = [];
                }
                $mapping[$name][] = [
                    'description' => $description,
                    'dimensions' => $this->extractDimensions($description),
                ];
            }
        }

        return $mapping;
    }

    /**
     * Extract dimensions from IKEA description.
     */
    private function extractDimensions($description)
    {
        // Pattern to match dimensions like "140x60 cm" or "40x28x202 cm"
        if (preg_match('/(\d+x\d+(?:x\d+)?)\s*cm/', $description, $matches)) {
            return $matches[1].' cm';
        }

        // Pattern for single dimension like "55 cm"
        if (preg_match('/(\d+)\s*cm/', $description, $matches)) {
            return $matches[1].' cm';
        }
    }

    /**
     * Get weight and dimensions for a product.
     */
    private function getWeightAndDimensions($product, $ikeaMapping)
    {
        // Try to find in IKEA data first
        $productName = strtoupper($product->name);

        // Extract potential IKEA name from product name
        $ikeaName = $this->extractIkeaName($productName);

        if ($ikeaName && isset($ikeaMapping[$ikeaName])) {
            $ikeaProducts = $ikeaMapping[$ikeaName];

            // Find best match based on product description or type
            foreach ($ikeaProducts as $ikeaProduct) {
                if ($ikeaProduct['dimensions']) {
                    $weight = $this->calculateWeight($product, $ikeaProduct['dimensions']);

                    return [
                        'dimensions' => $ikeaProduct['dimensions'],
                        'weight' => $weight,
                    ];
                }
            }
        }

        // Fallback: Generate reasonable dimensions and weight based on product type
        return $this->generateFallbackData($product);
    }

    /**
     * Extract IKEA product name from full product name.
     */
    private function extractIkeaName($productName)
    {
        // Common IKEA product names
        $ikeaNames = [
            'LAGKAPTEN', 'SANDSBERG', 'GURSKEN', 'LACK', 'BURHULT', 'LINNMON',
            'BEKVÄM', 'HOL', 'BILLY', 'MOSSLANDA', 'LUSTIGT', 'TROTTEN',
            'KALLAX', 'HEMNES', 'PAX', 'MALM', 'EKTORP', 'KIVIK', 'POÄNG',
            'LISABO', 'NORDEN', 'EKEDALEN', 'JÄRVFJÄLLET', 'MARKUS',
            'STEFAN', 'INGOLF', 'BERNHARD', 'NORDVIKEN', 'HAVSTA',
            'BRIMNES', 'SONGESAND', 'IDANÄS', 'HAUGA', 'FLOTTEBO',
        ];

        foreach ($ikeaNames as $name) {
            if (stripos($productName, $name) !== false) {
                return $name;
            }
        }
    }

    /**
     * Calculate weight based on product type and dimensions.
     */
    private function calculateWeight($product, $dimensions)
    {
        // Parse dimensions
        $dims = explode('x', str_replace(' cm', '', $dimensions));

        $categoryName = strtolower($product->category->name ?? '');
        $productName = strtolower($product->name);

        // Calculate volume (simplified)
        $volume = 1;
        foreach ($dims as $dim) {
            $volume *= floatval($dim);
        }

        // Base density factors by product type (kg per 1000 cm³)
        if (stripos($productName, 'table') !== false || stripos($categoryName, 'table') !== false) {
            return round($volume / 3000, 1); // Tables: moderate weight
        } elseif (stripos($productName, 'chair') !== false || stripos($categoryName, 'chair') !== false) {
            return round($volume / 4000, 1); // Chairs: lighter
        } elseif (stripos($productName, 'shelf') !== false || stripos($productName, 'bookcase') !== false) {
            return round($volume / 5000, 1); // Shelves: lighter
        } elseif (stripos($productName, 'cabinet') !== false || stripos($productName, 'wardrobe') !== false) {
            return round($volume / 2000, 1); // Cabinets: heavier
        } elseif (stripos($productName, 'bed') !== false || stripos($categoryName, 'bed') !== false) {
            return round($volume / 1500, 1); // Beds: heavy
        } elseif (stripos($productName, 'sofa') !== false || stripos($productName, 'couch') !== false) {
            return round($volume / 1800, 1); // Sofas: heavy
        } elseif (stripos($productName, 'desk') !== false || stripos($categoryName, 'desk') !== false) {
            return round($volume / 3500, 1); // Desks: moderate
        } elseif (stripos($productName, 'drawer') !== false || stripos($productName, 'chest') !== false) {
            return round($volume / 2500, 1); // Drawers: moderate-heavy
        } else {
            return round($volume / 4000, 1); // Default: moderate weight
        }
    }

    /**
     * Generate fallback dimensions and weight based on product category.
     */
    private function generateFallbackData($product)
    {
        $categoryName = strtolower($product->category->name ?? '');
        $productName = strtolower($product->name);

        // Tables
        if (stripos($productName, 'table') !== false || stripos($categoryName, 'table') !== false) {
            if (stripos($productName, 'coffee') !== false) {
                return ['dimensions' => '100x60x45 cm', 'weight' => 12.5];
            } elseif (stripos($productName, 'dining') !== false) {
                return ['dimensions' => '150x90x75 cm', 'weight' => 25.0];
            } elseif (stripos($productName, 'side') !== false || stripos($productName, 'end') !== false) {
                return ['dimensions' => '50x50x55 cm', 'weight' => 6.5];
            } else {
                return ['dimensions' => '120x70x75 cm', 'weight' => 18.0];
            }
        }

        // Chairs
        if (stripos($productName, 'chair') !== false || stripos($categoryName, 'chair') !== false) {
            if (stripos($productName, 'office') !== false || stripos($productName, 'desk') !== false) {
                return ['dimensions' => '60x60x110 cm', 'weight' => 12.0];
            } elseif (stripos($productName, 'dining') !== false) {
                return ['dimensions' => '45x45x95 cm', 'weight' => 8.5];
            } elseif (stripos($productName, 'arm') !== false) {
                return ['dimensions' => '65x70x100 cm', 'weight' => 14.0];
            } else {
                return ['dimensions' => '50x50x85 cm', 'weight' => 7.5];
            }
        }

        // Beds
        if (stripos($productName, 'bed') !== false || stripos($categoryName, 'bed') !== false) {
            if (stripos($productName, 'king') !== false) {
                return ['dimensions' => '200x200x45 cm', 'weight' => 65.0];
            } elseif (stripos($productName, 'queen') !== false) {
                return ['dimensions' => '160x200x45 cm', 'weight' => 55.0];
            } elseif (stripos($productName, 'single') !== false || stripos($productName, 'twin') !== false) {
                return ['dimensions' => '90x200x45 cm', 'weight' => 35.0];
            } else {
                return ['dimensions' => '140x200x45 cm', 'weight' => 45.0];
            }
        }

        // Sofas
        if (stripos($productName, 'sofa') !== false || stripos($productName, 'couch') !== false) {
            if (stripos($productName, '3-seat') !== false || stripos($productName, 'three') !== false) {
                return ['dimensions' => '215x88x85 cm', 'weight' => 75.0];
            } elseif (stripos($productName, '2-seat') !== false || stripos($productName, 'two') !== false || stripos($productName, 'loveseat') !== false) {
                return ['dimensions' => '165x88x85 cm', 'weight' => 55.0];
            } else {
                return ['dimensions' => '190x88x85 cm', 'weight' => 65.0];
            }
        }

        // Shelves & Bookcases
        if (stripos($productName, 'shelf') !== false || stripos($productName, 'bookcase') !== false || stripos($productName, 'shelving') !== false) {
            if (stripos($productName, 'wall') !== false) {
                return ['dimensions' => '80x26x3 cm', 'weight' => 3.5];
            } else {
                return ['dimensions' => '80x40x180 cm', 'weight' => 28.0];
            }
        }

        // Cabinets & Wardrobes
        if (stripos($productName, 'cabinet') !== false || stripos($productName, 'wardrobe') !== false) {
            if (stripos($productName, 'wardrobe') !== false) {
                return ['dimensions' => '100x60x200 cm', 'weight' => 85.0];
            } else {
                return ['dimensions' => '80x45x120 cm', 'weight' => 45.0];
            }
        }

        // Drawers & Chests
        if (stripos($productName, 'drawer') !== false || stripos($productName, 'chest') !== false) {
            if (stripos($productName, '3-drawer') !== false || preg_match('/3\s*drawer/i', $productName)) {
                return ['dimensions' => '60x40x70 cm', 'weight' => 25.0];
            } elseif (stripos($productName, '4-drawer') !== false || preg_match('/4\s*drawer/i', $productName)) {
                return ['dimensions' => '60x40x95 cm', 'weight' => 32.0];
            } elseif (stripos($productName, '6-drawer') !== false || preg_match('/6\s*drawer/i', $productName)) {
                return ['dimensions' => '80x48x123 cm', 'weight' => 45.0];
            } else {
                return ['dimensions' => '60x40x80 cm', 'weight' => 28.0];
            }
        }

        // Desks
        if (stripos($productName, 'desk') !== false || stripos($categoryName, 'desk') !== false) {
            if (stripos($productName, 'corner') !== false) {
                return ['dimensions' => '140x140x75 cm', 'weight' => 32.0];
            } else {
                return ['dimensions' => '120x60x75 cm', 'weight' => 18.5];
            }
        }

        // Storage
        if (stripos($productName, 'storage') !== false || stripos($categoryName, 'storage') !== false) {
            return ['dimensions' => '70x50x90 cm', 'weight' => 22.0];
        }

        // Benches
        if (stripos($productName, 'bench') !== false) {
            return ['dimensions' => '110x38x48 cm', 'weight' => 12.0];
        }

        // Stools
        if (stripos($productName, 'stool') !== false) {
            return ['dimensions' => '35x35x45 cm', 'weight' => 4.5];
        }

        // Mirrors
        if (stripos($productName, 'mirror') !== false) {
            return ['dimensions' => '60x80x3 cm', 'weight' => 8.5];
        }

        // Lamps
        if (stripos($productName, 'lamp') !== false || stripos($categoryName, 'lighting') !== false) {
            if (stripos($productName, 'floor') !== false) {
                return ['dimensions' => '28x28x175 cm', 'weight' => 4.5];
            } elseif (stripos($productName, 'table') !== false) {
                return ['dimensions' => '25x25x45 cm', 'weight' => 2.5];
            } else {
                return ['dimensions' => '30x30x15 cm', 'weight' => 1.8];
            }
        }

        // Rugs
        if (stripos($productName, 'rug') !== false || stripos($productName, 'carpet') !== false) {
            return ['dimensions' => '160x230x1 cm', 'weight' => 5.5];
        }

        // Curtains
        if (stripos($productName, 'curtain') !== false) {
            return ['dimensions' => '140x250x1 cm', 'weight' => 1.2];
        }

        // Default for unrecognized items
        return ['dimensions' => '50x40x30 cm', 'weight' => 5.0];
    }
}
