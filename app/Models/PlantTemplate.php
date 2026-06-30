<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantTemplate extends Model
{
    protected $guarded = [];

    protected $casts = [
        'multiple_harvest' => 'boolean',
        'recommended_months' => 'array',
        'source_refs' => 'array',
        'care_rules' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(PlantCategory::class);
    }
}
