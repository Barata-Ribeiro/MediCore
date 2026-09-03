<?php

use App\Models\Exams\TotalProteinsAndFractions;

it('calculates the albumin to globulin ratio', function () {
    $record = new TotalProteinsAndFractions([
        'albumin' => 4.2,
        'globulin' => 2.1,
    ]);

    expect($record->albumin_globulin_ratio)->toBe(2.0);
});

it('does not calculate a ratio from non-positive values', function (float $albumin, float $globulin) {
    $record = new TotalProteinsAndFractions([
        'albumin' => $albumin,
        'globulin' => $globulin,
    ]);

    expect($record->albumin_globulin_ratio)->toBeNull();
})->with([
    'zero albumin' => [0.0, 2.1],
    'negative albumin' => [-1.0, 2.1],
    'zero globulin' => [4.2, 0.0],
    'negative globulin' => [4.2, -1.0],
]);
