<?php

namespace Database\Factories\Exams;

use App\Enums\ExamType;
use App\Models\Exams\ExamCsvTransfer;
use App\Models\MedicalFile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<ExamCsvTransfer> */
class ExamCsvTransferFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'medical_file_id' => fn (): int => User::factory()->create()->medicalFile()->create()->id,
            'user_id' => fn (array $attributes): int => MedicalFile::query()->whereKey($attributes['medical_file_id'])->sole()->user_id,
            'exam_type' => ExamType::GLUCOSE,
            'direction' => 'export',
            'status' => 'exporting',
            'path' => 'exam-csv/'.Str::uuid().'.csv',
            'headers' => ExamType::GLUCOSE->headers(),
            'expires_at' => now()->addDay(),
        ];
    }
}
