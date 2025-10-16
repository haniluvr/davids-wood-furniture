<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\PhilippineDataHelper;

class CompleteUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentCount = User::count();
        $targetCount = 75;
        $needed = $targetCount - $currentCount;
        
        $this->command->info("Current users: {$currentCount}");
        $this->command->info("Target users: {$targetCount}");
        $this->command->info("Adding {$needed} more users...");
        
        for ($i = 0; $i < $needed; $i++) {
            // Get Filipino name
            $name = PhilippineDataHelper::getRandomFilipinoName();
            $firstName = $name['first_name'];
            $lastName = $name['last_name'];
            
            // Generate Filipino-based email
            $email = PhilippineDataHelper::generateFilipinoEmail($firstName, $lastName);
            
            // Get Philippine address (with fallback)
            try {
                $address = PhilippineDataHelper::getRandomPhilippineAddress();
            } catch (\Exception $e) {
                // Use fallback if API fails
                $address = [
                    'region' => fake()->randomElement(['National Capital Region (NCR)', 'Region III (Central Luzon)', 'Region IV-A (CALABARZON)']),
                    'province' => fake()->optional(0.7)->randomElement(['Bulacan', 'Laguna', 'Cavite', 'Rizal']),
                    'city' => fake()->randomElement(['Quezon City', 'Manila', 'Makati', 'Taguig', 'Pasig']),
                    'barangay' => fake()->randomElement(['Poblacion', 'Diliman', 'Ermita', 'Bel-Air']),
                    'zip_code' => fake()->numerify('####'),
                    'street' => fake()->streetName()
                ];
            }
            
            User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => Hash::make('password123'),
                'username' => fake()->unique()->userName(),
                'phone' => fake()->optional(0.8)->passthrough(PhilippineDataHelper::getRandomPhoneNumber()),
                'region' => $address['region'],
                'province' => $address['province'],
                'city' => $address['city'],
                'barangay' => $address['barangay'],
                'zip_code' => $address['zip_code'],
                'street' => $address['street'],
                'email_verified_at' => fake()->optional(0.9)->dateTimeBetween('-1 year', 'now'),
                'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
            
            $this->command->info("Created user " . ($i + 1) . "/{$needed}: {$firstName} {$lastName}");
        }
        
        $finalCount = User::count();
        $this->command->info("✅ Completed! Total users now: {$finalCount}");
    }
}
