<?php

namespace App\Models;

use App\Models\Fitness\Exercise;
use App\Models\Fitness\MuscleGroup;
use App\Models\Fitness\Workout;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin IdeHelperUser
 */
#[Fillable(['name', 'email', 'locale', 'password', 'provider_id', 'provider_name', 'registration_domain', 'provider_token', 'provider_refresh_token', 'avatar'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token', 'provider_token', 'provider_refresh_token'])]
class User extends Authenticatable implements PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    /**
     * Scope a query to only include super admin users.
     *
     * @param  Builder<self>  $query
     */
    #[Scope]
    protected function isSuperAdmin(Builder $query): void
    {
        $query->whereHas('roles', fn ($q): Builder => $q->where('name', 'super-admin'));
    }

    /**
     * @return HasOne<Profile, $this>
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * @return HasOne<MedicalFile, $this>
     */
    public function medicalFile(): HasOne
    {
        return $this->hasOne(MedicalFile::class);
    }

    /**
     * Get the workouts for the user.
     *
     * @return HasMany<Workout, $this>
     */
    public function workouts(): HasMany
    {
        return $this->hasMany(Workout::class);
    }

    /**
     * Get the exercises for the user.
     *
     * @return HasMany<Exercise, $this>
     */
    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class);
    }

    /**
     * Get the muscle groups for the user.
     *
     * @return HasMany<MuscleGroup, $this>
     */
    public function muscleGroups(): HasMany
    {
        return $this->hasMany(MuscleGroup::class);
    }
}
