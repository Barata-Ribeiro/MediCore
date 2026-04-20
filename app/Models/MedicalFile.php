<?php

namespace App\Models;

use App\Enums\BloodType;
use App\Models\Exams\CompleteBloodCount;
use App\Models\Exams\Glucose;
use App\Models\Exams\LipidProfile;
use App\Models\Exams\UltrasensitiveTsh;
use App\Models\Exams\UreaAndCreatinine;
use App\Models\Exams\UricAcid;
use App\Models\Exams\VitaminB12;
use App\Models\Exams\VitaminD3;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Touches;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property BloodType|null $blood_type
 * @property string|null $allergies
 * @property string|null $diseases
 * @property string|null $medications
 * @property float|null $weight
 * @property float|null $height
 * @property string|null $emergency_contact_name
 * @property string|null $emergency_contact_phone_number
 * @property string|null $emergency_contact_relationship
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $user_id
 * @property-read Collection<int, CompleteBloodCount> $completeBloodCounts
 * @property-read int|null $complete_blood_counts_count
 * @property-read bool|null $complete_blood_counts_exists
 * @property-read float|null $bmi
 * @property-read Collection<int, Glucose> $glucoses
 * @property-read int|null $glucoses_count
 * @property-read bool|null $glucoses_exists
 * @property-read Collection<int, LipidProfile> $lipidProfiles
 * @property-read int|null $lipid_profiles_count
 * @property-read bool|null $lipid_profiles_exists
 * @property-read Collection<int, UltrasensitiveTsh> $ultrasensitiveTshs
 * @property-read int|null $ultrasensitive_tshs_count
 * @property-read bool|null $ultrasensitive_tshs_exists
 * @property-read Collection<int, UreaAndCreatinine> $ureaAndCreatinines
 * @property-read int|null $urea_and_creatinines_count
 * @property-read bool|null $urea_and_creatinines_exists
 * @property-read Collection<int, UricAcid> $uricAcids
 * @property-read int|null $uric_acids_count
 * @property-read bool|null $uric_acids_exists
 * @property-read User $user
 * @property-read Collection<int, VitaminB12> $vitaminB12s
 * @property-read int|null $vitamin_b12s_count
 * @property-read bool|null $vitamin_b12s_exists
 * @property-read Collection<int, VitaminD3> $vitaminD3s
 * @property-read int|null $vitamin_d3s_count
 * @property-read bool|null $vitamin_d3s_exists
 *
 * @method static Builder<static>|MedicalFile bloodType(\App\Enums\BloodType $bloodType)
 * @method static Builder<static>|MedicalFile newModelQuery()
 * @method static Builder<static>|MedicalFile newQuery()
 * @method static Builder<static>|MedicalFile query()
 * @method static Builder<static>|MedicalFile whereAllergies($value)
 * @method static Builder<static>|MedicalFile whereBloodType($value)
 * @method static Builder<static>|MedicalFile whereCreatedAt($value)
 * @method static Builder<static>|MedicalFile whereDiseases($value)
 * @method static Builder<static>|MedicalFile whereEmergencyContactName($value)
 * @method static Builder<static>|MedicalFile whereEmergencyContactPhoneNumber($value)
 * @method static Builder<static>|MedicalFile whereEmergencyContactRelationship($value)
 * @method static Builder<static>|MedicalFile whereHeight($value)
 * @method static Builder<static>|MedicalFile whereId($value)
 * @method static Builder<static>|MedicalFile whereMedications($value)
 * @method static Builder<static>|MedicalFile whereUpdatedAt($value)
 * @method static Builder<static>|MedicalFile whereUserId($value)
 * @method static Builder<static>|MedicalFile whereWeight($value)
 *
 * @mixin \Eloquent
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
     */
    #[Scope]
    protected function bloodType(Builder $query, BloodType $bloodType): void
    {
        $query->where('blood_type', $bloodType->value);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Exams

    public function completeBloodCounts(): HasMany
    {
        return $this->hasMany(CompleteBloodCount::class);
    }

    public function glucoses(): HasMany
    {
        return $this->hasMany(Glucose::class);
    }

    public function lipidProfiles(): HasMany
    {
        return $this->hasMany(LipidProfile::class);
    }

    public function ultrasensitiveTshs(): HasMany
    {
        return $this->hasMany(UltrasensitiveTsh::class);
    }

    public function ureaAndCreatinines(): HasMany
    {
        return $this->hasMany(UreaAndCreatinine::class);
    }

    public function uricAcids(): HasMany
    {
        return $this->hasMany(UricAcid::class);
    }

    public function vitaminB12s(): HasMany
    {
        return $this->hasMany(VitaminB12::class);
    }

    public function vitaminD3s(): HasMany
    {
        return $this->hasMany(VitaminD3::class);
    }
}
