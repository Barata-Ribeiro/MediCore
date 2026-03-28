<?php

namespace App\Models;

use App\Enums\BloodType;
use App\Models\Exams\LipidProfile;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
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
 * @property-read float|null $bmi
 * @property-read Collection<int, LipidProfile> $lipidProfile
 * @property-read int|null $lipid_profile_count
 * @property-read User $user
 *
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
 * @method static Builder<static>|MedicalFile withBloodType(\App\Enums\BloodType $bloodType)
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

    public function scopeWithBloodType(Builder $query, BloodType $bloodType)
    {
        return $query->where('blood_type', $bloodType->value);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Exams

    public function lipidProfile(): HasMany
    {
        return $this->hasMany(LipidProfile::class);
    }
}
