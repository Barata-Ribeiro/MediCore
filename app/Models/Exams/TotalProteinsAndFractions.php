<?php

namespace App\Models\Exams;

use App\Models\MedicalFile;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Touches;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property float $total_proteins Total Proteins level
 * @property float $albumin Albumin level
 * @property float $globulin Globulin level
 * @property CarbonImmutable $report_date Date of the Vitamin B12 report
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $medical_file_id
 * @property-read float|null $albumin_globulin_ratio
 * @property-read MedicalFile $medicalFile
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TotalProteinsAndFractions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TotalProteinsAndFractions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TotalProteinsAndFractions query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TotalProteinsAndFractions whereAlbumin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TotalProteinsAndFractions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TotalProteinsAndFractions whereGlobulin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TotalProteinsAndFractions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TotalProteinsAndFractions whereMedicalFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TotalProteinsAndFractions whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TotalProteinsAndFractions whereTotalProteins($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TotalProteinsAndFractions whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
#[Table('total_proteins_and_fractions')]
#[Touches(['medicalFile'])]
#[Fillable(['total_proteins', 'albumin', 'globulin', 'report_date', 'medical_file_id'])]
#[Appends(['albumin_globulin_ratio'])]
class TotalProteinsAndFractions extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = ['report_date' => 'date:Y-m-d'];

    /**
     * Get the albumin to globulin ratio.
     */
    public function getAlbuminGlobulinRatioAttribute(): ?float
    {
        if ($this->globulin === null || $this->albumin === null || $this->globulin <= 0 || $this->albumin <= 0) {
            return null;
        }

        return $this->albumin / $this->globulin;
    }

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
