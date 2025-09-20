<?php

declare(strict_types=1);

use App\Models\HomeOwnerModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

describe('HomeOwnerController', function () {
    uses(RefreshDatabase::class);

    beforeEach(function () {
        Storage::fake('local');
    });

    describe('upload', function () {
        it('successfully uploads and processes CSV file', function () {
            $csvContent = "homeowner,another\n".
                "Mr John Smith,Mrs Jane Smith\n".
                'Dr Alex Brogan,Prof Sarah Connor';

            $csvFile = UploadedFile::fake()->createWithContent('test.csv', $csvContent);

            $response = $this->post(route('homeowners.upload'), [
                'csv' => $csvFile,
            ]);

            $response->assertSuccessful();

            expect($response->viewData('page')['props'])->toHaveKey('homeOwners')
                ->and($response->viewData('page')['props'])->toHaveKey('statistics')
                ->and($response->viewData('page')['props'])->toHaveKey('totalCount');

            $statistics = $response->viewData('page')['props']['statistics'];
            expect($statistics['total_parsed'])->toBe(4)
                ->and($statistics['newly_created'])->toBe(4)
                ->and($statistics['duplicates_found'])->toBe(0);

            // Verify data was persisted to database
            $this->assertDatabaseCount('home_owners', 4);
            $this->assertDatabaseHas('home_owners', [
                'title' => 'Mr',
                'first_name' => 'John',
                'last_name' => 'Smith',
            ]);
            $this->assertDatabaseHas('home_owners', [
                'title' => 'Dr',
                'first_name' => 'Alex',
                'last_name' => 'Brogan',
            ]);
        });

        it('handles duplicate uploads correctly', function () {
            // Pre-create some home owners
            HomeOwnerModel::factory()->create([
                'title' => 'Mr',
                'first_name' => 'John',
                'last_name' => 'Smith',
            ]);

            $csvContent = "homeowner\n".
                "Mr John Smith\n".
                'Mrs Jane Doe';

            $csvFile = UploadedFile::fake()->createWithContent('test.csv', $csvContent);

            $response = $this->post(route('homeowners.upload'), [
                'csv' => $csvFile,
            ]);

            $response->assertSuccessful();

            $statistics = $response->viewData('page')['props']['statistics'];
            expect($statistics['total_parsed'])->toBe(2)
                ->and($statistics['newly_created'])->toBe(1)
                ->and($statistics['duplicates_found'])->toBe(1);

            // Verify only one new record was created
            $this->assertDatabaseCount('home_owners', 2);
        });

        it('validates CSV file is required', function () {
            $response = $this->post(route('homeowners.upload'), []);

            $response->assertSessionHasErrors(['csv']);
        });

        it('validates file is CSV format', function () {
            $txtFile = UploadedFile::fake()->create('test.txt', 100, 'application/pdf');

            $response = $this->post(route('homeowners.upload'), [
                'csv' => $txtFile,
            ]);

            $response->assertSessionHasErrors(['csv']);
        });

        it('handles empty CSV file', function () {
            $csvFile = UploadedFile::fake()->createWithContent('empty.csv', '');

            $response = $this->post(route('homeowners.upload'), [
                'csv' => $csvFile,
            ]);

            $response->assertSuccessful();

            $statistics = $response->viewData('page')['props']['statistics'];
            expect($statistics['total_parsed'])->toBe(0)
                ->and($statistics['newly_created'])->toBe(0)
                ->and($statistics['duplicates_found'])->toBe(0);

            $this->assertDatabaseCount('home_owners', 0);
        });
    });
});
