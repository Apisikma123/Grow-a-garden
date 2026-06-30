<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GardenPlot extends Model
{
    protected $fillable = [
        'garden_id',
        'plant_id',
        'name',
        'shape',
        'width',
        'length',
        'pos_x',
        'pos_y',
        'custom_points_json',
    ];

    protected $casts = [
        'custom_points_json' => 'array',
    ];

    public function garden(): BelongsTo
    {
        return $this->belongsTo(Garden::class);
    }

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }
}
