<?php

namespace App\Http\Requests\Exams;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\Attributes\FailOnUnknownFields;
use Illuminate\Foundation\Http\FormRequest;

#[FailOnUnknownFields]
class CompleteBloodCountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'report_date' => ['required', 'date'],
            'hematocrit' => ['required', 'numeric', 'min:0'],
            'hemoglobin' => ['required', 'numeric', 'min:0'],
            'red_blood_cell_count' => ['required', 'numeric', 'min:0'],
            'mean_corpuscular_volume' => ['required', 'numeric', 'min:0'],
            'mean_corpuscular_hemoglobin' => ['required', 'numeric', 'min:0'],
            'mean_corpuscular_hemoglobin_concentration' => ['required', 'numeric', 'min:0'],
            'red_blood_cell_distribution_width' => ['required', 'numeric', 'min:0'],
            'leukocyte_count' => ['required', 'numeric', 'min:0'],
            'rod_neutrophil_count' => ['required', 'numeric', 'min:0'],
            'segmented_neutrophil_count' => ['required', 'numeric', 'min:0'],
            'lymphocyte_count' => ['required', 'numeric', 'min:0'],
            'monocyte_count' => ['required', 'numeric', 'min:0'],
            'eosinophil_count' => ['required', 'numeric', 'min:0'],
            'basophil_count' => ['required', 'numeric', 'min:0'],
            'metamyelocyte_count' => ['required', 'numeric', 'min:0'],
            'promyelocyte_count' => ['required', 'numeric', 'min:0'],
            'atypical_cell_count' => ['required', 'numeric', 'min:0'],
            'platelet_count' => ['required', 'numeric', 'min:0'],
            'medical_file_id' => ['required', 'numeric', 'min:0'],
        ];
    }
}
