<?php

namespace App\Http\Controllers\Exams;

use App\Enums\ExamType;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessExamCsv;
use App\Models\Exams\ExamCsvTransfer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ExportExamCsvController extends Controller
{
    public function __invoke(Request $request, ExamType $examType): RedirectResponse
    {
        $user = $request->user();
        $medicalFile = $user->medicalFile;
        abort_if($medicalFile === null, 422, __('exam_csv.medical_file_required'));

        DB::transaction(function () use ($user, $medicalFile, $examType): void {
            $transfer = ExamCsvTransfer::query()->create([
                'user_id' => $user->id,
                'medical_file_id' => $medicalFile->id,
                'exam_type' => $examType,
                'direction' => 'export',
                'status' => 'exporting',
                'path' => 'exam-csv/'.Str::uuid().'.csv',
                'headers' => $examType->headers(),
                'max_id' => $examType->model()::query()->where('medical_file_id', $medicalFile->id)->max('id') ?? 0,
                'expires_at' => now()->addWeek(),
            ]);
            ProcessExamCsv::dispatch($transfer->id, 0)->afterCommit();
        });

        Inertia::flash('toast', ['type' => 'info', 'message' => __('exam_csv.export_queued')]);

        return back();
    }
}
