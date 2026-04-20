<?php

namespace App\Models\Exams;

use App\Models\MedicalFile;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Touches;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('uric_acids')]
#[Touches(['medicalFile'])]
#[Fillable(['uric_acid_level', 'report_date', 'medical_file_id'])]
class UricAcid extends Model
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
