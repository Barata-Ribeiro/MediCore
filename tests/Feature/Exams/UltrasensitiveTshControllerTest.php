<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia;

describe('tests for the "index" method of UltrasensitiveTshController', function () {
    $componentName = 'exams/ultrasensitive-tsh/index';

    it('should return empty data if no ultrasensitive tsh record exists', function () use ($componentName) {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $response = $this->actingAs($user)->get(route('ultrasensitive-tsh.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component($componentName)
                ->where('lang.ultrasensitive_tsh_pages.index.head_title', 'Ultrasensitive TSH Exams')
                ->has('ultrasensitiveTshs.data', 0)
                ->has('chartData')
        );
    });

    it('should return successful response with ultrasensitive tsh data', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $medicalFile->ultrasensitiveTshs()->create([
            'tsh_level' => 2.35,
            'report_date' => now()->toDateString(),
        ]);

        $medicalFile->ultrasensitiveTshs()->create([
            'tsh_level' => 1.82,
            'report_date' => now()->subDays(30)->toDateString(),
        ]);

        $today = now()->toDateString();
        $thirtyDaysAgo = now()->subDays(30)->toDateString();

        $response = $this->actingAs($user)->get(route('ultrasensitive-tsh.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->has('ultrasensitiveTshs.data', 2)
            ->has('chartData')
            ->has('ultrasensitiveTshs.data.0', fn (AssertableInertia $item) => $item
                ->where('tsh_level', 2.35)
                ->where('report_date', $today)
                ->etc()
            )
            ->has('ultrasensitiveTshs.data.1', fn (AssertableInertia $item) => $item
                ->where('tsh_level', 1.82)
                ->where('report_date', $thirtyDaysAgo)
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('ultrasensitive-tsh.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "create" method of UltrasensitiveTshController', function () {
    $componentName = 'exams/ultrasensitive-tsh/create';

    it('should return the create view for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('ultrasensitive-tsh.create'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.ultrasensitive_tsh_pages.create.head_title', 'Create Ultrasensitive TSH record')
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('ultrasensitive-tsh.create'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "store" method of UltrasensitiveTshController', function () {
    it('should store a new ultrasensitive tsh record and redirect to index', function () {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $data = [
            'tsh_level' => 2.64,
            'report_date' => now()->toDateString(),
        ];

        $response = $this->actingAs($user)->post(route('ultrasensitive-tsh.store'), $data);

        $response->assertRedirect(route('ultrasensitive-tsh.index'));
        $this->assertDatabaseHas('ultrasensitive_tshs', [...$data, 'medical_file_id' => $user->medicalFile->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->post(route('ultrasensitive-tsh.store'), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "edit" method of UltrasensitiveTshController', function () {
    $componentName = 'exams/ultrasensitive-tsh/edit';

    it('should return the edit view with ultrasensitive tsh data for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $ultrasensitiveTsh = $medicalFile->ultrasensitiveTshs()->create([
            'tsh_level' => 3.17,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('ultrasensitive-tsh.edit', $ultrasensitiveTsh));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.ultrasensitive_tsh_pages.edit.head_title', 'Edit Ultrasensitive TSH record')
            ->has('ultrasensitiveTsh', fn (AssertableInertia $item) => $item
                ->where('tsh_level', 3.17)
                ->where('report_date', now()->toDateString())
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('ultrasensitive-tsh.edit', 1));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "update" method of UltrasensitiveTshController', function () {
    it('should update the ultrasensitive tsh record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $ultrasensitiveTsh = $medicalFile->ultrasensitiveTshs()->create([
            'tsh_level' => 1.56,
            'report_date' => now()->toDateString(),
        ]);

        $updatedData = [
            'tsh_level' => 2.91,
            'report_date' => now()->subDays(15)->toDateString(),
        ];

        $response = $this->actingAs($user)->put(route('ultrasensitive-tsh.update', $ultrasensitiveTsh), $updatedData);

        $response->assertRedirect(route('ultrasensitive-tsh.index'));
        $this->assertDatabaseHas('ultrasensitive_tshs', [...$updatedData, 'id' => $ultrasensitiveTsh->id]);
    });

    it('should not update an ultrasensitive tsh record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $ultrasensitiveTsh = $ownerMedicalFile->ultrasensitiveTshs()->create([
            'tsh_level' => 1.24,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $updatedData = [
            'tsh_level' => 3.05,
            'report_date' => now()->subDays(10)->toDateString(),
        ];

        $response = $this->from(route('ultrasensitive-tsh.index'))
            ->actingAs($anotherUser)
            ->put(route('ultrasensitive-tsh.update', $ultrasensitiveTsh), $updatedData);

        $response->assertRedirect(route('ultrasensitive-tsh.index'));
        $this->assertDatabaseHas('ultrasensitive_tshs', [
            'id' => $ultrasensitiveTsh->id,
            'tsh_level' => 1.24,
            'report_date' => now()->toDateString(),
        ]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->put(route('ultrasensitive-tsh.update', 1), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "destroy" method of UltrasensitiveTshController', function () {
    it('should delete the ultrasensitive tsh record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $ultrasensitiveTsh = $medicalFile->ultrasensitiveTshs()->create([
            'tsh_level' => 2.48,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->delete(route('ultrasensitive-tsh.destroy', $ultrasensitiveTsh));

        $response->assertRedirect(route('ultrasensitive-tsh.index'));
        $this->assertDatabaseMissing('ultrasensitive_tshs', ['id' => $ultrasensitiveTsh->id]);
    });

    it('should not delete an ultrasensitive tsh record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $ultrasensitiveTsh = $ownerMedicalFile->ultrasensitiveTshs()->create([
            'tsh_level' => 1.73,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $response = $this->from(route('ultrasensitive-tsh.index'))
            ->actingAs($anotherUser)
            ->delete(route('ultrasensitive-tsh.destroy', $ultrasensitiveTsh));

        $response->assertRedirect(route('ultrasensitive-tsh.index'));
        $this->assertDatabaseHas('ultrasensitive_tshs', ['id' => $ultrasensitiveTsh->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->delete(route('ultrasensitive-tsh.destroy', 1));

        $response->assertRedirect(route('login'));
    });
});
