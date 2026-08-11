<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeatherRule extends Model
{
    protected $fillable = [
        'name',
        'weather_field',
        'operator',
        'threshold',
        'severity',
        'message',
        'is_active',
    ];
}
