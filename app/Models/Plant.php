<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plant extends Model
{
    protected $fillable = [
        'garden_id',
        'plant_template_id',
        'planted_date',
        'transplant_date',
        'current_hst',
        'stage',
        'status',
        'multiple_harvest_override',
    ];

    protected $casts = [
        'planted_date' => 'date',
        'transplant_date' => 'date',
        'multiple_harvest_override' => 'boolean',
    ];

    public function garden(): BelongsTo
    {
        return $this->belongsTo(Garden::class);
    }

    public function plantTemplate(): BelongsTo
    {
        return $this->belongsTo(\App\Models\PlantTemplate::class, 'plant_template_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
