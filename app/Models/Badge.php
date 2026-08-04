<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Badge extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon_url',
    ];

    public function missions(): HasMany
    {
        return $this->hasMany(Mission::class, 'reward_badge_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_badges')
            ->withPivot('awarded_at')
            ->withTimestamps();
    }
}
