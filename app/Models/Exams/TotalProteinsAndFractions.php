<?php

namespace App\Models\Exams;

use App\Models\MedicalFile;
use Carbon\CarbonImmutable;
use Database\Factories\Exams\TotalProteinsAndFractionsFactory;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Touches;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperTotalProteinsAndFractions
 */
#[Table('total_proteins_and_fractions')]
#[Touches(['medicalFile'])]
#[Fillable(['total_proteins', 'albumin', 'globulin', 'report_date', 'medical_file_id'])]
#[Appends(['albumin_globulin_ratio'])]
class TotalProteinsAndFractions extends Model
{
    /** @use HasFactory<TotalProteinsAndFractionsFactory> */
    use HasFactory;

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
        if ($this->globulin <= 0 || $this->albumin <= 0) {
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
