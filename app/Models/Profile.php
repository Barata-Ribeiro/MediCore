<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Touches;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Touches('user')]
#[Fillable(['first_name', 'last_name', 'bio', 'birth_date', 'phone_number', 'address', 'sex', 'gender_identity'])]
#[Appends(['full_name', 'age'])]
class Profile extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->attributes['first_name']} {$this->attributes['last_name']}";
    }

    public function getAgeAttribute(): ?int
    {
        return Carbon::parse($this->birth_date)->age;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
