<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia;

describe('tests for the "index" method of VitaminB12Controller', function () {
    $componentName = 'exams/vitamin-b12/index';

    it('should return empty data if no vitamin b12 record exists', function () use ($componentName) {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $response = $this->actingAs($user)->get(route('vitamin-b12.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component($componentName)
                ->has('vitaminB12s.data', 0)
                ->has('chartData')
        );
    });

    it('should return successful response with vitamin b12 data', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $medicalFile->vitaminB12s()->create([
            'vitamin_b12_level' => 325,
            'report_date' => now()->toDateString(),
        ]);

        $medicalFile->vitaminB12s()->create([
            'vitamin_b12_level' => 281,
            'report_date' => now()->subDays(30)->toDateString(),
        ]);

        $today = now()->toDateString();
        $thirtyDaysAgo = now()->subDays(30)->toDateString();

        $response = $this->actingAs($user)->get(route('vitamin-b12.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->has('vitaminB12s.data', 2)
            ->has('chartData')
            ->has('vitaminB12s.data.0', fn (AssertableInertia $item) => $item
                ->where('vitamin_b12_level', 325)
                ->where('report_date', $today)
                ->etc()
            )
            ->has('vitaminB12s.data.1', fn (AssertableInertia $item) => $item
                ->where('vitamin_b12_level', 281)
                ->where('report_date', $thirtyDaysAgo)
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->get(route('vitamin-b12.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "create" method of VitaminB12Controller', function () {
    $componentName = 'exams/vitamin-b12/create';

    it('should return the create view for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('vitamin-b12.create'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName));
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->get(route('vitamin-b12.create'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "store" method of VitaminB12Controller', function () {
    it('should store a new vitamin b12 record and redirect to index', function () {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $data = [
            'vitamin_b12_level' => 354,
            'report_date' => now()->toDateString(),
        ];

        $response = $this->actingAs($user)->post(route('vitamin-b12.store'), $data);

        $response->assertRedirect(route('vitamin-b12.index'));
        $this->assertDatabaseHas('vitamin_b12s', [...$data, 'medical_file_id' => $user->medicalFile->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->post(route('vitamin-b12.store'), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "edit" method of VitaminB12Controller', function () {
    $componentName = 'exams/vitamin-b12/edit';

    it('should return the edit view with vitamin b12 data for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $vitaminB12 = $medicalFile->vitaminB12s()->create([
            'vitamin_b12_level' => 317,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('vitamin-b12.edit', $vitaminB12));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->has('vitaminB12', fn (AssertableInertia $item) => $item
                ->where('vitamin_b12_level', 317)
                ->where('report_date', now()->toDateString())
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->get(route('vitamin-b12.edit', 1));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "update" method of VitaminB12Controller', function () {
    it('should update the vitamin b12 record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $vitaminB12 = $medicalFile->vitaminB12s()->create([
            'vitamin_b12_level' => 262,
            'report_date' => now()->toDateString(),
        ]);

        $updatedData = [
            'vitamin_b12_level' => 398,
            'report_date' => now()->subDays(15)->toDateString(),
        ];

        $response = $this->actingAs($user)->put(route('vitamin-b12.update', $vitaminB12), $updatedData);

        $response->assertRedirect(route('vitamin-b12.index'));
        $this->assertDatabaseHas('vitamin_b12s', [...$updatedData, 'id' => $vitaminB12->id]);
    });

    it('should not update a vitamin b12 record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $vitaminB12 = $ownerMedicalFile->vitaminB12s()->create([
            'vitamin_b12_level' => 225,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $updatedData = [
            'vitamin_b12_level' => 450,
            'report_date' => now()->subDays(10)->toDateString(),
        ];

        $response = $this->from(route('vitamin-b12.index'))
            ->actingAs($anotherUser)
            ->put(route('vitamin-b12.update', $vitaminB12), $updatedData);

        $response->assertRedirect(route('vitamin-b12.index'));
        $this->assertDatabaseHas('vitamin_b12s', [
            'id' => $vitaminB12->id,
            'vitamin_b12_level' => 225,
            'report_date' => now()->toDateString(),
        ]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->put(route('vitamin-b12.update', 1), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "destroy" method of VitaminB12Controller', function () {
    it('should delete the vitamin b12 record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $vitaminB12 = $medicalFile->vitaminB12s()->create([
            'vitamin_b12_level' => 294,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->delete(route('vitamin-b12.destroy', $vitaminB12));

        $response->assertRedirect(route('vitamin-b12.index'));
        $this->assertDatabaseMissing('vitamin_b12s', ['id' => $vitaminB12->id]);
    });

    it('should not delete a vitamin b12 record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $vitaminB12 = $ownerMedicalFile->vitaminB12s()->create([
            'vitamin_b12_level' => 246,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $response = $this->from(route('vitamin-b12.index'))
            ->actingAs($anotherUser)
            ->delete(route('vitamin-b12.destroy', $vitaminB12));

        $response->assertRedirect(route('vitamin-b12.index'));
        $this->assertDatabaseHas('vitamin_b12s', ['id' => $vitaminB12->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->delete(route('vitamin-b12.destroy', 1));

        $response->assertRedirect(route('login'));
    });
});
