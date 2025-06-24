<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use App\Traits\HasTrial;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, HasSlug, HasTrial;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'password',
        'role',
        'email_verified_at',
        'last_login',
        'online_at',
        'banned_at',
        'ban_reason',
        'apple_id',
        'google_id',
        'has_had_trial',
        'trial_ends_at',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login' => 'datetime',
            'online_at' => 'datetime',
            'banned_at' => 'datetime',
            'password' => 'hashed',
            'trial_ends_at' => 'datetime',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function devices()
    {
        return $this->hasMany(UserDevice::class);
    }

    public function activePlan()
    {
        return $this->hasOne(Purchase::class)->where('status', 'active')->where('end_date', '>', now())->latest();
    }

    public function isBanned(): bool
    {
        return !is_null($this->banned_at);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function hasAnyRole(string ...$roles): bool
    {
        return in_array($this->role, $roles);
    }

    public function isOnline(): bool
    {
        return !is_null($this->online_at) && $this->online_at->diffInMinutes(now()) < 5;
    }

    public function onTrial(): bool
    {
        return $this->trial_ends_at && now()->lt($this->trial_ends_at);
    }
}
