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
            $emailVerifiedAt = (rand(1, 100) <= 85) ? date('Y-m-d H:i:s', rand(strtotime('-2 years'), time())) : null;

            // Generate newsletter preferences (randomized)
            $newsletterSubscribed = (rand(1, 100) <= 60);
            $newsletterProductUpdates = (rand(1, 100) <= 70);
            $newsletterSpecialOffers = (rand(1, 100) <= 50);
            $marketingEmails = (rand(1, 100) <= 40);

            // Generate additional address details
            $streetNumber = rand(1, 999);
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
                'two_factor_enabled' => (rand(1, 100) <= 15), // 15% have 2FA enabled
                'two_factor_verified_at' => (rand(1, 100) <= 15) ? date('Y-m-d H:i:s', rand(strtotime('-1 year'), time())) : null,
            ]);
        }

        $this->command->info("Created {$userCount} Filipino users successfully!");
    }
}
