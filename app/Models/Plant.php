<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function gardenPlot(): HasOne
    {
        return $this->hasOne(GardenPlot::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
