<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia;

describe('tests for the "index" method of UreaAndCreatinineController', function () {
    $componentName = 'exams/urea-and-creatinine/index';

    it('should return empty data if no urea and creatinine record exists', function () use ($componentName) {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $response = $this->actingAs($user)->get(route('urea-and-creatinine.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component($componentName)
                ->has('ureaAndCreatinines.data', 0)
                ->has('chartData')
        );
    });

    it('should return successful response with urea and creatinine data', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $medicalFile->ureaAndCreatinines()->create([
            'urea_level' => 32.5,
            'creatinine_level' => 1.1,
            'report_date' => now()->toDateString(),
        ]);

        $medicalFile->ureaAndCreatinines()->create([
            'urea_level' => 28.4,
            'creatinine_level' => 0.9,
            'report_date' => now()->subDays(30)->toDateString(),
        ]);

        $today = now()->toDateString();
        $thirtyDaysAgo = now()->subDays(30)->toDateString();

        $response = $this->actingAs($user)->get(route('urea-and-creatinine.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->has('ureaAndCreatinines.data', 2)
            ->has('chartData')
            ->has('ureaAndCreatinines.data.0', fn (AssertableInertia $item) => $item
                ->where('urea_level', 32.5)
                ->where('creatinine_level', 1.1)
                ->where('report_date', $today)
                ->etc()
            )
            ->has('ureaAndCreatinines.data.1', fn (AssertableInertia $item) => $item
                ->where('urea_level', 28.4)
                ->where('creatinine_level', 0.9)
                ->where('report_date', $thirtyDaysAgo)
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('urea-and-creatinine.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "create" method of UreaAndCreatinineController', function () {
    $componentName = 'exams/urea-and-creatinine/create';

    it('should return the create view for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('urea-and-creatinine.create'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName));
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('urea-and-creatinine.create'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "store" method of UreaAndCreatinineController', function () {
    it('should store a new urea and creatinine record and redirect to index', function () {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $data = [
            'urea_level' => 34.8,
            'creatinine_level' => 1.2,
            'report_date' => now()->toDateString(),
        ];

        $response = $this->actingAs($user)->post(route('urea-and-creatinine.store'), $data);

        $response->assertRedirect(route('urea-and-creatinine.index'));
        $this->assertDatabaseHas('urea_and_creatinines', [...$data, 'medical_file_id' => $user->medicalFile->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->post(route('urea-and-creatinine.store'), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "edit" method of UreaAndCreatinineController', function () {
    $componentName = 'exams/urea-and-creatinine/edit';

    it('should return the edit view with urea and creatinine data for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $ureaAndCreatinine = $medicalFile->ureaAndCreatinines()->create([
            'urea_level' => 30.7,
            'creatinine_level' => 1.0,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('urea-and-creatinine.edit', $ureaAndCreatinine));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->has('ureaAndCreatinine', fn (AssertableInertia $item) => $item
                ->where('urea_level', 30.7)
                ->where('creatinine_level', 1)
                ->where('report_date', now()->toDateString())
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('urea-and-creatinine.edit', 1));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "update" method of UreaAndCreatinineController', function () {
    it('should update the urea and creatinine record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $ureaAndCreatinine = $medicalFile->ureaAndCreatinines()->create([
            'urea_level' => 27.3,
            'creatinine_level' => 0.8,
            'report_date' => now()->toDateString(),
        ]);

        $updatedData = [
            'urea_level' => 38.1,
            'creatinine_level' => 1.3,
            'report_date' => now()->subDays(15)->toDateString(),
        ];

        $response = $this->actingAs($user)->put(route('urea-and-creatinine.update', $ureaAndCreatinine), $updatedData);

        $response->assertRedirect(route('urea-and-creatinine.index'));
        $this->assertDatabaseHas('urea_and_creatinines', [...$updatedData, 'id' => $ureaAndCreatinine->id]);
    });

    it('should not update a urea and creatinine record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $ureaAndCreatinine = $ownerMedicalFile->ureaAndCreatinines()->create([
            'urea_level' => 25.6,
            'creatinine_level' => 0.7,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $updatedData = [
            'urea_level' => 41.0,
            'creatinine_level' => 1.4,
            'report_date' => now()->subDays(10)->toDateString(),
        ];

        $response = $this->from(route('urea-and-creatinine.index'))
            ->actingAs($anotherUser)
            ->put(route('urea-and-creatinine.update', $ureaAndCreatinine), $updatedData);

        $response->assertRedirect(route('urea-and-creatinine.index'));
        $this->assertDatabaseHas('urea_and_creatinines', [
            'id' => $ureaAndCreatinine->id,
            'urea_level' => 25.6,
            'creatinine_level' => 0.7,
            'report_date' => now()->toDateString(),
        ]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->put(route('urea-and-creatinine.update', 1), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "destroy" method of UreaAndCreatinineController', function () {
    it('should delete the urea and creatinine record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $ureaAndCreatinine = $medicalFile->ureaAndCreatinines()->create([
            'urea_level' => 29.9,
            'creatinine_level' => 1.1,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->delete(route('urea-and-creatinine.destroy', $ureaAndCreatinine));

        $response->assertRedirect(route('urea-and-creatinine.index'));
        $this->assertDatabaseMissing('urea_and_creatinines', ['id' => $ureaAndCreatinine->id]);
    });

    it('should not delete a urea and creatinine record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $ureaAndCreatinine = $ownerMedicalFile->ureaAndCreatinines()->create([
            'urea_level' => 24.2,
            'creatinine_level' => 0.9,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $response = $this->from(route('urea-and-creatinine.index'))
            ->actingAs($anotherUser)
            ->delete(route('urea-and-creatinine.destroy', $ureaAndCreatinine));

        $response->assertRedirect(route('urea-and-creatinine.index'));
        $this->assertDatabaseHas('urea_and_creatinines', ['id' => $ureaAndCreatinine->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->delete(route('urea-and-creatinine.destroy', 1));

        $response->assertRedirect(route('login'));
    });
});
