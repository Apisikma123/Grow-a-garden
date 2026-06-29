<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateStage extends Model
{
    protected $fillable = [
        'template_id',
        'stage_order',
        'stage_name',
        'start_day',
        'end_day',
        'icon',
    ];

    /**
     * Get the template that owns the stage.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
}
