<?php

use App\Enums\ExamType;
use App\Events\ExamCsvFinished;
use App\Jobs\ProcessExamCsv;
use App\Models\Exams\ExamCsvTransfer;
use App\Models\Exams\Glucose;
use App\Models\User;
use App\Services\Exams\ExamCsvService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->withoutVite();
});

it('queues imports with only an identifier and checkpoint after commit', function () {
    Storage::fake('local');
    Queue::fake();
    $user = User::factory()->create();
    $user->medicalFile()->create();

    $this->actingAs($user)->post(route('exams.csv.import', 'glucose'), [
        'file' => UploadedFile::fake()->createWithContent('glucose.csv', "report_date,glucose_level,glycated_hemoglobin,estimated_average_glucose\n2026-09-01,95,5.2,103\n"),
    ])->assertRedirect()->assertSessionHasNoErrors();

    $transfer = ExamCsvTransfer::query()->sole();
    expect($transfer->status)->toBe('validating');
    expect($transfer->user_id)->toBe($user->id);
    Storage::disk('local')->assertExists($transfer->path);
    $this->assertDatabaseCount('glucoses', 0);
    Queue::assertPushedOn('default', ProcessExamCsv::class, fn (ProcessExamCsv $job): bool => $job->transferId === $transfer->id && $job->revision === 0 && $job->afterCommit === true);
});

it('round trips every exam with all its measurements and private ownership', function (ExamType $examType) {
    Storage::fake('local');
    Event::fake([ExamCsvFinished::class]);
    $model = $examType->model();
    $record = $model::factory()->create(['report_date' => '2026-09-01']);
    $medicalFile = $record->medicalFile;
    $original = $record->only($examType->headers());

    $this->actingAs($medicalFile->user)->post(route('exams.csv.export', $examType->value))
        ->assertRedirect()->assertSessionHasNoErrors();

    $export = ExamCsvTransfer::query()->sole();
    expect($export->status)->toBe('completed');
    $csv = Storage::disk('local')->get($export->path);
    $this->get(route('exams.csv.download', $export))->assertOk()->assertDownload($examType->value.'.csv');

    $this->post(route('exams.csv.import', $examType->value), [
        'file' => UploadedFile::fake()->createWithContent('exam.csv', $csv),
    ])->assertRedirect()->assertSessionHasNoErrors();

    $import = ExamCsvTransfer::query()->where('direction', 'import')->sole();
    expect($import->status)->toBe('completed');
    expect($import->processed)->toBe(1);
    expect($model::query()->where('medical_file_id', $medicalFile->id)->count())->toBe(2);
    expect($model::query()->latest('id')->first()->only($examType->headers()))->toEqual($original);
    Storage::disk('local')->assertMissing($import->path);
    Event::assertDispatched(ExamCsvFinished::class, fn ($event): bool => $event->transferId === $import->id);
})->with(ExamType::cases());

it('exports all pages while excluding other users and other exam types', function () {
    Storage::fake('local');
    Event::fake([ExamCsvFinished::class]);
    $user = User::factory()->create();
    $medicalFile = $user->medicalFile()->create();
    Glucose::factory()->count(251)->for($medicalFile)->create(['glucose_level' => 95, 'report_date' => '2026-09-01']);
    Glucose::factory()->create(['glucose_level' => 9999]);

    $this->actingAs($user)->post(route('exams.csv.export', ['examType' => 'glucose', 'page' => 2, 'per_page' => 10, 'search' => '9999']))
        ->assertRedirect();

    $transfer = ExamCsvTransfer::query()->sole();
    expect($transfer->processed)->toBe(251);
    $csv = Storage::disk('local')->get($transfer->path);
    expect(substr_count($csv, "\n"))->toBe(252);
    expect($csv)->not->toContain('9999')->not->toContain('medical_file_id');
    Event::assertDispatched(ExamCsvFinished::class);
});

it('rejects incompatible headers before storing or queuing a file', function (string $header) {
    Storage::fake('local');
    Queue::fake();
    $user = User::factory()->create();
    $user->medicalFile()->create();

    $this->actingAs($user)->post(route('exams.csv.import', 'glucose'), [
        'file' => UploadedFile::fake()->createWithContent('exam.csv', $header."\n2026-09-01,95,5.2,103\n"),
    ])->assertSessionHasErrors(['file' => __('exam_csv.headers_mismatch', [
        'columns' => 'report_date, glucose_level, glycated_hemoglobin, estimated_average_glucose',
    ])]);

    $this->assertDatabaseCount('exam_csv_transfers', 0);
    expect(Storage::disk('local')->allFiles())->toBe([]);
    Queue::assertNothingPushed();
})->with([
    'missing' => 'report_date,glucose_level',
    'unexpected' => 'report_date,glucose_level,glycated_hemoglobin,estimated_average_glucose,medical_file_id',
    'duplicate' => 'report_date,glucose_level,glucose_level,estimated_average_glucose',
    'different exam' => 'report_date,uric_acid_level',
]);

it('accepts BOM semicolons reordered columns and blank lines', function () {
    Storage::fake('local');
    Event::fake([ExamCsvFinished::class]);
    $user = User::factory()->create();
    $user->medicalFile()->create();

    $this->actingAs($user)->post(route('exams.csv.import', 'glucose'), [
        'file' => UploadedFile::fake()->createWithContent('exam.csv', "\xEF\xBB\xBFglucose_level;report_date;estimated_average_glucose;glycated_hemoglobin\r\n\r\n95;2026-09-01;103;5.2\r\n"),
    ])->assertRedirect()->assertSessionHasNoErrors();

    $this->assertDatabaseHas('glucoses', ['glucose_level' => 95, 'glycated_hemoglobin' => 5.2, 'medical_file_id' => $user->medicalFile->id]);
    expect(ExamCsvTransfer::query()->sole()->status)->toBe('completed');
    Event::assertDispatched(ExamCsvFinished::class);
});

it('validates the whole CSV before writing any records', function (string $invalidRow) {
    Storage::fake('local');
    Event::fake([ExamCsvFinished::class]);
    $user = User::factory()->create();
    $user->medicalFile()->create();
    $csv = "report_date,glucose_level,glycated_hemoglobin,estimated_average_glucose\n"
        .str_repeat("2026-09-01,95,5.2,103\n", 251).$invalidRow."\n";

    $this->actingAs($user)->post(route('exams.csv.import', 'glucose'), [
        'file' => UploadedFile::fake()->createWithContent('exam.csv', $csv),
    ])->assertRedirect()->assertSessionHasNoErrors();

    $transfer = ExamCsvTransfer::query()->sole();
    expect($transfer->status)->toBe('failed');
    expect($transfer->error_code)->toBe('invalid_row');
    expect($transfer->error_line)->toBe(253);
    $this->assertDatabaseCount('glucoses', 0);
    Storage::disk('local')->assertMissing($transfer->path);
    Event::assertDispatched(ExamCsvFinished::class);
})->with([
    'invalid number' => '2026-09-01,no,5.2,103',
    'negative value' => '2026-09-01,-1,5.2,103',
    'invalid date' => '2026-02-30,95,5.2,103',
    'extra column' => '2026-09-01,95,5.2,103,123',
    'missing measurement' => '2026-09-01,95,,103',
    'nonfinite number' => '2026-09-01,1e999,5.2,103',
    'formula' => '2026-09-01,=1+1,5.2,103',
]);

it('reports a header-only import as empty', function () {
    Storage::fake('local');
    Event::fake([ExamCsvFinished::class]);
    $user = User::factory()->create();
    $user->medicalFile()->create();

    $this->actingAs($user)->post(route('exams.csv.import', 'glucose'), [
        'file' => UploadedFile::fake()->createWithContent('exam.csv', "report_date,glucose_level,glycated_hemoglobin,estimated_average_glucose\n"),
    ])->assertRedirect();
    expect(ExamCsvTransfer::query()->sole()->error_code)->toBe('empty_file');
    $this->assertDatabaseCount('glucoses', 0);
    Event::assertDispatched(ExamCsvFinished::class);
});

it('exports an empty exam as a reusable CSV header', function () {
    Storage::fake('local');
    Event::fake([ExamCsvFinished::class]);
    $user = User::factory()->create();
    $user->medicalFile()->create();

    $this->actingAs($user)->post(route('exams.csv.export', 'uric-acid'))->assertRedirect();
    $transfer = ExamCsvTransfer::query()->sole();
    expect(Storage::disk('local')->get($transfer->path))->toBe("report_date,uric_acid_level\r\n");
    expect($transfer->status)->toBe('completed');
    Event::assertDispatched(ExamCsvFinished::class);
});

it('restricts CSV endpoints to authenticated users', function (string $action) {
    $this->post(route('exams.csv.'.$action, 'glucose'))->assertRedirect(route('login'));
})->with(['import', 'export']);

it('rejects fitness and unknown exam types', function (string $type) {
    Queue::fake();
    $this->actingAs(User::factory()->create())->post(route('exams.csv.export', $type))->assertNotFound();
    Queue::assertNothingPushed();
})->with(['workout', 'exercise', 'muscle-group', 'unknown']);

it('validates the upload size type and extension', function (string $kind) {
    Queue::fake();
    $user = User::factory()->create();
    $user->medicalFile()->create();
    $file = match ($kind) {
        'missing' => null,
        'large' => UploadedFile::fake()->create('exam.csv', 10241, 'text/csv'),
        'extension' => UploadedFile::fake()->createWithContent('exam.php', 'a,b,c'),
        'content' => UploadedFile::fake()->image('exam.csv'),
    };
    $this->actingAs($user)->post(route('exams.csv.import', 'glucose'), ['file' => $file])
        ->assertSessionHasErrors('file');
    Queue::assertNothingPushed();
})->with(['missing', 'large', 'extension', 'content']);

it('only allows the owner to download a completed unexpired export', function () {
    Storage::fake('local');
    $transfer = ExamCsvTransfer::factory()->create(['status' => 'completed']);
    Storage::disk('local')->put($transfer->path, 'private medical data');

    $this->get(route('exams.csv.download', $transfer))->assertRedirect(route('login'));
    $this->actingAs(User::factory()->create())->get(route('exams.csv.download', $transfer))->assertNotFound();
    $this->actingAs($transfer->user)->get(route('exams.csv.download', $transfer))
        ->assertOk()->assertDownload('glucose.csv')->assertHeader('Cache-Control', 'no-store, private');

    $transfer->update(['status' => 'exporting']);
    $this->get(route('exams.csv.download', $transfer))->assertNotFound();
    $transfer->update(['status' => 'completed', 'expires_at' => now()->subMinute()]);
    $this->get(route('exams.csv.download', $transfer))->assertGone();
});

it('recovers only the current users recent transfers on any page', function () {
    $transfer = ExamCsvTransfer::factory()->create(['status' => 'completed']);
    ExamCsvTransfer::factory()->create(['status' => 'completed']);
    ExamCsvTransfer::factory()->create(['user_id' => $transfer->user_id, 'expires_at' => now()->subDay()]);

    $this->actingAs($transfer->user)->get(route('dashboard'))->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('examCsvTransfers', 1)
            ->where('examCsvTransfers.0.id', $transfer->id)
            ->missing('examCsvTransfers.0.path')
            ->has('lang.exam_csv.download'));
});

it('broadcasts completion privately without medical measurements or storage paths', function () {
    $transfer = ExamCsvTransfer::factory()->create(['status' => 'completed']);
    $event = new ExamCsvFinished($transfer->id);

    expect($event->broadcastOn()[0]->name)->toBe('private-App.Models.User.'.$transfer->user_id);
    expect($event->broadcastWith())->toMatchArray(['id' => $transfer->id, 'status' => 'completed'])
        ->not->toHaveKey('path')->not->toHaveKey('medical_file_id');
});

it('prunes expired CSV files while retaining available exports', function () {
    Storage::fake('local');
    $expired = ExamCsvTransfer::factory()->create(['expires_at' => now()->subDay()]);
    $active = ExamCsvTransfer::factory()->create();
    Storage::disk('local')->put($expired->path, 'expired');
    Storage::disk('local')->put($active->path, 'available');

    $this->artisan('model:prune', ['--model' => [ExamCsvTransfer::class]])->assertSuccessful();
    $this->assertModelMissing($expired);
    $this->assertModelExists($active);
    Storage::disk('local')->assertMissing($expired->path);
    Storage::disk('local')->assertExists($active->path);
});

it('resumes imports without duplicating committed chunks when jobs are redelivered', function () {
    Storage::fake('local');
    Queue::fake();
    Event::fake([ExamCsvFinished::class]);
    $user = User::factory()->create();
    $user->medicalFile()->create();
    $this->actingAs($user)->post(route('exams.csv.import', 'glucose'), [
        'file' => UploadedFile::fake()->createWithContent('exam.csv',
            "report_date,glucose_level,glycated_hemoglobin,estimated_average_glucose\n"
            .str_repeat("2026-09-01,95,5.2,103\n", 251)),
    ])->assertRedirect();
    $transfer = ExamCsvTransfer::query()->sole();
    $service = app(ExamCsvService::class);

    (new ProcessExamCsv($transfer->id, 0))->handle($service);
    (new ProcessExamCsv($transfer->id, 1))->handle($service);
    expect($transfer->fresh()->status)->toBe('importing');
    $this->assertDatabaseCount('glucoses', 0);

    $chunk = new ProcessExamCsv($transfer->id, 2);
    $chunk->handle($service);
    $chunk->handle($service);
    $this->assertDatabaseCount('glucoses', 250);

    (new ProcessExamCsv($transfer->id, 3))->handle($service);
    (new ProcessExamCsv($transfer->id, 3))->handle($service);
    $this->assertDatabaseCount('glucoses', 251);
    expect($transfer->fresh()->status)->toBe('completed');
    Storage::disk('local')->assertMissing($transfer->path);
    Queue::assertPushed(ProcessExamCsv::class, fn ($job): bool => $job->revision === 3);
    Event::assertDispatched(ExamCsvFinished::class);
});

it('restores export checkpoints after a partially written file and ignores repeated chunks', function () {
    Storage::fake('local');
    Queue::fake();
    Event::fake([ExamCsvFinished::class]);
    $user = User::factory()->create();
    $medicalFile = $user->medicalFile()->create();
    Glucose::factory()->count(251)->for($medicalFile)->create();
    $this->actingAs($user)->post(route('exams.csv.export', 'glucose'))->assertRedirect();
    $transfer = ExamCsvTransfer::query()->sole();
    $service = app(ExamCsvService::class);
    $first = new ProcessExamCsv($transfer->id, 0);
    $first->handle($service);
    $first->handle($service);
    expect($transfer->fresh()->processed)->toBe(250);

    Storage::disk('local')->append($transfer->path, 'partial uncommitted output');
    $last = new ProcessExamCsv($transfer->id, 1);
    $last->handle($service);
    $last->handle($service);
    $csv = Storage::disk('local')->get($transfer->path);
    expect($csv)->not->toContain('partial uncommitted output');
    expect(substr_count($csv, "\n"))->toBe(252);
    expect($transfer->fresh()->processed)->toBe(251);
    Queue::assertPushed(ProcessExamCsv::class, fn ($job): bool => $job->revision === 1);
    Event::assertDispatched(ExamCsvFinished::class);
});

it('refuses to complete an export when committed output has been lost', function () {
    Storage::fake('local');
    Queue::fake();
    $transfer = ExamCsvTransfer::factory()->create(['offset' => 100, 'revision' => 1]);
    Storage::disk('local')->put($transfer->path, 'truncated');

    expect(fn () => (new ProcessExamCsv($transfer->id, 1))->handle(app(ExamCsvService::class)))
        ->toThrow(RuntimeException::class, 'CSV output is shorter than its committed checkpoint.');

    expect($transfer->fresh()->status)->toBe('exporting');
    Queue::assertNothingPushed();
});

it('rolls back an interrupted import chunk before retrying it', function () {
    Storage::fake('local');
    Queue::fake();
    $user = User::factory()->create();
    $user->medicalFile()->create();
    $this->actingAs($user)->post(route('exams.csv.import', 'glucose'), [
        'file' => UploadedFile::fake()->createWithContent('exam.csv',
            "report_date,glucose_level,glycated_hemoglobin,estimated_average_glucose\n"
            ."2026-09-01,95,5.2,103\n2026-09-02,96,5.3,104\n"),
    ])->assertRedirect();
    $transfer = ExamCsvTransfer::query()->sole();
    $service = app(ExamCsvService::class);
    (new ProcessExamCsv($transfer->id, 0))->handle($service);

    $creating = 0;
    Glucose::creating(function () use (&$creating): void {
        if (++$creating === 2) {
            throw new RuntimeException('Transient write failure.');
        }
    });
    $job = new ProcessExamCsv($transfer->id, 1);
    expect(fn () => $job->handle($service))->toThrow(RuntimeException::class, 'Transient write failure.');
    $this->assertDatabaseCount('glucoses', 0);
    expect($transfer->fresh()->processed)->toBe(0);
    $job->handle($service);
    $this->assertDatabaseCount('glucoses', 2);
    expect($transfer->fresh()->status)->toBe('completed');
    Queue::assertPushed(ProcessExamCsv::class);
});

it('marks exhausted jobs as failed and keeps completed transfers intact', function () {
    Storage::fake('local');
    Event::fake([ExamCsvFinished::class]);
    $transfer = ExamCsvTransfer::factory()->create();
    Storage::disk('local')->put($transfer->path, 'incomplete');
    (new ProcessExamCsv($transfer->id, 0))->failed(new RuntimeException('Storage unavailable.'));

    expect($transfer->fresh()->status)->toBe('failed');
    expect($transfer->fresh()->error_code)->toBe('processing_failed');
    Storage::disk('local')->assertMissing($transfer->path);
    Event::assertDispatched(ExamCsvFinished::class);

    $completed = ExamCsvTransfer::factory()->create(['status' => 'completed']);
    (new ProcessExamCsv($completed->id, 0))->failed(new RuntimeException('Delayed failure.'));
    expect($completed->fresh()->status)->toBe('completed');
});

it('deduplicates the same checkpoint without suppressing independent transfers', function () {
    Queue::fake();
    $first = ExamCsvTransfer::factory()->create();
    $second = ExamCsvTransfer::factory()->create();
    ProcessExamCsv::dispatch($first->id, 0);
    ProcessExamCsv::dispatch($first->id, 0);
    ProcessExamCsv::dispatch($second->id, 0);
    ProcessExamCsv::dispatch($first->id, 1);
    Queue::assertPushed(ProcessExamCsv::class, 3);
});

it('refuses transfers until the user has a medical file', function () {
    Queue::fake();
    $this->actingAs(User::factory()->create())->postJson(route('exams.csv.export', 'glucose'))
        ->assertUnprocessable()->assertJsonPath('message', __('exam_csv.medical_file_required'));
    Queue::assertNothingPushed();
});

it('authorizes only the owner of the private broadcast channel', function () {
    config([
        'broadcasting.default' => 'reverb',
        'broadcasting.connections.reverb.key' => 'test-key',
        'broadcasting.connections.reverb.secret' => 'test-secret',
        'broadcasting.connections.reverb.app_id' => 'test-app',
    ]);
    require base_path('routes/channels.php');
    $user = User::factory()->create();
    $other = User::factory()->create();
    $payload = ['channel_name' => 'private-App.Models.User.'.$user->id, 'socket_id' => '123.456'];
    $this->actingAs($other)->postJson('/broadcasting/auth', $payload)->assertForbidden();
    $this->actingAs($user)->postJson('/broadcasting/auth', $payload)->assertOk()->assertJsonStructure(['auth']);
});
