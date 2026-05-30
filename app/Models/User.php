<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'avatar',
        'is_super_admin',
        'is_active',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_super_admin'    => 'boolean',
            'is_active'         => 'boolean',
            'password'          => 'hashed',
        ];
    }

    public function institutions(): BelongsToMany
    {
        return $this->belongsToMany(Institution::class, 'user_institutions')
            ->withPivot(['role', 'is_primary', 'is_active', 'joined_at'])
            ->withTimestamps();
    }

    public function userInstitutions(): HasMany
    {
        return $this->hasMany(UserInstitution::class);
    }

    public function activeInstitutions(): BelongsToMany
    {
        return $this->institutions()->wherePivot('is_active', true);
    }

    public function primaryInstitution(): ?Institution
    {
        return $this->institutions()->wherePivot('is_primary', true)->first();
    }

    public function canAccessInstitution(int $institutionId): bool
    {
        if ($this->is_super_admin) {
            return true;
        }

        return $this->activeInstitutions()
            ->where('institutions.id', $institutionId)
            ->exists();
    }
}
