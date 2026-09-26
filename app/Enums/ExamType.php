<?php

namespace App\Enums;

use App\Models\Exams\CompleteBloodCount;
use App\Models\Exams\Glucose;
use App\Models\Exams\LipidProfile;
use App\Models\Exams\TgoAndTgp;
use App\Models\Exams\TotalProteinsAndFractions;
use App\Models\Exams\UltrasensitiveTsh;
use App\Models\Exams\UreaAndCreatinine;
use App\Models\Exams\UricAcid;
use App\Models\Exams\VitaminB12;
use App\Models\Exams\VitaminD3;
use Illuminate\Database\Eloquent\Model;

enum ExamType: string
{
    case COMPLETE_BLOOD_COUNT = 'complete-blood-count';
    case GLUCOSE = 'glucose';
    case LIPID_PROFILE = 'lipid-profile';
    case TOTAL_PROTEINS_AND_FRACTIONS = 'total-proteins-and-fractions';
    case ULTRASENSITIVE_TSH = 'ultrasensitive-tsh';
    case TGO_AND_TGP = 'tgo-and-tgp';
    case UREA_AND_CREATININE = 'urea-and-creatinine';
    case URIC_ACID = 'uric-acid';
    case VITAMIN_B12 = 'vitamin-b12';
    case VITAMIN_D3 = 'vitamin-d3';

    /** @return class-string<Model> */
    public function model(): string
    {
        return match ($this) {
            self::COMPLETE_BLOOD_COUNT => CompleteBloodCount::class,
            self::GLUCOSE => Glucose::class,
            self::LIPID_PROFILE => LipidProfile::class,
            self::TOTAL_PROTEINS_AND_FRACTIONS => TotalProteinsAndFractions::class,
            self::ULTRASENSITIVE_TSH => UltrasensitiveTsh::class,
            self::TGO_AND_TGP => TgoAndTgp::class,
            self::UREA_AND_CREATININE => UreaAndCreatinine::class,
            self::URIC_ACID => UricAcid::class,
            self::VITAMIN_B12 => VitaminB12::class,
            self::VITAMIN_D3 => VitaminD3::class,
        };
    }

    /** @return array<string, list<string>> */
    public function rules(): array
    {
        $measurements = match ($this) {
            self::COMPLETE_BLOOD_COUNT => ['hematocrit', 'hemoglobin', 'red_blood_cell_count', 'mean_corpuscular_volume', 'mean_corpuscular_hemoglobin', 'mean_corpuscular_hemoglobin_concentration', 'red_blood_cell_distribution_width', 'leukocyte_count', 'rod_neutrophil_count', 'segmented_neutrophil_count', 'lymphocyte_count', 'monocyte_count', 'eosinophil_count', 'basophil_count', 'metamyelocyte_count', 'promyelocyte_count', 'atypical_cell_count', 'platelet_count'],
            self::GLUCOSE => ['glucose_level', 'glycated_hemoglobin', 'estimated_average_glucose'],
            self::LIPID_PROFILE => ['total_cholesterol', 'hdl_cholesterol', 'ldl_cholesterol', 'vldl_cholesterol', 'triglycerides'],
            self::TOTAL_PROTEINS_AND_FRACTIONS => ['total_proteins', 'albumin', 'globulin'],
            self::ULTRASENSITIVE_TSH => ['tsh_level'],
            self::TGO_AND_TGP => ['tgo_level', 'tgp_level'],
            self::UREA_AND_CREATININE => ['urea_level', 'creatinine_level'],
            self::URIC_ACID => ['uric_acid_level'],
            self::VITAMIN_B12 => ['vitamin_b12_level'],
            self::VITAMIN_D3 => ['twenty_five_hydroxyvitamin_d3'],
        };

        return [
            'report_date' => ['required', 'date'],
            ...array_fill_keys($measurements, ['required', 'numeric', 'min:0']),
        ];
    }

    /** @return list<string> */
    public function headers(): array
    {
        return array_keys($this->rules());
    }
}
