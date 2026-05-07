<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;
use App\Services\AdvisoryService;

/**
 * DashboardController
 *
 * THIN CONTROLLER — only orchestrates request → service → view.
 * No business logic lives here.
 */
class DashboardController extends Controller
{
    public function __construct(
        private readonly WeatherService  $weatherService,
        private readonly AdvisoryService $advisoryService,
    ) {}

    /**
     * GET /dashboard
     *
     * Accepts optional query params: city, crop_id, season
     * Shows weather data + matching advisory + recommendation alert.
     */
    public function index(Request $request)
    {
        // Validate query inputs (mild rules — it's a GET filter form)
        $request->validate([
            'city'    => 'nullable|string|max:100',
            'crop_id' => 'nullable|string',
            'season'  => 'nullable|string|in:Spring,Summer,Monsoon,Winter',
        ]);

        $crops        = $this->advisoryService->getAllCrops();
        $city         = $request->input('city');
        $cropId       = $request->input('crop_id');
        $season       = $request->input('season', $this->advisoryService->detectCurrentSeason());

        // Nothing to show until user submits the form
        if (!$city) {
            return view('dashboard', compact('crops', 'season'))->with([
                'weather'    => null,
                'advisory'   => null,
                'alert'      => null,
                'rainAlert'  => false,
                'city'       => null,
                'cropId'     => null,
            ]);
        }

        // --- WEATHER LAYER ---
        $rawWeather = $this->weatherService->getCurrentWeather($city);

        if (isset($rawWeather['error'])) {
            return view('dashboard', compact('crops', 'city', 'season'))->with([
                'weather'   => null,
                'advisory'  => null,
                'alert'     => null,
                'rainAlert' => false,
                'cropId'    => $cropId,
                'error'     => $rawWeather['message'],
            ]);
        }

        $weather   = $this->weatherService->parseWeatherData($rawWeather);
        $condition = $this->weatherService->normalizeCondition($weather['condition']);

        // --- ADVISORY LAYER ---
        $advisory  = $cropId
            ? $this->advisoryService->getAdvisory($cropId, $season, $condition)
            : null;

        $cropName  = $crops->find($cropId)?->name;
        $alert     = $this->advisoryService->getRecommendationAlert($condition, $cropName);
        $rainAlert = $this->advisoryService->isRainExpected($condition);

        return view('dashboard', compact(
            'crops', 'weather', 'advisory', 'alert',
            'rainAlert', 'city', 'cropId', 'season'
        ));
    }
}
