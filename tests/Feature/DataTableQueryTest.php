<?php

use App\Models\Exams\Glucose;
use App\Models\Fitness\MuscleGroup;
use App\Models\Fitness\Workout;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->withoutVite();
    $this->travelTo('2026-08-10 12:00:00');
});

it('applies numeric operators before pagination', function (string $operator, mixed $value, array $expected) {
    $user = User::factory()->create();
    $file = $user->medicalFile()->create();
    foreach ([0, 10, 20] as $level) {
        Glucose::factory()->for($file)->create(['glucose_level' => $level]);
    }
    Glucose::factory()->create(['glucose_level' => 10]);

    $this->actingAs($user)->get(route('glucose.index', [
        'filters' => json_encode([['id' => 'glucose_level', 'operator' => $operator, 'value' => $value]]),
        'sorting' => json_encode([['id' => 'glucose_level', 'desc' => false]]),
    ]))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('glucoses.total', count($expected))
        ->where('glucoses.data', fn ($rows) => collect($rows)->pluck('glucose_level')->map(fn ($value) => (float) $value)->all() === $expected)
    );
})->with([
    'equals zero' => ['equals', '0', [0.0]],
    'not equal' => ['notEquals', 10, [0.0, 20.0]],
    'less' => ['lessThan', 10, [0.0]],
    'less or equal' => ['lessThanOrEqualTo', 10, [0.0, 10.0]],
    'greater' => ['greaterThan', 10, [20.0]],
    'greater or equal' => ['greaterThanOrEqualTo', 10, [10.0, 20.0]],
    'range including zero' => ['inRange', [0, 10], [0.0, 10.0]],
    'open start' => ['inRange', ['', 10], [0.0, 10.0]],
    'open end' => ['inRange', [10, ''], [10.0, 20.0]],
]);

it('compares calendar days including the entire final day', function (string $operator, mixed $value, array $expected) {
    $user = User::factory()->create();
    $file = $user->medicalFile()->create();
    foreach (['2026-08-09 23:59:59', '2026-08-10 23:59:59', '2026-08-11 00:00:00'] as $index => $date) {
        Glucose::factory()->for($file)->create(['glucose_level' => $index, 'created_at' => $date]);
    }
    $this->actingAs($user)->get(route('glucose.index', [
        'filters' => json_encode([['id' => 'created_at', 'operator' => $operator, 'value' => $value]]),
    ]))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('glucoses.data', fn ($rows) => collect($rows)->pluck('glucose_level')->map(fn ($value) => (int) $value)->all() === $expected)
    );
})->with([
    'same day' => ['equals', '2026-08-10', [1]],
    'different day' => ['notEquals', '2026-08-10', [0, 2]],
    'before' => ['lessThan', '2026-08-10', [0]],
    'on or before' => ['lessThanOrEqualTo', '2026-08-10', [0, 1]],
    'after' => ['greaterThan', '2026-08-10', [2]],
    'on or after' => ['greaterThanOrEqualTo', '2026-08-10', [1, 2]],
    'inclusive range' => ['inRange', ['2026-08-09', '2026-08-10'], [0, 1]],
    'relative day' => ['isRelativeToToday', 0, [1]],
    'not empty' => ['isNotEmpty', '', [0, 1, 2]],
    'empty' => ['isEmpty', '', []],
]);

it('applies text operators and treats delimiters and wildcard characters literally', function (string $operator, string $value, array $expected) {
    $user = User::factory()->create();
    foreach (['Alpha', 'Beta', '50%_!,: test'] as $name) {
        MuscleGroup::factory()->for($user)->create(['name' => $name]);
    }
    $this->actingAs($user)->get(route('muscle-groups.index', [
        'filters' => json_encode([['id' => 'name', 'operator' => $operator, 'value' => $value]]),
    ]))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('muscleGroups.data', fn ($rows) => collect($rows)->pluck('name')->all() === $expected)
    );
})->with([
    'contains' => ['includesString', 'ALP', ['Alpha']],
    'not contains' => ['notIncludesString', 'a', ['50%_!,: test']],
    'starts' => ['startsWith', 'Al', ['Alpha']],
    'ends' => ['endsWith', 'ta', ['Beta']],
    'equals' => ['equalsString', 'alpha', ['Alpha']],
    'not equals' => ['notEqualsString', 'alpha', ['Beta', '50%_!,: test']],
    'literal wildcard' => ['includesString', '%_!,:', ['50%_!,: test']],
    'empty' => ['isEmpty', '', []],
    'not empty' => ['isNotEmpty', '', ['Alpha', 'Beta', '50%_!,: test']],
]);

it('groups OR filters inside ownership and search constraints', function () {
    $user = User::factory()->create();
    MuscleGroup::factory()->for($user)->create(['name' => 'Target Alpha']);
    MuscleGroup::factory()->for($user)->create(['name' => 'Target Beta']);
    MuscleGroup::factory()->for($user)->create(['name' => 'Excluded Beta']);
    MuscleGroup::factory()->create(['name' => 'Target Beta']);
    $this->actingAs($user)->get(route('muscle-groups.index', [
        'search' => 'Target',
        'filters' => json_encode([
            ['id' => 'name', 'operator' => 'endsWith', 'value' => 'Alpha', 'joinOperator' => 'or'],
            ['id' => 'name', 'operator' => 'endsWith', 'value' => 'Beta', 'joinOperator' => 'or'],
        ]),
    ]))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->has('muscleGroups.data', 2)
        ->where('muscleGroups.data.0.name', 'Target Alpha')
        ->where('muscleGroups.data.1.name', 'Target Beta')
    );
});

it('combines repeated column filters with AND and sorts before paging with stable ties', function () {
    $user = User::factory()->create();
    $file = $user->medicalFile()->create();
    $records = [];
    foreach ([[20, 5], [10, 8], [10, 8], [10, 4], [30, 2]] as [$level, $hemoglobin]) {
        $records[] = Glucose::factory()->for($file)->create(['glucose_level' => $level, 'glycated_hemoglobin' => $hemoglobin]);
    }
    $expected = $records[2];
    $this->actingAs($user)->get(route('glucose.index', [
        'filters' => json_encode([
            ['id' => 'glucose_level', 'operator' => 'greaterThanOrEqualTo', 'value' => 10],
            ['id' => 'glucose_level', 'operator' => 'lessThanOrEqualTo', 'value' => 20],
        ]),
        'sorting' => json_encode([['id' => 'glucose_level', 'desc' => false], ['id' => 'glycated_hemoglobin', 'desc' => true]]),
        'per_page' => 1, 'page' => 2,
    ]))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('glucoses.total', 4)
        ->where('glucoses.current_page', 2)
        ->where('glucoses.data.0.id', $expected->id)
    );
});

it('filters boolean selections including zero without exposing another owner', function (string $operator, array $values, array $expected) {
    $user = User::factory()->create();
    Workout::factory()->for($user)->create(['goal' => 'Active', 'is_active' => true]);
    Workout::factory()->for($user)->create(['goal' => 'Inactive', 'is_active' => false]);
    Workout::factory()->create(['goal' => 'Other owner', 'is_active' => false]);
    $this->actingAs($user)->get(route('workouts.index', [
        'filters' => json_encode([['id' => 'is_active', 'operator' => $operator, 'value' => $values]]),
        'sorting' => json_encode([['id' => 'id', 'desc' => false]]),
    ]))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('workouts.data', fn ($rows) => collect($rows)->pluck('goal')->all() === $expected)
    );
})->with([
    'false' => ['equals', ['0'], ['Inactive']],
    'true' => ['equals', ['1'], ['Active']],
    'both' => ['equals', ['0', '1'], ['Active', 'Inactive']],
    'not false' => ['notEquals', ['0'], ['Active']],
    'neither' => ['notEquals', ['0', '1'], []],
]);

it('distinguishes null dates from populated dates', function (string $operator, string $expected) {
    $user = User::factory()->create();
    Workout::factory()->for($user)->create(['goal' => 'No date', 'next_change_at' => null]);
    Workout::factory()->for($user)->create(['goal' => 'Dated', 'next_change_at' => '2026-09-01']);
    $this->actingAs($user)->get(route('workouts.index', [
        'filters' => json_encode([['id' => 'next_change_at', 'operator' => $operator, 'value' => '']]),
    ]))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->has('workouts.data', 1)->where('workouts.data.0.goal', $expected)
    );
})->with([['isEmpty', 'No date'], ['isNotEmpty', 'Dated']]);

it('rejects invalid filters before SQL executes', function (array $filter, string $field) {
    $record = Glucose::factory()->create();
    $this->actingAs($record->medicalFile->user)->get(route('glucose.index', [
        'filters' => json_encode([$filter]),
    ]))->assertSessionHasErrors([$field]);
})->with([
    'unknown column' => [['id' => 'password', 'operator' => 'equals', 'value' => 1], 'filters.0.id'],
    'injected column' => [['id' => 'id) OR 1=1', 'operator' => 'equals', 'value' => 1], 'filters.0.id'],
    'unknown operator' => [['id' => 'glucose_level', 'operator' => 'raw', 'value' => 1], 'filters.0.operator'],
    'incompatible operator' => [['id' => 'glucose_level', 'operator' => 'includesString', 'value' => '1'], 'filters.0.operator'],
    'invalid date' => [['id' => 'report_date', 'operator' => 'equals', 'value' => '2026-02-30'], 'filters.0.value'],
    'invalid numeric' => [['id' => 'glucose_level', 'operator' => 'equals', 'value' => 'text'], 'filters.0.value'],
    'missing range bound' => [['id' => 'glucose_level', 'operator' => 'inRange', 'value' => [1]], 'filters.0.value'],
    'empty range' => [['id' => 'glucose_level', 'operator' => 'inRange', 'value' => ['', '']], 'filters.0.value.0'],
    'nested value' => [['id' => 'glucose_level', 'operator' => 'equals', 'value' => ['sql' => 1]], 'filters.0.value'],
]);

it('rejects malformed table state and unsafe sort identifiers', function (array $query, string $field) {
    $user = User::factory()->create();
    $this->actingAs($user)->get(route('muscle-groups.index', $query))->assertSessionHasErrors([$field]);
})->with([
    'invalid JSON' => [['filters' => '[bad'], 'filters'],
    'old delimiter format' => [['filters' => 'name:test'], 'filters'],
    'non-list filters' => [['filters' => ['name' => 'test']], 'filters'],
    'invalid sorting' => [['sorting' => '[bad'], 'sorting'],
    'unsafe sorting' => [['sorting' => [['id' => 'id desc; drop table users', 'desc' => false]]], 'sorting.0.id'],
    'invalid direction' => [['sorting' => [['id' => 'id', 'desc' => 'desc']]], 'sorting.0.desc'],
    'unbounded page size' => [['per_page' => 10000], 'per_page'],
]);

it('rejects fractional relationship counts instead of rounding the filter', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get(route('muscle-groups.index', [
        'filters' => json_encode([['id' => 'exercises_count', 'operator' => 'equals', 'value' => 1.5]]),
    ]))->assertSessionHasErrors(['filters.0.value' => 'The filters.0.value field must be an integer.']);
});
