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

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
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

    // ── Plan Helper Methods ──

    /**
     * Get the user's active subscription.
     */
    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('valid_until', '>', now())
            ->latest()
            ->first();
    }

    /**
     * Get human-readable plan name.
     */
    public function planName(): string
    {
        return match ($this->role) {
            'pro' => 'Subur (Pro)',
            'premium' => 'Panen Raya (Premium)',
            'admin' => 'Admin Console',
            default => 'Bibit (Gratis)',
        };
    }

    /**
     * Maximum gardens allowed for this user's plan.
     */
    public function maxGardens(): int
    {
        return match ($this->role) {
            'pro' => 10,
            'premium', 'admin' => 100,
            default => 1,
        };
    }

    /**
     * Maximum plants allowed for this user's plan.
     * Returns PHP_INT_MAX for unlimited.
     */
    public function maxPlants(): int
    {
        return match ($this->role) {
            'pro' => 100,
            'premium', 'admin' => PHP_INT_MAX,
            default => 10,
        };
    }

    /**
     * Whether this user can use the Autopilot feature (auto care task generation).
     */
    public function canUseAutopilot(): bool
    {
        return in_array($this->role, ['pro', 'premium', 'admin']);
    }

    /**
     * Whether this user can use Weather Adjustment.
     */
    public function canUseWeatherAdjustment(): bool
    {
        return in_array($this->role, ['pro', 'premium', 'admin']);
    }

    /**
     * Whether this user has unlimited activity log.
     */
    public function hasUnlimitedActivityLog(): bool
    {
        return in_array($this->role, ['premium', 'admin']);
    }
}
