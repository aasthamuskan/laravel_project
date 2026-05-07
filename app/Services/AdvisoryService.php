<?php

namespace App\Services;

use App\Models\Advisory;
use App\Models\Crop;

/**
 * AdvisoryService
 *
 * Handles all advisory matching logic.
 * - Queries database for crop+season+weather_condition combinations
 * - Falls back gracefully when no exact match exists
 * - Generates human-readable alert/recommendation summaries
 *
 * ARCHITECTURE NOTE:
 * All advisory logic is isolated here. Controllers and views
 * never query the Advisory model directly — they go through this service.
 */
class AdvisoryService
{
    /**
     * Retrieve the best-matching advisory for a given crop, season, and weather condition.
     *
     * Matching strategy (priority order):
     * 1. Exact match: crop + season + weather_condition
     * 2. Partial match: crop + weather_condition (ignore season)
     * 3. Partial match: crop + season (ignore weather)
     * 4. null — no match found, caller should show generic advice
     */
    public function getAdvisory(string $cropId, string $season, string $normalizedCondition): ?Advisory
    {
        // 1. Exact match
        $advisory = Advisory::where('crop_id', $cropId)
            ->where('season', $season)
            ->where('weather_condition', $normalizedCondition)
            ->first();

        if ($advisory) return $advisory;

        // 2. Match crop + weather condition regardless of season
        $advisory = Advisory::where('crop_id', $cropId)
            ->where('weather_condition', $normalizedCondition)
            ->first();

        if ($advisory) return $advisory;

        // 3. Match crop + season regardless of weather condition
        $advisory = Advisory::where('crop_id', $cropId)
            ->where('season', $season)
            ->first();

        return $advisory; // may be null — that's OK
    }

    /**
     * Get all advisories for a given crop (used for filtering view).
     */
    public function getAdvisoriesForCrop(string $cropId): \Illuminate\Database\Eloquent\Collection
    {
        return Advisory::where('crop_id', $cropId)->with('crop')->get();
    }

    /**
     * Generate an actionable recommendation summary based on weather condition.
     * This drives the "alert" banner on the dashboard.
     *
     * Returns an array with:
     *  - type: 'danger' | 'warning' | 'success' | 'info'
     *  - message: string
     *  - icon: emoji for visual feedback
     */
    public function getRecommendationAlert(string $normalizedCondition, ?string $cropName = null): array
    {
        $crop = $cropName ? " for {$cropName}" : '';

        return match($normalizedCondition) {
            'Rainy' => [
                'type'    => 'danger',
                'icon'    => '🌧️',
                'message' => "Rain expected{$crop}. Delay irrigation and avoid pesticide spraying. Ensure field drainage is clear.",
            ],
            'Stormy' => [
                'type'    => 'danger',
                'icon'    => '⛈️',
                'message' => "Thunderstorm alert{$crop}. Halt all outdoor farming activities. Secure equipment immediately.",
            ],
            'Clear' => [
                'type'    => 'success',
                'icon'    => '☀️',
                'message' => "Clear skies{$crop}. Ideal conditions for spraying, harvesting, and outdoor farm work.",
            ],
            'Cloudy' => [
                'type'    => 'warning',
                'icon'    => '☁️',
                'message' => "Overcast conditions{$crop}. Monitor for moisture buildup. Watch for early signs of fungal disease.",
            ],
            'Cold' => [
                'type'    => 'info',
                'icon'    => '❄️',
                'message' => "Cold/Snow conditions{$crop}. Protect frost-sensitive crops. Delay sowing until temperatures rise.",
            ],
            default => [
                'type'    => 'info',
                'icon'    => '🌤️',
                'message' => "Weather conditions are normal{$crop}. Proceed with regular farming schedule.",
            ],
        };
    }

    /**
     * Detect if a rain alert should be shown (used for the bonus rain banner).
     */
    public function isRainExpected(string $normalizedCondition): bool
    {
        return in_array($normalizedCondition, ['Rainy', 'Stormy']);
    }

    /**
     * Get all crop options for form dropdowns.
     */
    public function getAllCrops(): \Illuminate\Database\Eloquent\Collection
    {
        return Crop::orderBy('name')->get();
    }

    /**
     * Resolve current season from month (Northern Hemisphere default).
     * Can be overridden by user input on the form.
     */
    public function detectCurrentSeason(): string
    {
        $month = (int) now()->format('n');
        return match(true) {
            in_array($month, [12, 1, 2])  => 'Winter',
            in_array($month, [3, 4, 5])   => 'Spring',
            in_array($month, [6, 7, 8])   => 'Summer',
            default                       => 'Monsoon', // 9, 10, 11
        };
    }
}
