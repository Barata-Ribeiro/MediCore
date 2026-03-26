<?php

namespace App\Models;

use App\Enums\BloodType;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Touches;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Touches('user')]
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
}
