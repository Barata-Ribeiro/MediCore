<?php

namespace App\Models\Exams;

use App\Models\MedicalFile;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Touches;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property float $hematocrit Hematocrit
 * @property float $hemoglobin Hemoglobin
 * @property float $red_blood_cell_count Red Blood Cell Count
 * @property float $mean_corpuscular_volume Mean Corpuscular Volume
 * @property float $mean_corpuscular_hemoglobin Mean Corpuscular Hemoglobin
 * @property float $mean_corpuscular_hemoglobin_concentration Mean Corpuscular Hemoglobin Concentration
 * @property float $red_blood_cell_distribution_width Red Blood Cell Distribution Width
 * @property float $leukocyte_count Leukocyte Count
 * @property float $rod_neutrophil_count Rod Neutrophil Count
 * @property float $segmented_neutrophil_count Segmented Neutrophil Count
 * @property float $lymphocyte_count Lymphocyte Count
 * @property float $monocyte_count Monocyte Count
 * @property float $eosinophil_count Eosinophil Count
 * @property float $basophil_count Basophil Count
 * @property float $metamyelocyte_count Metamyelocyte Count
 * @property float $promyelocyte_count Promyelocyte Count
 * @property float $atypical_cell_count Atypical Cell Count
 * @property float $platelet_count Platelet Count
 * @property CarbonImmutable $report_date Date of the complete blood count report
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $medical_file_id
 * @property-read MedicalFile $medicalFile
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereAtypicalCellCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereBasophilCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereEosinophilCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereHematocrit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereHemoglobin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereLeukocyteCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereLymphocyteCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereMeanCorpuscularHemoglobin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereMeanCorpuscularHemoglobinConcentration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereMeanCorpuscularVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereMedicalFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereMetamyelocyteCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereMonocyteCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount wherePlateletCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount wherePromyelocyteCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereRedBloodCellCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereRedBloodCellDistributionWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereRodNeutrophilCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereSegmentedNeutrophilCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompleteBloodCount whereUpdatedAt($value)
 *
 * @mixin \Eloquent
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
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'report_date' => 'date:Y-m-d',
    ];

    public function medicalFile(): BelongsTo
    {
        return $this->belongsTo(MedicalFile::class);
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
