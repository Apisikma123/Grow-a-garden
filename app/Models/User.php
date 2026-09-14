<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'google_id',
        'otp_code',
        'otp_expires_at',
        'avatar',
        'province',
        'gardening_experience',
        'gardening_scale',
        'gardening_goal',
        'onboarding_completed_at',
        'language',
        'email_notifications',
        'push_notifications',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'onboarding_completed_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Whether the user has finished their onboarding questionnaire.
     */
    public function hasCompletedOnboarding(): bool
    {
        return !is_null($this->onboarding_completed_at) || $this->gardens()->count() > 0;
    }

    public function gardens(): HasMany
    {
        return $this->hasMany(Garden::class);
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('awarded_at')
            ->withTimestamps();
    }

    /**
     * Whether this user can use the Autopilot feature (auto care task generation).
     */
    public function canUseAutopilot(): bool
    {
        // All users can use autopilot
        return true;
    }

    /**
     * Whether this user can use Weather Adjustment.
     */
    public function canUseWeatherAdjustment(): bool
    {
        // All users can use weather adjustment
        return true;
    }

    /**
     * Whether this user has unlimited activity log.
     */
    public function hasUnlimitedActivityLog(): bool
    {
        // All users get unlimited activity log
        return true;
    }
}
