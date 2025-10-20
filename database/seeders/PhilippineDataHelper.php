<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PhilippineDataHelper
{
    private static $apiBaseUrl = 'https://psgc.cloud/api/v2';

    private static $cachePrefix = 'psgc_';

    private static $cacheDuration = 60 * 60 * 24; // 24 hours

    public static function getFilipinoNames()
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
     * Fetch regions from PSGC API
     */
    public static function getRegions()
    {
        $cacheKey = self::$cachePrefix.'regions';

        return Cache::remember($cacheKey, self::$cacheDuration, function () {
            try {
                $response = Http::timeout(10)->get(self::$apiBaseUrl.'/regions');

                if ($response->successful()) {
                    return $response->json();
                }

                // Fallback to hardcoded regions if API fails
                return self::getFallbackRegions();
            } catch (\Exception $e) {
                return self::getFallbackRegions();
            }
        });
    }

    /**
     * Fetch provinces for a specific region
     */
    public static function getProvinces($regionName)
    {
        $cacheKey = self::$cachePrefix.'provinces_'.md5($regionName);

        return Cache::remember($cacheKey, self::$cacheDuration, function () use ($regionName) {
            try {
                $response = Http::timeout(10)->get(self::$apiBaseUrl.'/regions/'.urlencode($regionName).'/provinces');

                if ($response->successful()) {
                    return $response->json();
                }

                return [];
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    /**
     * Fetch cities/municipalities for a specific province
     */
    public static function getCitiesMunicipalities($regionName, $provinceName)
    {
        $cacheKey = self::$cachePrefix.'cities_'.md5($regionName.'_'.$provinceName);

        return Cache::remember($cacheKey, self::$cacheDuration, function () use ($regionName, $provinceName) {
            try {
                $response = Http::timeout(10)->get(self::$apiBaseUrl.'/regions/'.urlencode($regionName).'/provinces/'.urlencode($provinceName).'/cities-municipalities');

                if ($response->successful()) {
                    return $response->json();
                }

                return [];
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    /**
     * Fetch barangays for a specific city/municipality
     */
    public static function getBarangays($regionName, $provinceName, $cityName)
    {
        $cacheKey = self::$cachePrefix.'barangays_'.md5($regionName.'_'.$provinceName.'_'.$cityName);

        return Cache::remember($cacheKey, self::$cacheDuration, function () use ($regionName, $provinceName, $cityName) {
            try {
                $response = Http::timeout(10)->get(self::$apiBaseUrl.'/regions/'.urlencode($regionName).'/provinces/'.urlencode($provinceName).'/cities-municipalities/'.urlencode($cityName).'/barangays');

                if ($response->successful()) {
                    return $response->json();
                }

                return [];
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    /**
     * Generate a random Philippine address using PSGC API
     */
    public static function getRandomPhilippineAddress()
    {
        $regions = self::getRegions();

        if (empty($regions)) {
            return self::getFallbackAddress();
        }

        $region = fake()->randomElement($regions);
        $regionName = $region['name'];

        $provinces = self::getProvinces($regionName);

        if (empty($provinces)) {
            // NCR or region without provinces
            return self::getNCRAddress($regionName);
        }

        $province = fake()->randomElement($provinces);
        $provinceName = $province['name'];

        $cities = self::getCitiesMunicipalities($regionName, $provinceName);

        if (empty($cities)) {
            return self::getFallbackAddress();
        }

        $city = fake()->randomElement($cities);
        $cityName = $city['name'];

        $barangays = self::getBarangays($regionName, $provinceName, $cityName);

        if (empty($barangays)) {
            return self::getFallbackAddress();
        }

        $barangay = fake()->randomElement($barangays);

        return [
            'region' => $regionName,
            'province' => $provinceName,
            'city' => $cityName,
            'barangay' => $barangay['name'],
            'zip_code' => self::generateZipCode($regionName, $cityName),
            'street' => self::generateStreetName(),
        ];
    }

    /**
     * Generate Filipino-based email address
     */
    public static function generateFilipinoEmail($firstName, $lastName)
    {
        $domains = ['gmail.com', 'yahoo.com', 'yahoo.com.ph', 'hotmail.com', 'outlook.com'];

        // Clean names (remove accents, convert to lowercase)
        $cleanFirst = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $firstName));
        $cleanLast = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $lastName));

        // Remove special characters and spaces
        $cleanFirst = preg_replace('/[^a-z]/', '', $cleanFirst);
        $cleanLast = preg_replace('/[^a-z]/', '', $cleanLast);

        // Add some randomization
        $randomSuffix = fake()->optional(0.3)->numerify('###');
        $separator = fake()->randomElement(['.', '_', '']);

        $email = $cleanFirst.$separator.$cleanLast.$randomSuffix;
        $domain = fake()->randomElement($domains);

        return $email.'@'.$domain;
    }

    /**
     * Fallback regions if API fails
     */
    private static function getFallbackRegions()
    {
        return [
            ['name' => 'National Capital Region (NCR)'],
            ['name' => 'Region III (Central Luzon)'],
            ['name' => 'Region IV-A (CALABARZON)'],
            ['name' => 'Region V (Bicol Region)'],
            ['name' => 'Region VI (Western Visayas)'],
            ['name' => 'Region VII (Central Visayas)'],
            ['name' => 'Region VIII (Eastern Visayas)'],
            ['name' => 'Region IX (Zamboanga Peninsula)'],
            ['name' => 'Region X (Northern Mindanao)'],
            ['name' => 'Region XI (Davao Region)'],
            ['name' => 'Region XII (SOCCSKSARGEN)'],
        ];
    }

    /**
     * Generate NCR address (no provinces)
     */
    private static function getNCRAddress($regionName)
    {
        $ncrCities = [
            'Quezon City', 'Manila', 'Makati', 'Taguig', 'Pasig',
            'Mandaluyong', 'San Juan', 'Marikina', 'Las Piñas', 'Parañaque',
        ];

        $city = fake()->randomElement($ncrCities);

        return [
            'region' => $regionName,
            'province' => null,
            'city' => $city,
            'barangay' => fake()->randomElement(['Poblacion', 'Diliman', 'Ermita', 'Bel-Air', 'Fort Bonifacio', 'Ortigas Center']),
            'zip_code' => self::generateZipCode($regionName, $city),
            'street' => self::generateStreetName(),
        ];
    }

    /**
     * Generate fallback address
     */
    private static function getFallbackAddress()
    {
        $regions = self::getFallbackRegions();
        $region = fake()->randomElement($regions);

        return [
            'region' => $region['name'],
            'province' => fake()->randomElement(['Bulacan', 'Pampanga', 'Laguna', 'Cavite', 'Batangas']),
            'city' => fake()->randomElement(['Malolos', 'San Fernando', 'Calamba', 'Dasmariñas', 'Batangas City']),
            'barangay' => fake()->randomElement(['Poblacion', 'San Agustin', 'Poblacion 1', 'Salitran']),
            'zip_code' => fake()->numerify('####'),
            'street' => self::generateStreetName(),
        ];
    }

    /**
     * Generate realistic street name
     */
    private static function generateStreetName()
    {
        $streetTypes = ['Street', 'Avenue', 'Road', 'Boulevard', 'Drive', 'Lane'];
        $streetNames = [
            'Rizal', 'Commonwealth', 'Ayala', 'Ortigas', 'Shaw', 'Marcos',
            'Alabang-Zapote', 'Elizalde', 'Capitol', 'Jose Abad Santos',
            'Governor\'s', 'J. Hernandez', 'J.M. Basa', 'Gorordo', 'Carlos P. Garcia',
            'Real', 'Climaco', 'Velez', 'Roxas', 'Pioneer', 'Sinsuat',
        ];

        return fake()->randomElement($streetNames).' '.fake()->randomElement($streetTypes);
    }

    /**
     * Generate realistic zip code based on region and city
     */
    private static function generateZipCode($regionName, $cityName)
    {
        // Common zip code patterns for major cities
        $zipPatterns = [
            'Quezon City' => '11##',
            'Manila' => '10##',
            'Makati' => '12##',
            'Taguig' => '16##',
            'Pasig' => '16##',
            'Mandaluyong' => '15##',
            'San Juan' => '15##',
            'Marikina' => '18##',
            'Las Piñas' => '17##',
            'Parañaque' => '17##',
        ];

        if (isset($zipPatterns[$cityName])) {
            return fake()->numerify($zipPatterns[$cityName]);
        }

        // Default pattern
        return fake()->numerify('####');
    }

    public static function getRandomFilipinoName()
    {
        $names = self::getFilipinoNames();

        return [
            'first_name' => fake()->randomElement($names['first_names']),
            'last_name' => fake()->randomElement($names['last_names']),
        ];
    }

    public static function getRandomPhoneNumber()
    {
        // Philippine mobile number format: +63 9XX XXX XXXX
        $prefixes = ['917', '918', '919', '920', '921', '922', '923', '924', '925', '926', '927', '928', '929', '930', '931', '932', '933', '934', '935', '936', '937', '938', '939', '940', '941', '942', '943', '944', '945', '946', '947', '948', '949', '950', '951', '952', '953', '954', '955', '956', '957', '958', '959', '960', '961', '962', '963', '964', '965', '966', '967', '968', '969', '970', '971', '972', '973', '974', '975', '976', '977', '978', '979', '980', '981', '982', '983', '984', '985', '986', '987', '988', '989', '990', '991', '992', '993', '994', '995', '996', '997', '998', '999'];

        $prefix = fake()->randomElement($prefixes);
        $number = fake()->numerify('### ####');

        return '+63 '.$prefix.' '.$number;
    }
}
