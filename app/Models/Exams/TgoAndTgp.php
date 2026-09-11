<?php

namespace App\Models\Exams;

use App\Models\MedicalFile;
use Carbon\CarbonImmutable;
use Database\Factories\Exams\TgoAndTgpFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Touches;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property float $tgo_level Aspartate aminotransferase (TGO/AST) in U/L
 * @property float $tgp_level Alanine aminotransferase (TGP/ALT) in U/L
 * @property CarbonImmutable $report_date Date of the TGO and TGP report
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $medical_file_id
 * @property-read MedicalFile $medicalFile
 *
 * @method static \Database\Factories\Exams\TgoAndTgpFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TgoAndTgp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TgoAndTgp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TgoAndTgp query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TgoAndTgp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TgoAndTgp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TgoAndTgp whereMedicalFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TgoAndTgp whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TgoAndTgp whereTgoLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TgoAndTgp whereTgpLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TgoAndTgp whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
#[Table('tgo_and_tgps')]
#[Touches(['medicalFile'])]
#[Fillable(['tgo_level', 'tgp_level', 'report_date', 'medical_file_id'])]
class TgoAndTgp extends Model
{
    /** @use HasFactory<TgoAndTgpFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'report_date' => 'date:Y-m-d',
    ];

    /**
     * @return BelongsTo<MedicalFile, $this>
     */
    public function medicalFile(): BelongsTo
    {
        return $this->belongsTo(MedicalFile::class, 'medical_file_id', 'id');
    }

    /**
     * Ensure report_date is saved as a date-only string (Y-m-d).
     */
    public function setReportDateAttribute(CarbonImmutable|string|\DateTimeInterface|null $value): void
    {
        if ($value === null) {
            $this->attributes['report_date'] = null;

            return;
        }

        $this->attributes['report_date'] = CarbonImmutable::parse($value)->toDateString();
    }
}
