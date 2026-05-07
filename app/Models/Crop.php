<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Crop extends Model
{
    protected $fillable = ['name'];

    public function advisories()
    {
        return $this->hasMany(Advisory::class);
    }
}
