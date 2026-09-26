<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Models\Exams\ExamCsvTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadExamCsvController extends Controller
{
    public function __invoke(Request $request, ExamCsvTransfer $transfer): StreamedResponse
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
