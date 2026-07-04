<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models\Exams{
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
 * @mixin \Eloquent
 */
	class CompleteBloodCount extends \Eloquent {}
}

namespace App\Models\Exams{
/**
 * @property int $id
 * @property float $glucose_level
 * @property float $glycated_hemoglobin Glycated Hemoglobin (HbA1c)
 * @property float $estimated_average_glucose Estimated Average Glucose (eAG)
 * @property CarbonImmutable $report_date Date of the glucose report
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $medical_file_id
 * @property-read MedicalFile $medicalFile
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose whereEstimatedAverageGlucose($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose whereGlucoseLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose whereGlycatedHemoglobin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose whereMedicalFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Glucose whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Glucose extends \Eloquent {}
}

namespace App\Models\Exams{
/**
 * @property int $id
 * @property float $total_cholesterol Total Cholesterol
 * @property float $hdl_cholesterol High-Density Lipoprotein Cholesterol
 * @property float $ldl_cholesterol Low-Density Lipoprotein Cholesterol
 * @property float $vldl_cholesterol Very Low-Density Lipoprotein Cholesterol
 * @property float $triglycerides Triglycerides
 * @property CarbonImmutable $report_date Date of the lipid profile report
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $medical_file_id
 * @property-read MedicalFile $medicalFile
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile whereHdlCholesterol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile whereLdlCholesterol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile whereMedicalFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile whereTotalCholesterol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile whereTriglycerides($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LipidProfile whereVldlCholesterol($value)
 * @mixin \Eloquent
 */
	class LipidProfile extends \Eloquent {}
}

namespace App\Models\Exams{
/**
 * @property int $id
 * @property float $total_proteins Total Proteins level
 * @property float $albumin Albumin level
 * @property float $globulin Globulin level
 * @property CarbonImmutable $report_date Date of the Vitamin B12 report
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $medical_file_id
 * @property-read float|int|null $albumin_globulin_ratio
 * @property-read MedicalFile $medicalFile
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
 * @mixin \Eloquent
 */
	class TotalProteinsAndFractions extends \Eloquent {}
}

namespace App\Models\Exams{
/**
 * @property int $id
 * @property float $tsh_level Ultrasensitive TSH level
 * @property CarbonImmutable $report_date Date of the Urea and Creatinine report
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $medical_file_id
 * @property-read MedicalFile $medicalFile
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UltrasensitiveTsh newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UltrasensitiveTsh newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UltrasensitiveTsh query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UltrasensitiveTsh whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UltrasensitiveTsh whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UltrasensitiveTsh whereMedicalFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UltrasensitiveTsh whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UltrasensitiveTsh whereTshLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UltrasensitiveTsh whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class UltrasensitiveTsh extends \Eloquent {}
}

namespace App\Models\Exams{
/**
 * @property int $id
 * @property float $urea_level Urea level
 * @property float $creatinine_level Creatinine level
 * @property CarbonImmutable $report_date Date of the Urea and Creatinine report
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $medical_file_id
 * @property-read float|null $urea_creatinine_ratio
 * @property-read MedicalFile $medicalFile
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UreaAndCreatinine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UreaAndCreatinine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UreaAndCreatinine query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UreaAndCreatinine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UreaAndCreatinine whereCreatinineLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UreaAndCreatinine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UreaAndCreatinine whereMedicalFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UreaAndCreatinine whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UreaAndCreatinine whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UreaAndCreatinine whereUreaLevel($value)
 * @mixin \Eloquent
 */
	class UreaAndCreatinine extends \Eloquent {}
}

namespace App\Models\Exams{
/**
 * @property int $id
 * @property float $uric_acid_level Uric Acid level
 * @property CarbonImmutable $report_date Date of the Urea and Creatinine report
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $medical_file_id
 * @property-read MedicalFile $medicalFile
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UricAcid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UricAcid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UricAcid query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UricAcid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UricAcid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UricAcid whereMedicalFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UricAcid whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UricAcid whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UricAcid whereUricAcidLevel($value)
 * @mixin \Eloquent
 */
	class UricAcid extends \Eloquent {}
}

namespace App\Models\Exams{
/**
 * @property int $id
 * @property float $vitamin_b12_level Vitamin B12 level
 * @property CarbonImmutable $report_date Date of the Vitamin B12 report
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $medical_file_id
 * @property-read MedicalFile $medicalFile
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminB12 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminB12 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminB12 query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminB12 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminB12 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminB12 whereMedicalFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminB12 whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminB12 whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminB12 whereVitaminB12Level($value)
 * @mixin \Eloquent
 */
	class VitaminB12 extends \Eloquent {}
}

namespace App\Models\Exams{
/**
 * @property int $id
 * @property float $twenty_five_hydroxyvitamin_d3 25-Hydroxyvitamin D3 (25(OH)D3) level
 * @property CarbonImmutable $report_date Date of the Vitamin D3 report
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $medical_file_id
 * @property-read MedicalFile $medicalFile
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminD3 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminD3 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminD3 query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminD3 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminD3 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminD3 whereMedicalFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminD3 whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminD3 whereTwentyFiveHydroxyvitaminD3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitaminD3 whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class VitaminD3 extends \Eloquent {}
}

namespace App\Models\Fitness{
/**
 * @property int $id
 * @property string $name The name of the exercise, e.g. "Bench Press", "Squat", etc.
 * @property string|null $description A detailed description of how to perform the exercise, including proper form and technique.
 * @property string|null $video_url A URL to a video demonstrating the exercise, if available.
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Collection<int, \App\Models\Fitness\MuscleGroup> $muscleGroups
 * @property-read int|null $muscle_groups_count
 * @property-read bool|null $muscle_groups_exists
 * @property-read Collection<int, \App\Models\Fitness\WorkoutExercise> $workoutExercises
 * @property-read int|null $workout_exercises_count
 * @property-read bool|null $workout_exercises_exists
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exercise newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exercise newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exercise query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exercise whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exercise whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exercise whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exercise whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exercise whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exercise whereVideoUrl($value)
 * @mixin \Eloquent
 */
	class Exercise extends \Eloquent {}
}

namespace App\Models\Fitness{
/**
 * @property int $id
 * @property string $name The name of the muscle group, e.g. "Pectorals", "Quadriceps", etc.
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Collection<int, \App\Models\Fitness\Exercise> $exercises
 * @property-read int|null $exercises_count
 * @property-read bool|null $exercises_exists
 * @property-read Collection<int, \App\Models\Fitness\WorkoutExercise> $workoutExercises
 * @property-read int|null $workout_exercises_count
 * @property-read bool|null $workout_exercises_exists
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MuscleGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MuscleGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MuscleGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MuscleGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MuscleGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MuscleGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MuscleGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class MuscleGroup extends \Eloquent {}
}

namespace App\Models\Fitness{
/**
 * @property int $id
 * @property CarbonImmutable|null $filled_at The date when the workout was filled out
 * @property CarbonImmutable|null $next_change_at The date when the workout should be changed next
 * @property string|null $goal The goal of the workout, e.g. "lose weight", "build muscle", etc.
 * @property string|null $method The method of the workout, e.g. "A/B split", "full body", etc.
 * @property int|null $rest_between_sets The rest time between sets in seconds
 * @property int|null $rest_between_exercises The rest time between exercises in seconds
 * @property bool $is_active Whether the workout is active or not
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $user_id
 * @property-read Collection<int, \App\Models\Fitness\WorkoutSection> $sections
 * @property-read int|null $sections_count
 * @property-read bool|null $sections_exists
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereFilledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereGoal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereNextChangeAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereRestBetweenExercises($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereRestBetweenSets($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workout whereUserId($value)
 * @mixin \Eloquent
 */
	class Workout extends \Eloquent {}
}

namespace App\Models\Fitness{
/**
 * @property int $id
 * @property int $workout_section_id
 * @property int $exercise_id
 * @property int|null $muscle_group_id
 * @property string|null $code Code of the exercise, e.g., "A1", "B2"
 * @property int $order The order of the exercise in the workout section
 * @property int $sets Number of sets
 * @property string $reps Reps, e.g., "8-12", "AMRAP", "Failure", etc.
 * @property float|null $load Load, default unit is kg
 * @property string $load_unit Unit of the load, e.g., "kg", "lbs", "bodyweight", etc.
 * @property int|null $rest_seconds Rest time in seconds between sets
 * @property string|null $notes Additional notes for the exercise, e.g., "Use a spotter", "Focus on form", etc.
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read \App\Models\Fitness\Exercise $exercise
 * @property-read \App\Models\Fitness\MuscleGroup|null $muscleGroup
 * @property-read \App\Models\Fitness\WorkoutSection $section
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereExerciseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereLoad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereLoadUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereMuscleGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereReps($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereRestSeconds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereSets($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutExercise whereWorkoutSectionId($value)
 * @mixin \Eloquent
 */
	class WorkoutExercise extends \Eloquent {}
}

namespace App\Models\Fitness{
/**
 * @property int $id
 * @property string $name The name of the workout section, e.g. "Superior", "Inferior", "Day A", etc.
 * @property int $order The order of the workout section in the workout
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $workout_id
 * @property-read Collection<int, \App\Models\Fitness\WorkoutExercise> $exercises
 * @property-read int|null $exercises_count
 * @property-read bool|null $exercises_exists
 * @property-read \App\Models\Fitness\Workout $workout
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutSection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutSection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutSection query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutSection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutSection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutSection whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutSection whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutSection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutSection whereWorkoutId($value)
 * @mixin \Eloquent
 */
	class WorkoutSection extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property BloodType|null $blood_type
 * @property string|null $allergies
 * @property string|null $diseases
 * @property string|null $medications
 * @property float|null $weight
 * @property float|null $height
 * @property string|null $emergency_contact_name
 * @property string|null $emergency_contact_phone_number
 * @property string|null $emergency_contact_relationship
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $user_id
 * @property-read Collection<int, CompleteBloodCount> $completeBloodCounts
 * @property-read int|null $complete_blood_counts_count
 * @property-read bool|null $complete_blood_counts_exists
 * @property-read float|null $bmi
 * @property-read Collection<int, Glucose> $glucoses
 * @property-read int|null $glucoses_count
 * @property-read bool|null $glucoses_exists
 * @property-read Collection<int, LipidProfile> $lipidProfiles
 * @property-read int|null $lipid_profiles_count
 * @property-read bool|null $lipid_profiles_exists
 * @property-read Collection<int, TotalProteinsAndFractions> $totalProteinsAndFractions
 * @property-read int|null $total_proteins_and_fractions_count
 * @property-read bool|null $total_proteins_and_fractions_exists
 * @property-read Collection<int, UltrasensitiveTsh> $ultrasensitiveTshs
 * @property-read int|null $ultrasensitive_tshs_count
 * @property-read bool|null $ultrasensitive_tshs_exists
 * @property-read Collection<int, UreaAndCreatinine> $ureaAndCreatinines
 * @property-read int|null $urea_and_creatinines_count
 * @property-read bool|null $urea_and_creatinines_exists
 * @property-read Collection<int, UricAcid> $uricAcids
 * @property-read int|null $uric_acids_count
 * @property-read bool|null $uric_acids_exists
 * @property-read \App\Models\User $user
 * @property-read Collection<int, VitaminB12> $vitaminB12s
 * @property-read int|null $vitamin_b12s_count
 * @property-read bool|null $vitamin_b12s_exists
 * @property-read Collection<int, VitaminD3> $vitaminD3s
 * @property-read int|null $vitamin_d3s_count
 * @property-read bool|null $vitamin_d3s_exists
 * @method static Builder<static>|MedicalFile bloodType(\App\Enums\BloodType $bloodType)
 * @method static Builder<static>|MedicalFile newModelQuery()
 * @method static Builder<static>|MedicalFile newQuery()
 * @method static Builder<static>|MedicalFile query()
 * @method static Builder<static>|MedicalFile whereAllergies($value)
 * @method static Builder<static>|MedicalFile whereBloodType($value)
 * @method static Builder<static>|MedicalFile whereCreatedAt($value)
 * @method static Builder<static>|MedicalFile whereDiseases($value)
 * @method static Builder<static>|MedicalFile whereEmergencyContactName($value)
 * @method static Builder<static>|MedicalFile whereEmergencyContactPhoneNumber($value)
 * @method static Builder<static>|MedicalFile whereEmergencyContactRelationship($value)
 * @method static Builder<static>|MedicalFile whereHeight($value)
 * @method static Builder<static>|MedicalFile whereId($value)
 * @method static Builder<static>|MedicalFile whereMedications($value)
 * @method static Builder<static>|MedicalFile whereUpdatedAt($value)
 * @method static Builder<static>|MedicalFile whereUserId($value)
 * @method static Builder<static>|MedicalFile whereWeight($value)
 * @mixin \Eloquent
 */
	class MedicalFile extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $bio
 * @property CarbonImmutable|null $birth_date
 * @property string|null $phone_number
 * @property string|null $address
 * @property string|null $sex
 * @property string|null $gender_identity
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $user_id
 * @property-read int|null $age
 * @property-read string $full_name
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereGenderIdentity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereUserId($value)
 * @mixin \Eloquent
 */
	class Profile extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property CarbonImmutable|null $email_verified_at
 * @property string|null $password
 * @property string|null $remember_token
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property CarbonImmutable|null $two_factor_confirmed_at
 * @property string|null $provider_id
 * @property string|null $provider_name
 * @property string|null $registration_domain
 * @property string|null $provider_token
 * @property string|null $provider_refresh_token
 * @property string|null $avatar
 * @property string $locale
 * @property-read \App\Models\MedicalFile|null $medicalFile
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read bool|null $notifications_exists
 * @property-read Collection<int, Passkey> $passkeys
 * @property-read int|null $passkeys_count
 * @property-read bool|null $passkeys_exists
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read bool|null $permissions_exists
 * @property-read \App\Models\Profile|null $profile
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read bool|null $roles_exists
 * @property-read Collection<int, Permission> $teams
 * @property-read int|null $teams_count
 * @property-read bool|null $teams_exists
 * @property-read Collection<int, Workout> $workouts
 * @property-read int|null $workouts_count
 * @property-read bool|null $workouts_exists
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static Builder<static>|User isSuperAdmin()
 * @method static Builder<static>|User newModelQuery()
 * @method static Builder<static>|User newQuery()
 * @method static Builder<static>|User permission($permissions, bool $without = false)
 * @method static Builder<static>|User query()
 * @method static Builder<static>|User role($roles, ?string $guard = null, bool $without = false)
 * @method static Builder<static>|User team($teams, bool $without = false)
 * @method static Builder<static>|User whereAvatar($value)
 * @method static Builder<static>|User whereCreatedAt($value)
 * @method static Builder<static>|User whereEmail($value)
 * @method static Builder<static>|User whereEmailVerifiedAt($value)
 * @method static Builder<static>|User whereId($value)
 * @method static Builder<static>|User whereLocale($value)
 * @method static Builder<static>|User whereName($value)
 * @method static Builder<static>|User wherePassword($value)
 * @method static Builder<static>|User whereProviderId($value)
 * @method static Builder<static>|User whereProviderName($value)
 * @method static Builder<static>|User whereProviderRefreshToken($value)
 * @method static Builder<static>|User whereProviderToken($value)
 * @method static Builder<static>|User whereRegistrationDomain($value)
 * @method static Builder<static>|User whereRememberToken($value)
 * @method static Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static Builder<static>|User whereTwoFactorSecret($value)
 * @method static Builder<static>|User whereUpdatedAt($value)
 * @method static Builder<static>|User withoutPermission($permissions)
 * @method static Builder<static>|User withoutRole($roles, ?string $guard = null)
 * @method static Builder<static>|User withoutTeam($teams)
 * @mixin \Eloquent
 */
	class User extends \Eloquent implements \Laravel\Fortify\Contracts\PasskeyUser, \Laravel\Passkeys\Contracts\PasskeyUser {}
}

