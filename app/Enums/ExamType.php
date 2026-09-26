<?php

namespace App\Enums;

use App\Http\Requests\Exams\CompleteBloodCountRequest;
use App\Http\Requests\Exams\GlucoseRequest;
use App\Http\Requests\Exams\LipidProfileRequest;
use App\Http\Requests\Exams\TgoAndTgpRequest;
use App\Http\Requests\Exams\TotalProteinsAndFractionsRequest;
use App\Http\Requests\Exams\UltrasensitiveTshRequest;
use App\Http\Requests\Exams\UreaAndCreatinineRequest;
use App\Http\Requests\Exams\UricAcidRequest;
use App\Http\Requests\Exams\VitaminB12Request;
use App\Http\Requests\Exams\VitaminD3Request;
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
use Illuminate\Contracts\Validation\ValidationRule;
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

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        $request = match ($this) {
            self::COMPLETE_BLOOD_COUNT => new CompleteBloodCountRequest,
            self::GLUCOSE => new GlucoseRequest,
            self::LIPID_PROFILE => new LipidProfileRequest,
            self::TOTAL_PROTEINS_AND_FRACTIONS => new TotalProteinsAndFractionsRequest,
            self::ULTRASENSITIVE_TSH => new UltrasensitiveTshRequest,
            self::TGO_AND_TGP => new TgoAndTgpRequest,
            self::UREA_AND_CREATININE => new UreaAndCreatinineRequest,
            self::URIC_ACID => new UricAcidRequest,
            self::VITAMIN_B12 => new VitaminB12Request,
            self::VITAMIN_D3 => new VitaminD3Request,
        };

        return $request->rules();
    }

    /** @return list<string> */
    public function headers(): array
    {
        return array_keys($this->rules());
    }
}
