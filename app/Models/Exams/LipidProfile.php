<?php

namespace App\Models\Exams;

use App\Models\MedicalFile;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Touches;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read MedicalFile|null $medicalFile
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile query()
 *
 * @mixin \Eloquent
 */
#[Touches(['medicalFile'])]
#[Fillable(['total_cholesterol', 'hdl_cholesterol', 'ldl_cholesterol', 'vldl_cholesterol', 'triglycerides', 'report_date', 'medical_file_id'])]
class LipidProfile extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'report_date' => 'date',
    ];

    public function medicalFile(): BelongsTo
    {
        return $this->belongsTo(MedicalFile::class);
    }
}
