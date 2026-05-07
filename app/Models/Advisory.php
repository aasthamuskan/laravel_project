<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Advisory extends Model
{
    protected $fillable = [
        'crop_id',
        'season',
        'weather_condition',
        'advice',
    ];

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
}
