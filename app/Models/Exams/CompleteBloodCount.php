<?php

namespace App\Models\Exams;

use App\Models\MedicalFile;
use Carbon\CarbonImmutable;
use Database\Factories\Exams\CompleteBloodCountFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Touches;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperCompleteBloodCount
 */
#[Table('complete_blood_counts')]
#[Touches(['medicalFile'])]
#[Fillable([
    'hematocrit',
    'hemoglobin',
    'red_blood_cell_count',
    'mean_corpuscular_volume',
    'mean_corpuscular_hemoglobin',
    'mean_corpuscular_hemoglobin_concentration',
    'red_blood_cell_distribution_width',
    'leukocyte_count',
    'rod_neutrophil_count',
    'segmented_neutrophil_count',
    'lymphocyte_count',
    'monocyte_count',
    'eosinophil_count',
    'basophil_count',
    'metamyelocyte_count',
    'promyelocyte_count',
    'atypical_cell_count',
    'platelet_count',
    'report_date',
    'medical_file_id',
])]
class CompleteBloodCount extends Model
{
    /** @use HasFactory<CompleteBloodCountFactory> */
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
