<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia;

describe('tests for the "index" method of UricAcidController', function () {
    $componentName = 'exams/uric-acid/index';

    it('should return empty data if no uric acid record exists', function () use ($componentName) {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $response = $this->actingAs($user)->get(route('uric-acid.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component($componentName)
                ->has('uricAcids.data', 0)
                ->has('chartData')
        );
    });

    it('should return successful response with uric acid data', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $medicalFile->uricAcids()->create([
            'uric_acid_level' => 6.8,
            'report_date' => now()->toDateString(),
        ]);

        $medicalFile->uricAcids()->create([
            'uric_acid_level' => 5.9,
            'report_date' => now()->subDays(30)->toDateString(),
        ]);

        $today = now()->toDateString();
        $thirtyDaysAgo = now()->subDays(30)->toDateString();

        $response = $this->actingAs($user)->get(route('uric-acid.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->has('uricAcids.data', 2)
            ->has('chartData')
            ->has('uricAcids.data.0', fn (AssertableInertia $item) => $item
                ->where('uric_acid_level', 6.8)
                ->where('report_date', $today)
                ->etc()
            )
            ->has('uricAcids.data.1', fn (AssertableInertia $item) => $item
                ->where('uric_acid_level', 5.9)
                ->where('report_date', $thirtyDaysAgo)
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('uric-acid.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "create" method of UricAcidController', function () {
    $componentName = 'exams/uric-acid/create';

    it('should return the create view for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('uric-acid.create'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName));
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('uric-acid.create'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "store" method of UricAcidController', function () {
    it('should store a new uric acid record and redirect to index', function () {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $data = [
            'uric_acid_level' => 7.1,
            'report_date' => now()->toDateString(),
        ];

        $response = $this->actingAs($user)->post(route('uric-acid.store'), $data);

        $response->assertRedirect(route('uric-acid.index'));
        $this->assertDatabaseHas('uric_acids', [...$data, 'medical_file_id' => $user->medicalFile->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->post(route('uric-acid.store'), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "edit" method of UricAcidController', function () {
    $componentName = 'exams/uric-acid/edit';

    it('should return the edit view with uric acid data for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $uricAcid = $medicalFile->uricAcids()->create([
            'uric_acid_level' => 6.4,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('uric-acid.edit', $uricAcid));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->has('uricAcid', fn (AssertableInertia $item) => $item
                ->where('uric_acid_level', 6.4)
                ->where('report_date', now()->toDateString())
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('uric-acid.edit', 1));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "update" method of UricAcidController', function () {
    it('should update the uric acid record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $uricAcid = $medicalFile->uricAcids()->create([
            'uric_acid_level' => 5.8,
            'report_date' => now()->toDateString(),
        ]);

        $updatedData = [
            'uric_acid_level' => 7.4,
            'report_date' => now()->subDays(15)->toDateString(),
        ];

        $response = $this->actingAs($user)->put(route('uric-acid.update', $uricAcid), $updatedData);

        $response->assertRedirect(route('uric-acid.index'));
        $this->assertDatabaseHas('uric_acids', [...$updatedData, 'id' => $uricAcid->id]);
    });

    it('should not update a uric acid record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $uricAcid = $ownerMedicalFile->uricAcids()->create([
            'uric_acid_level' => 6.2,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $updatedData = [
            'uric_acid_level' => 8.1,
            'report_date' => now()->subDays(10)->toDateString(),
        ];

        $response = $this->from(route('uric-acid.index'))
            ->actingAs($anotherUser)
            ->put(route('uric-acid.update', $uricAcid), $updatedData);

        $response->assertRedirect(route('uric-acid.index'));
        $this->assertDatabaseHas('uric_acids', [
            'id' => $uricAcid->id,
            'uric_acid_level' => 6.2,
            'report_date' => now()->toDateString(),
        ]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->put(route('uric-acid.update', 1), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "destroy" method of UricAcidController', function () {
    it('should delete the uric acid record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $uricAcid = $medicalFile->uricAcids()->create([
            'uric_acid_level' => 6.7,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->delete(route('uric-acid.destroy', $uricAcid));

        $response->assertRedirect(route('uric-acid.index'));
        $this->assertDatabaseMissing('uric_acids', ['id' => $uricAcid->id]);
    });

    it('should not delete a uric acid record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $uricAcid = $ownerMedicalFile->uricAcids()->create([
            'uric_acid_level' => 5.6,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $response = $this->from(route('uric-acid.index'))
            ->actingAs($anotherUser)
            ->delete(route('uric-acid.destroy', $uricAcid));

        $response->assertRedirect(route('uric-acid.index'));
        $this->assertDatabaseHas('uric_acids', ['id' => $uricAcid->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->delete(route('uric-acid.destroy', 1));

        $response->assertRedirect(route('login'));
    });
});
