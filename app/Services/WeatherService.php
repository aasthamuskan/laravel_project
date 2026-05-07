<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\WeatherCache;
use Carbon\Carbon;

/**
 * WeatherService
 *
 * Handles all weather-related business logic.
 * - Fetches real-time weather from OpenWeatherMap API
 * - Caches results for 10 minutes to avoid excessive API calls
 * - Persists responses in the weather_caches table for auditing
 * - Provides structured error handling with meaningful messages
 *
 * ARCHITECTURE NOTE:
 * This service is injected into controllers via dependency injection.
 * Controllers never call the API directly — all weather logic lives here.
 */
class WeatherService
{
    private const CACHE_TTL_SECONDS = 600;   // 10 minutes
    private const API_TIMEOUT_SECONDS = 5;
    private const WEATHER_API_URL = 'https://api.openweathermap.org/data/2.5/weather';
    private const FORECAST_API_URL = 'https://api.openweathermap.org/data/2.5/forecast';

    private string $apiKey;

    public function __construct()
    {
        // Read from .env — never hardcode credentials
        $this->apiKey = config('services.openweathermap.key', '');
    }

    /**
     * Fetch current weather for a city.
     * Returns structured array on success, or ['error' => '...'] on failure.
     *
     * Cache key format: weather_current_{city} (as required by spec)
     */
    public function getCurrentWeather(string $city): array
    {
        $city = trim($city);

        if (empty($city)) {
            return $this->errorResponse('City name cannot be empty.');
        }

        $cacheKey = 'weather_current_' . strtolower(str_replace(' ', '_', $city));

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($city) {
            return $this->fetchCurrentWeatherFromApi($city);
        });
    }

    /**
     * Internal method to hit the OpenWeatherMap API.
     * Separated so caching wraps it cleanly.
     */
    private function fetchCurrentWeatherFromApi(string $city): array
    {
        // Fail fast if no API key is configured
        if (empty($this->apiKey)) {
            Log::warning('WeatherService: OPENWEATHER_API_KEY is not set in .env');
            return $this->errorResponse('Weather service is not configured. Please set OPENWEATHER_API_KEY in .env.');
        }

        try {
            $response = Http::timeout(self::API_TIMEOUT_SECONDS)
                ->get(self::WEATHER_API_URL, [
                    'q'     => $city,
                    'appid' => $this->apiKey,
                    'units' => 'metric',
                ]);

            // Handle HTTP-level errors
            if ($response->status() === 404) {
                return $this->errorResponse("City \"{$city}\" was not found. Please check the spelling.");
            }

            if ($response->status() === 401) {
                Log::error('WeatherService: Invalid API key.');
                return $this->errorResponse('Weather API authentication failed. Please contact support.');
            }

            if ($response->failed()) {
                Log::error('WeatherService: API returned status ' . $response->status(), ['city' => $city]);
                return $this->errorResponse('Weather service is temporarily unavailable. Please try again later.');
            }

            $data = $response->json();

            // Persist to DB for auditing and offline fallback
            $this->persistToDatabase($city, $data);

            return $data;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('WeatherService: Connection timeout.', ['city' => $city, 'error' => $e->getMessage()]);
            // Try DB fallback before giving up
            return $this->getFallbackFromDatabase($city)
                ?? $this->errorResponse('Weather service is currently unreachable. Please try again shortly.');
        } catch (\Exception $e) {
            Log::error('WeatherService: Unexpected error.', ['city' => $city, 'error' => $e->getMessage()]);
            return $this->errorResponse('An unexpected error occurred while fetching weather data.');
        }
    }

    /**
     * Persist weather data to database for auditing & fallback.
     */
    private function persistToDatabase(string $city, array $data): void
    {
        try {
            WeatherCache::updateOrCreate(
                ['city' => strtolower($city)],
                [
                    'data_json'  => json_encode($data),
                    'created_at' => Carbon::now(),
                ]
            );
        } catch (\Exception $e) {
            // Non-critical — log but don't fail the request
            Log::warning('WeatherService: Could not persist to DB.', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Try to return last known data from DB if API is down.
     */
    private function getFallbackFromDatabase(string $city): ?array
    {
        $cached = WeatherCache::where('city', strtolower($city))->first();
        if ($cached) {
            $data = json_decode($cached->data_json, true);
            $data['_fallback'] = true;  // Flag so the view can show a notice
            Log::info('WeatherService: Using DB fallback for ' . $city);
            return $data;
        }
        return null;
    }

    /**
     * Normalize weather condition for advisory matching.
     * OpenWeatherMap returns: Clear, Clouds, Rain, Drizzle, Thunderstorm, Snow, Mist, etc.
     * We normalize to our system's categories: Clear, Cloudy, Rainy, Stormy, Cold
     */
    public function normalizeCondition(string $owmCondition): string
    {
        return match(true) {
            in_array($owmCondition, ['Thunderstorm'])              => 'Stormy',
            in_array($owmCondition, ['Rain', 'Drizzle'])          => 'Rainy',
            in_array($owmCondition, ['Snow'])                     => 'Cold',
            in_array($owmCondition, ['Clouds', 'Mist', 'Fog',
                                     'Haze', 'Smoke', 'Dust',
                                     'Sand', 'Ash', 'Squall',
                                     'Tornado'])                  => 'Cloudy',
            default                                               => 'Clear',
        };
    }

    /**
     * Extract useful display data from raw API response.
     * Returns a clean DTO-style array for the view.
     */
    public function parseWeatherData(array $rawData): array
    {
        return [
            'city'        => $rawData['name'] ?? 'Unknown',
            'country'     => $rawData['sys']['country'] ?? '',
            'temp'        => round($rawData['main']['temp'] ?? 0),
            'feels_like'  => round($rawData['main']['feels_like'] ?? 0),
            'humidity'    => $rawData['main']['humidity'] ?? 0,
            'wind_speed'  => round($rawData['wind']['speed'] ?? 0, 1),
            'condition'   => $rawData['weather'][0]['main'] ?? 'Unknown',
            'description' => ucfirst($rawData['weather'][0]['description'] ?? ''),
            'icon'        => $rawData['weather'][0]['icon'] ?? '01d',
            'is_fallback' => $rawData['_fallback'] ?? false,
        ];
    }

    /**
     * Helper to build a consistent error response.
     */
    private function errorResponse(string $message): array
    {
        return ['error' => true, 'message' => $message];
    }
}
