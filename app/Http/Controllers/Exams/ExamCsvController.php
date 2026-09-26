<?php

namespace App\Http\Controllers\Exams;

use App\Enums\ExamType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Exams\ImportExamCsvRequest;
use App\Jobs\ProcessExamCsv;
use App\Models\Exams\ExamCsvTransfer;
use App\Services\Exams\ExamCsvService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class ExamCsvController extends Controller
{
    public function import(ImportExamCsvRequest $request, ExamType $examType, ExamCsvService $service): RedirectResponse
    {
        $user = $request->user();
        $medicalFile = $user->medicalFile;
        abort_if($medicalFile === null, 422, __('exam_csv.medical_file_required'));

        $file = $request->file('file');
        $header = $service->header($file->getRealPath(), $examType);
        $path = $file->store('exam-csv', 'local');
        abort_if($path === false, 500);

        try {
            $transfer = ExamCsvTransfer::query()->create([
                'user_id' => $user->id,
                'medical_file_id' => $medicalFile->id,
                'exam_type' => $examType,
                'direction' => 'import',
                'status' => 'validating',
                'path' => $path,
                ...$header,
                'expires_at' => now()->addWeek(),
            ]);
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($path);

            throw $exception;
        }

        ProcessExamCsv::dispatch($transfer->id, 0)->afterCommit();
        Inertia::flash('toast', ['type' => 'info', 'message' => __('exam_csv.import_queued')]);

        return back();
    }

    public function export(Request $request, ExamType $examType): RedirectResponse
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

    public function download(Request $request, ExamCsvTransfer $transfer): StreamedResponse
    {
        abort_unless($transfer->user_id === $request->user()->id, 404);
        abort_unless($transfer->direction === 'export' && $transfer->status === 'completed', 404);
        abort_if($transfer->expires_at->isPast(), 410);
        abort_unless(Storage::disk('local')->exists($transfer->path), 404);

        return Storage::disk('local')->download(
            $transfer->path,
            $transfer->exam_type->value.'.csv',
            ['Content-Type' => 'text/csv; charset=UTF-8', 'Cache-Control' => 'private, no-store'],
        );
    }
}
