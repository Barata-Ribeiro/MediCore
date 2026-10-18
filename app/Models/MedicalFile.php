<?php

namespace App\Models;

use App\Enums\BloodType;
use App\Models\Exams\CompleteBloodCount;
use App\Models\Exams\Glucose;
use App\Models\Exams\LipidProfile;
use App\Models\Exams\TgoAndTgp;
use App\Models\Exams\TotalProteinsAndFractions;
use App\Models\Exams\UltrasensitiveTsh;
use App\Models\Exams\UreaAndCreatinine;
use App\Models\Exams\UricAcid;
use App\Models\Exams\VitaminB12;
use App\Models\Exams\VitaminD3;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Touches;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperMedicalFile
 */
#[Touches(['user'])]
#[Fillable(['blood_type', 'allergies', 'diseases', 'medications', 'weight', 'height', 'emergency_contact_name', 'emergency_contact_phone_number', 'emergency_contact_relationship'])]
#[Appends(['bmi'])]
class MedicalFile extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'blood_type' => BloodType::class,
        ];
    }

    public function getBmiAttribute(): ?float
    {
        if ($this->weight == null || $this->height == null) {
            return null;
        }
        if ($this->weight == 0 || $this->height == 0) {
            return null;
        }

        $heightMeters = $this->height / 100.0;
        if ($heightMeters == 0) {
            return null;
        }

        return $this->weight / ($heightMeters * $heightMeters);
    }

    /**
     * Scope a query to only include users with a specific blood type.
     *
     * @param  Builder<self>  $query
     */
    #[Scope]
    protected function bloodType(Builder $query, BloodType $bloodType): void
    {
        $query->where('blood_type', $bloodType->value);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Exams

    /**
     * @return HasMany<CompleteBloodCount, $this>
     */
    public function completeBloodCounts(): HasMany
    {
        return $this->hasMany(CompleteBloodCount::class);
    }

    /**
     * @return HasMany<Glucose, $this>
     */
    public function glucoses(): HasMany
    {
        return $this->hasMany(Glucose::class);
    }

    /**
     * @return HasMany<LipidProfile, $this>
     */
    public function lipidProfiles(): HasMany
    {
        return $this->hasMany(LipidProfile::class);
    }

    /**
     * @return HasMany<TotalProteinsAndFractions, $this>
     */
    public function totalProteinsAndFractions(): HasMany
    {
        return $this->hasMany(TotalProteinsAndFractions::class);
    }

    /**
     * @return HasMany<UltrasensitiveTsh, $this>
     */
    public function ultrasensitiveTshs(): HasMany
    {
        return $this->hasMany(UltrasensitiveTsh::class);
    }

    /**
     * @return HasMany<TgoAndTgp, $this>
     */
    public function tgoAndTgps(): HasMany
    {
        return $this->hasMany(TgoAndTgp::class);
    }

    /**
     * @return HasMany<UreaAndCreatinine, $this>
     */
    public function ureaAndCreatinines(): HasMany
    {
        return $this->hasMany(UreaAndCreatinine::class);
    }

    /**
     * @return HasMany<UricAcid, $this>
     */
    public function uricAcids(): HasMany
    {
        return $this->hasMany(UricAcid::class);
    }

    /**
     * @return HasMany<VitaminB12, $this>
     */
    public function vitaminB12s(): HasMany
    {
        return $this->hasMany(VitaminB12::class);
    }

    /**
     * @return HasMany<VitaminD3, $this>
     */
    public function vitaminD3s(): HasMany
    {
        return $this->hasMany(VitaminD3::class);
    }
}
