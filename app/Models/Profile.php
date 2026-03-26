<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Touches;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $bio
 * @property CarbonImmutable $birth_date
 * @property string $phone_number
 * @property string $address
 * @property string|null $sex
 * @property string|null $gender_identity
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $user_id
 * @property-read int|null $age
 * @property-read string $full_name
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereGenderIdentity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereUserId($value)
 *
 * @mixin \Eloquent
 */
#[Touches(['user'])]
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
