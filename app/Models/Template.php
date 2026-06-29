<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    protected $fillable = [
        'name',
        'category',
        'duration_min',
        'duration_max',
        'image',
    ];

    /**
     * Get the stages for the template, ordered by stage_order.
     */
    public function stages(): HasMany
    {
        return $this->hasMany(TemplateStage::class)->orderBy('stage_order');
    }
}
