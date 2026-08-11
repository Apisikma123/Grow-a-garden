<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Garden extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'location_name',
        'latitude',
        'longitude',
        'area_size_m2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plants(): HasMany
    {
        return $this->hasMany(Plant::class);
    }
}
