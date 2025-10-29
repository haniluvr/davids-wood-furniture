<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FilipinoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userCount = 150;

        for ($i = 0; $i < $userCount; $i++) {
            // Get random Filipino name
            $name = PhilippineDataHelper::getRandomFilipinoName();
            $firstName = $name['first_name'];
            $lastName = $name['last_name'];

            // Generate username: first letter of first name + full last name
            $username = strtolower(substr($firstName, 0, 1).$lastName);

            // Generate email
            $email = PhilippineDataHelper::generateFilipinoEmail($firstName, $lastName);

            // Generate phone number
            $phone = PhilippineDataHelper::getRandomPhoneNumber();

            // Get random Philippine address
            $address = PhilippineDataHelper::getRandomPhilippineAddress();

            // Generate email verification (85% verified)
            $emailVerifiedAt = fake()->boolean(85) ? fake()->dateTimeBetween('-2 years', 'now') : null;

            // Generate newsletter preferences (randomized)
            $newsletterSubscribed = fake()->boolean(60);
            $newsletterProductUpdates = fake()->boolean(70);
            $newsletterSpecialOffers = fake()->boolean(50);
            $marketingEmails = fake()->boolean(40);

            // Generate additional address details
            $streetNumber = fake()->numberBetween(1, 999);
            $streetName = $address['street'];
            $fullStreet = $streetNumber.' '.$streetName;

            User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'username' => $username,
                'email' => $email,
                'password' => Hash::make('password123'),
                'phone' => $phone,
                'region' => $address['region'],
                'street' => $streetName,
                'barangay' => $address['barangay'],
                'city' => $address['city'],
                'province' => $address['province'],
                'zip_code' => $address['zip_code'],
                'newsletter_subscribed' => $newsletterSubscribed,
                'newsletter_product_updates' => $newsletterProductUpdates,
                'newsletter_special_offers' => $newsletterSpecialOffers,
                'marketing_emails' => $marketingEmails,
                'email_verified_at' => $emailVerifiedAt,
                'is_suspended' => false,
                'two_factor_enabled' => fake()->boolean(15), // 15% have 2FA enabled
                'two_factor_verified_at' => fake()->boolean(15) ? fake()->dateTimeBetween('-1 year', 'now') : null,
            ]);
        }

        $this->command->info("Created {$userCount} Filipino users successfully!");
    }
}
