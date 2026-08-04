<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlantTemplate extends Model
{
    protected $fillable = [
        'category_id',
        'name_id',
        'scientific_name',
        'family',
        'germination_day',
        'seedling_day',
        'vegetative_day',
        'flowering_day',
        'fruiting_day',
        'harvest_start_day',
        'harvest_end_day',
        'multiple_harvest',
        'soil_ph_min',
        'soil_ph_max',
        'max_temperature',
        'water_requirement',
        'sunlight',
        'recommended_months',
        'source_refs',
    ];

    protected $casts = [
        'multiple_harvest' => 'boolean',
        'recommended_months' => 'array',
        'source_refs' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PlantCategory::class, 'category_id');
    }

    public function plants(): HasMany
    {
        return $this->hasMany(Plant::class, 'plant_template_id');
    }
}
