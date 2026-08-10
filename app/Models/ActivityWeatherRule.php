<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityWeatherRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_type',
        'weather_field',
        'operator',
        'threshold',
        'action',
        'message',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'threshold' => 'float',
    ];
}
