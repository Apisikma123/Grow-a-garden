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

    public function getHstAttribute(): int
    {
        if (!$this->planted_date) return 0;
        return (int) now()->diffInDays($this->planted_date);
    }

    public function getStageNameAttribute(): string
    {
        $hst = $this->hst;
        $template = $this->plantTemplate;

        if (!$template) return 'SEED';

        if ($template->harvest_start_day && $hst >= $template->harvest_start_day) {
            return 'HARVEST';
        }
        if ($template->fruiting_day && $hst >= $template->fruiting_day) {
            return 'FRUITING';
        }
        if ($template->flowering_day && $hst >= $template->flowering_day) {
            return 'FLOWERING';
        }
        if ($template->vegetative_day && $hst >= $template->vegetative_day) {
            return 'VEGETATIVE';
        }
        if ($template->seedling_day && $hst >= $template->seedling_day) {
            return 'SEEDLING';
        }
        if ($template->germination_day && $hst >= $template->germination_day) {
            return 'GERMINATION';
        }

        return 'SEED';
    }

    public function getEstimatedHarvestDaysAttribute(): ?int
    {
        $template = $this->plantTemplate;
        if (!$template || !$template->harvest_start_day) return null;
        
        return max(0, $template->harvest_start_day - $this->hst);
    }
}
