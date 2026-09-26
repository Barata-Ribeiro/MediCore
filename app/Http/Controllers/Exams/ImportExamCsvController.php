<?php

namespace App\Http\Controllers\Exams;

use App\Enums\ExamType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Exams\ImportExamCsvRequest;
use App\Jobs\ProcessExamCsv;
use App\Models\Exams\ExamCsvTransfer;
use App\Services\Exams\ExamCsvService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Throwable;

class ImportExamCsvController extends Controller
{
    public function __invoke(ImportExamCsvRequest $request, ExamType $examType, ExamCsvService $service): RedirectResponse
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
}
