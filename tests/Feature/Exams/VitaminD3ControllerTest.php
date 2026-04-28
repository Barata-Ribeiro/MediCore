<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia;

describe('tests for the "index" method of VitaminD3Controller', function () {
    $componentName = 'exams/vitamin-d3/index';

    it('should return empty data if no vitamin d3 record exists', function () use ($componentName) {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $response = $this->actingAs($user)->get(route('vitamin-d3.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page->component($componentName)
                ->where('lang.vitamin_d3_pages.index.head_title', 'Vitamin D3 Exams')
                ->has('vitaminD3s.data', 0)
                ->has('chartData')
        );
    });

    it('should return successful response with vitamin d3 data', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $medicalFile->vitaminD3s()->create([
            'twenty_five_hydroxyvitamin_d3' => 32.5,
            'report_date' => now()->toDateString(),
        ]);

        $medicalFile->vitaminD3s()->create([
            'twenty_five_hydroxyvitamin_d3' => 28.1,
            'report_date' => now()->subDays(30)->toDateString(),
        ]);

        $today = now()->toDateString();
        $thirtyDaysAgo = now()->subDays(30)->toDateString();

        $response = $this->actingAs($user)->get(route('vitamin-d3.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->has('vitaminD3s.data', 2)
            ->has('chartData')
            ->has('vitaminD3s.data.0', fn (AssertableInertia $item) => $item
                ->where('twenty_five_hydroxyvitamin_d3', 32.5)
                ->where('report_date', $today)
                ->etc()
            )
            ->has('vitaminD3s.data.1', fn (AssertableInertia $item) => $item
                ->where('twenty_five_hydroxyvitamin_d3', 28.1)
                ->where('report_date', $thirtyDaysAgo)
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('vitamin-d3.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "create" method of VitaminD3Controller', function () {
    $componentName = 'exams/vitamin-d3/create';

    it('should return the create view for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('vitamin-d3.create'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.vitamin_d3_pages.create.head_title', 'Create Vitamin D3 record')
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('vitamin-d3.create'));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "store" method of VitaminD3Controller', function () {
    it('should store a new vitamin d3 record and redirect to index', function () {
        $user = User::factory()->create();
        $user->medicalFile()->create();

        $data = [
            'twenty_five_hydroxyvitamin_d3' => 35.4,
            'report_date' => now()->toDateString(),
        ];

        $response = $this->actingAs($user)->post(route('vitamin-d3.store'), $data);

        $response->assertRedirect(route('vitamin-d3.index'));
        $this->assertDatabaseHas('vitamin_d3_s', [...$data, 'medical_file_id' => $user->medicalFile->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->post(route('vitamin-d3.store'), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "edit" method of VitaminD3Controller', function () {
    $componentName = 'exams/vitamin-d3/edit';

    it('should return the edit view with vitamin d3 data for authenticated users', function () use ($componentName) {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $vitaminD3 = $medicalFile->vitaminD3s()->create([
            'twenty_five_hydroxyvitamin_d3' => 31.7,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('vitamin-d3.edit', $vitaminD3));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component($componentName)
            ->where('lang.vitamin_d3_pages.edit.head_title', 'Edit Vitamin D3 record')
            ->has('vitaminD3', fn (AssertableInertia $item) => $item
                ->where('twenty_five_hydroxyvitamin_d3', 31.7)
                ->where('report_date', now()->toDateString())
                ->etc()
            )
        );
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->get(route('vitamin-d3.edit', 1));

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "update" method of VitaminD3Controller', function () {
    it('should update the vitamin d3 record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $vitaminD3 = $medicalFile->vitaminD3s()->create([
            'twenty_five_hydroxyvitamin_d3' => 26.2,
            'report_date' => now()->toDateString(),
        ]);

        $updatedData = [
            'twenty_five_hydroxyvitamin_d3' => 39.8,
            'report_date' => now()->subDays(15)->toDateString(),
        ];

        $response = $this->actingAs($user)->put(route('vitamin-d3.update', $vitaminD3), $updatedData);

        $response->assertRedirect(route('vitamin-d3.index'));
        $this->assertDatabaseHas('vitamin_d3_s', [...$updatedData, 'id' => $vitaminD3->id]);
    });

    it('should not update a vitamin d3 record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $vitaminD3 = $ownerMedicalFile->vitaminD3s()->create([
            'twenty_five_hydroxyvitamin_d3' => 22.5,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $updatedData = [
            'twenty_five_hydroxyvitamin_d3' => 45.0,
            'report_date' => now()->subDays(10)->toDateString(),
        ];

        $response = $this->from(route('vitamin-d3.index'))
            ->actingAs($anotherUser)
            ->put(route('vitamin-d3.update', $vitaminD3), $updatedData);

        $response->assertRedirect(route('vitamin-d3.index'));
        $this->assertDatabaseHas('vitamin_d3_s', [
            'id' => $vitaminD3->id,
            'twenty_five_hydroxyvitamin_d3' => 22.5,
            'report_date' => now()->toDateString(),
        ]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->put(route('vitamin-d3.update', 1), []);

        $response->assertRedirect(route('login'));
    });
});

describe('tests for the "destroy" method of VitaminD3Controller', function () {
    it('should delete the vitamin d3 record and redirect to index', function () {
        $user = User::factory()->create();
        $medicalFile = $user->medicalFile()->create();

        $vitaminD3 = $medicalFile->vitaminD3s()->create([
            'twenty_five_hydroxyvitamin_d3' => 29.4,
            'report_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->delete(route('vitamin-d3.destroy', $vitaminD3));

        $response->assertRedirect(route('vitamin-d3.index'));
        $this->assertDatabaseMissing('vitamin_d3_s', ['id' => $vitaminD3->id]);
    });

    it('should not delete a vitamin d3 record from another user', function () {
        $owner = User::factory()->create();
        $ownerMedicalFile = $owner->medicalFile()->create();

        $vitaminD3 = $ownerMedicalFile->vitaminD3s()->create([
            'twenty_five_hydroxyvitamin_d3' => 24.6,
            'report_date' => now()->toDateString(),
        ]);

        $anotherUser = User::factory()->create();

        $response = $this->from(route('vitamin-d3.index'))
            ->actingAs($anotherUser)
            ->delete(route('vitamin-d3.destroy', $vitaminD3));

        $response->assertRedirect(route('vitamin-d3.index'));
        $this->assertDatabaseHas('vitamin_d3_s', ['id' => $vitaminD3->id]);
    });

    it('should redirect guests to login if user is not authenticated', function () {
        $response = $this->actingAsGuest()->delete(route('vitamin-d3.destroy', 1));

        $response->assertRedirect(route('login'));
    });
});
