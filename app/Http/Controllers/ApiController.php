<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function weather(): JsonResponse
    {
        try {
            // Using OpenWeatherMap API as example
            $apiKey = config('services.openweather.key');
            $city = request()->get('city', 'London'); // Default city

            $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric',
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return response()->json([
                    'success' => true,
                    'data' => [
                        'city' => $data['name'],
                        'country' => $data['sys']['country'],
                        'temperature' => round($data['main']['temp']),
                        'description' => ucfirst($data['weather'][0]['description']),
                        'humidity' => $data['main']['humidity'],
                        'wind_speed' => round($data['wind']['speed'] * 3.6, 2), // Convert m/s to km/h
                    ],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Weather data not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch weather data: '.$e->getMessage(),
            ], 500);
        }
    }
}
