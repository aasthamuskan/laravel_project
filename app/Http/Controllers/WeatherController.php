<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;
use App\Services\AdvisoryService;

/**
 * WeatherController
 *
 * Dedicated controller for the weather API endpoint.
 * GET /weather?city={city}
 *
 * Returns JSON so it can also be consumed by frontend JS if needed.
 */
class WeatherController extends Controller
{
    public function __construct(
        private readonly WeatherService  $weatherService,
        private readonly AdvisoryService $advisoryService,
    ) {}

    /**
     * GET /weather?city=London
     *
     * Returns structured weather JSON for AJAX or direct use.
     */
    public function show(Request $request)
    {
        $request->validate([
            'city' => 'required|string|max:100',
        ]);

        $city       = $request->input('city');
        $rawWeather = $this->weatherService->getCurrentWeather($city);

        if (isset($rawWeather['error'])) {
            return response()->json([
                'success' => false,
                'message' => $rawWeather['message'],
            ], 422);
        }

        $weather   = $this->weatherService->parseWeatherData($rawWeather);
        $condition = $this->weatherService->normalizeCondition($weather['condition']);
        $alert     = $this->advisoryService->getRecommendationAlert($condition);

        return response()->json([
            'success'   => true,
            'weather'   => $weather,
            'condition' => $condition,
            'alert'     => $alert,
        ]);
    }
}
