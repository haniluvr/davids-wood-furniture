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
            $name = $this->getRandomFilipinoName();
            $firstName = $name['first_name'];
            $lastName = $name['last_name'];

            // Generate username: first letter of first name + full last name
            $username = strtolower(substr($firstName, 0, 1).$lastName);

            // Generate email
            $email = $this->generateFilipinoEmail($firstName, $lastName);

            // Generate phone number
            $phone = $this->getRandomPhoneNumber();

            // Get random Philippine address
            $address = $this->getRandomPhilippineAddress();

            // Generate random created_at date (within last 2 years)
            $createdAt = date('Y-m-d H:i:s', rand(strtotime('-2 years'), time()));
            $updatedAt = date('Y-m-d H:i:s', rand(strtotime($createdAt), time()));

            // Generate email verification (85% verified)
            // Email verification should be after account creation but not in the future
            $emailVerifiedAt = null;
            if (rand(1, 100) <= 85) {
                $emailVerifiedTimestamp = rand(strtotime($createdAt), min(time(), strtotime($createdAt) + (7 * 24 * 60 * 60))); // Within 7 days of creation
                $emailVerifiedAt = date('Y-m-d H:i:s', $emailVerifiedTimestamp);
            }

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
                'two_factor_verified_at' => (rand(1, 100) <= 15) ? date('Y-m-d H:i:s', rand(strtotime($createdAt), time())) : null,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);
        }

        $this->command->info("Created {$userCount} Filipino users successfully!");
    }

    /**
     * Get random Filipino names.
     */
    private function getFilipinoNames()
    {
        return [
            'first_names' => [
                'Maria', 'Jose', 'Antonio', 'Francisco', 'Manuel', 'Pedro', 'Juan', 'Carlos', 'Ramon', 'Ricardo',
                'Ana', 'Carmen', 'Rosa', 'Isabel', 'Teresa', 'Dolores', 'Pilar', 'Concepcion', 'Mercedes', 'Josefa',
                'Miguel', 'Rafael', 'Fernando', 'Alberto', 'Eduardo', 'Sergio', 'Roberto', 'Luis', 'Jorge', 'Andres',
                'Elena', 'Beatriz', 'Cristina', 'Victoria', 'Amparo', 'Esperanza', 'Soledad', 'Rosario', 'Purificacion', 'Encarnacion',
                'Daniel', 'Alejandro', 'Guillermo', 'Emilio', 'Alfonso', 'Federico', 'Arturo', 'Adolfo', 'Raul', 'Enrique',
                'Patricia', 'Monica', 'Alicia', 'Sandra', 'Marta', 'Inmaculada', 'Andrea', 'Cristina', 'Marta', 'Paula',
            ],
            'last_names' => [
                'Santos', 'Reyes', 'Cruz', 'Bautista', 'Ocampo', 'Garcia', 'Lopez', 'Mendoza', 'Ramos', 'Gonzales',
                'Torres', 'Flores', 'Rivera', 'Gomez', 'Diaz', 'Perez', 'Sanchez', 'Ramirez', 'Jimenez', 'Herrera',
                'Moreno', 'Munoz', 'Alvarez', 'Romero', 'Alonso', 'Gutierrez', 'Navarro', 'Torres', 'Dominguez', 'Vazquez',
                'Ramos', 'Gil', 'Serrano', 'Blanco', 'Suarez', 'Munoz', 'Alonso', 'Gutierrez', 'Navarro', 'Dominguez',
                'Castillo', 'Ortega', 'Delgado', 'Castro', 'Ortiz', 'Rubio', 'Marin', 'Sanz', 'Iglesias', 'Medina',
                'Cortes', 'Garrido', 'Castillo', 'Ortega', 'Delgado', 'Castro', 'Ortiz', 'Rubio', 'Marin', 'Sanz',
            ],
        ];
    }

    /**
     * Get random Filipino name.
     */
    private function getRandomFilipinoName()
    {
        $names = $this->getFilipinoNames();

        return [
            'first_name' => $names['first_names'][array_rand($names['first_names'])],
            'last_name' => $names['last_names'][array_rand($names['last_names'])],
        ];
    }

    /**
     * Generate Filipino-based email address.
     */
    private function generateFilipinoEmail($firstName, $lastName)
    {
        $domains = ['gmail.com', 'yahoo.com', 'yahoo.com.ph', 'hotmail.com', 'outlook.com'];

        // Clean names (remove accents, convert to lowercase)
        $cleanFirst = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $firstName));
        $cleanLast = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $lastName));

        // Remove special characters and spaces
        $cleanFirst = preg_replace('/[^a-z]/', '', $cleanFirst);
        $cleanLast = preg_replace('/[^a-z]/', '', $cleanLast);

        // Add some randomization
        $randomSuffix = (rand(1, 100) <= 30) ? str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT) : '';
        $separator = ['.', '_', ''][array_rand(['.', '_', ''])];
        $domain = $domains[array_rand($domains)];

        $email = $cleanFirst.$separator.$cleanLast.$randomSuffix;

        return $email.'@'.$domain;
    }

    /**
     * Get random Philippine phone number.
     */
    private function getRandomPhoneNumber()
    {
        // Philippine mobile number format: +63 9XX XXX XXXX
        $prefixes = ['917', '918', '919', '920', '921', '922', '923', '924', '925', '926', '927', '928', '929', '930', '931', '932', '933', '934', '935', '936', '937', '938', '939', '940', '941', '942', '943', '944', '945', '946', '947', '948', '949', '950', '951', '952', '953', '954', '955', '956', '957', '958', '959', '960', '961', '962', '963', '964', '965', '966', '967', '968', '969', '970', '971', '972', '973', '974', '975', '976', '977', '978', '979', '980', '981', '982', '983', '984', '985', '986', '987', '988', '989', '990', '991', '992', '993', '994', '995', '996', '997', '998', '999'];

        $prefix = $prefixes[array_rand($prefixes)];
        $number = str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT).' '.str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);

        return '+63 '.$prefix.' '.$number;
    }

    /**
     * Get random Philippine address.
     */
    private function getRandomPhilippineAddress()
    {
        $regions = [
            'National Capital Region (NCR)',
            'Region III (Central Luzon)',
            'Region IV-A (CALABARZON)',
            'Region V (Bicol Region)',
            'Region VI (Western Visayas)',
            'Region VII (Central Visayas)',
            'Region VIII (Eastern Visayas)',
            'Region IX (Zamboanga Peninsula)',
            'Region X (Northern Mindanao)',
            'Region XI (Davao Region)',
            'Region XII (SOCCSKSARGEN)',
        ];

        $provinces = [
            'Bulacan', 'Pampanga', 'Laguna', 'Cavite', 'Batangas', 'Rizal', 'Quezon', 'Nueva Ecija', 'Tarlac', 'Zambales',
            'Bataan', 'Aurora', 'Cebu', 'Bohol', 'Negros Oriental', 'Siquijor', 'Iloilo', 'Capiz', 'Aklan', 'Antique',
            'Leyte', 'Samar', 'Eastern Samar', 'Northern Samar', 'Biliran', 'Southern Leyte', 'Davao del Sur', 'Davao del Norte',
            'Davao Oriental', 'Davao de Oro', 'Davao Occidental', 'South Cotabato', 'North Cotabato', 'Sultan Kudarat', 'Sarangani',
        ];

        $cities = [
            'Quezon City', 'Manila', 'Makati', 'Taguig', 'Pasig', 'Mandaluyong', 'San Juan', 'Marikina', 'Las Piñas', 'Parañaque',
            'Malolos', 'San Fernando', 'Calamba', 'Dasmariñas', 'Batangas City', 'Antipolo', 'Lucena', 'Cabanatuan', 'Tarlac City', 'Olongapo',
            'Cebu City', 'Mandaue', 'Lapu-Lapu', 'Talisay', 'Toledo', 'Dumaguete', 'Tagbilaran', 'Iloilo City', 'Roxas City', 'Kalibo',
            'Tacloban', 'Catbalogan', 'Borongan', 'Calbayog', 'Davao City', 'Tagum', 'Digos', 'Mati', 'Panabo', 'Samal',
            'Koronadal', 'Tacurong', 'Kidapawan', 'Cotabato City', 'General Santos', 'Isulan', 'Alabel', 'Malungon',
        ];

        $barangays = [
            'Poblacion', 'Diliman', 'Ermita', 'Bel-Air', 'Fort Bonifacio', 'Ortigas Center', 'San Agustin', 'Poblacion 1', 'Salitran',
            'Centro', 'Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4', 'Barangay 5', 'San Isidro', 'San Antonio', 'San Jose',
            'San Miguel', 'San Pedro', 'San Rafael', 'San Roque', 'San Vicente', 'Santa Ana', 'Santa Cruz', 'Santa Maria', 'Santo Niño',
            'Santo Rosario', 'Sitio', 'Villa', 'Subdivision', 'Housing', 'Residential', 'Commercial', 'Industrial',
        ];

        $region = $regions[array_rand($regions)];
        $province = $provinces[array_rand($provinces)];
        $city = $cities[array_rand($cities)];
        $barangay = $barangays[array_rand($barangays)];

        return [
            'region' => $region,
            'province' => $province,
            'city' => $city,
            'barangay' => $barangay,
            'zip_code' => str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
            'street' => $this->generateStreetName(),
        ];
    }

    /**
     * Generate realistic street name.
     */
    private function generateStreetName()
    {
        $streetTypes = ['Street', 'Avenue', 'Road', 'Boulevard', 'Drive', 'Lane'];
        $streetNames = [
            'Rizal', 'Commonwealth', 'Ayala', 'Ortigas', 'Shaw', 'Marcos',
            'Alabang-Zapote', 'Elizalde', 'Capitol', 'Jose Abad Santos',
            'Governor\'s', 'J. Hernandez', 'J.M. Basa', 'Gorordo', 'Carlos P. Garcia',
            'Real', 'Climaco', 'Velez', 'Roxas', 'Pioneer', 'Sinsuat',
        ];

        return $streetNames[array_rand($streetNames)].' '.$streetTypes[array_rand($streetTypes)];
    }
}
