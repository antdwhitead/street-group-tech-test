<?php

declare(strict_types=1);

use App\Services\HomeOwnerDataService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('HomeOwnerDataService Persistence', function () {
    beforeEach(function () {
        $this->service = new HomeOwnerDataService;
    });

    it('parses CSV file and persists to database', function () {
        $csvPath = __DIR__.'/../../Data/example.csv';
        $results = $this->service->parseCsv($csvPath, persist: true);

        expect($results)->toHaveKey('homeOwners')
            ->and($results)->toHaveKey('statistics')
            ->and($results['homeOwners'])->toHaveCount(18)
            ->and($results['statistics']['total_parsed'])->toBe(18)
            ->and($results['statistics']['newly_created'])->toBe(18)
            ->and($results['statistics']['duplicates_found'])->toBe(0);

        // Verify data was persisted to database
        $this->assertDatabaseCount('home_owners', 18);

        $firstHomeOwner = $results['homeOwners'][0];
        $this->assertDatabaseHas('home_owners', [
            'title' => $firstHomeOwner->title,
            'first_name' => $firstHomeOwner->first_name,
            'initial' => $firstHomeOwner->initial,
            'last_name' => $firstHomeOwner->last_name,
        ]);
    });

    it('handles duplicate detection correctly', function () {
        $csvPath = __DIR__.'/../../Data/example.csv';

        // First parse - all new records
        $firstResults = $this->service->parseCsv($csvPath, persist: true);

        expect($firstResults['statistics']['newly_created'])->toBe(18)
            ->and($firstResults['statistics']['duplicates_found'])->toBe(0);

        // Second parse - all duplicates
        $secondResults = $this->service->parseCsv($csvPath, persist: true);

        expect($secondResults['statistics']['newly_created'])->toBe(0)
            ->and($secondResults['statistics']['duplicates_found'])->toBe(18);

        // Verify no additional records were created
        $this->assertDatabaseCount('home_owners', 18);
    });
});
