<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

/**
 * WeatherCache model
 *
 * Stores raw OpenWeatherMap API responses per city.
 * Used for:
 *  - Offline fallback when API is unreachable
 *  - Audit trail of fetched data
 *
 * Collection: weather_caches (MongoDB)
 */
class WeatherCache extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'weather_caches';

    protected $fillable = [
        'city',
        'data_json',
    ];

    // data_json is stored as raw JSON string (we decode manually in WeatherService)
}
