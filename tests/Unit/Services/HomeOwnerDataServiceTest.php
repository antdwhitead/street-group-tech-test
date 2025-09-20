<?php

use App\DataTransferObjects\HomeOwner;
use App\Services\HomeOwnerDataService;

describe('HomeOwnerDataService', function () {
    beforeEach(function () {
        $this->service = new HomeOwnerDataService;
    });

    describe('parseNameString', function () {
        it('parses single person names', function (string $input, string $expectedTitle, ?string $expectedFirstName, ?string $expectedInitial, string $expectedLastName) {
            $result = $this->service->parseNameString($input);

            expect($result)->toHaveCount(1)
                ->and($result[0])->toBeInstanceOf(HomeOwner::class)
                ->and($result[0]->title)->toBe($expectedTitle)
                ->and($result[0]->first_name)->toBe($expectedFirstName)
                ->and($result[0]->initial)->toBe($expectedInitial)
                ->and($result[0]->last_name)->toBe($expectedLastName);
        })->with([
            'title, first name and last name' => ['Mr John Smith', 'Mr', 'John', null, 'Smith'],
            'title and initial with period' => ['Mr J. Smith', 'Mr', null, 'J', 'Smith'],
            'title and initial without period' => ['Mr J Smith', 'Mr', null, 'J', 'Smith'],
            'title and last name only' => ['Mr Smith', 'Mr', null, null, 'Smith'],
            'no title' => ['John Smith', '', 'John', null, 'Smith'],
            'multiple middle names' => ['Mr John Michael Smith', 'Mr', 'John', null, 'Smith'],
            'Mister title' => ['Mister John Doe', 'Mister', 'John', null, 'Doe'],
            'hyphenated surnames' => ['Mrs Faye Hughes-Eastwood', 'Mrs', 'Faye', null, 'Hughes-Eastwood'],
            'single character initial' => ['Dr A Smith', 'Dr', null, 'A', 'Smith'],
            'two-character first name' => ['Mr Jo Smith', 'Mr', 'Jo', null, 'Smith'],
        ]);

        it('parses multiple people with connectors', function (string $input) {
            $result = $this->service->parseNameString($input);

            expect($result)->toHaveCount(2)
                ->and($result[0]->title)->toBe('Mr')
                ->and($result[0]->first_name)->toBeNull()
                ->and($result[0]->initial)->toBeNull()
                ->and($result[0]->last_name)->toBe('Smith')
                ->and($result[1]->title)->toBe('Mrs')
                ->and($result[1]->first_name)->toBeNull()
                ->and($result[1]->initial)->toBeNull()
                ->and($result[1]->last_name)->toBe('Smith');
        })->with([
            'ampersand connector' => ['Mr & Mrs Smith'],
            'and connector' => ['Mr and Mrs Smith'],
        ]);

        it('parses multiple people with different last names', function () {
            $result = $this->service->parseNameString('Mr John Smith & Mrs Jane Doe');

            expect($result)->toHaveCount(2)
                ->and($result[0]->title)->toBe('Mr')
                ->and($result[0]->first_name)->toBe('John')
                ->and($result[0]->initial)->toBeNull()
                ->and($result[0]->last_name)->toBe('Smith')
                ->and($result[1]->title)->toBe('Mrs')
                ->and($result[1]->first_name)->toBe('Jane')
                ->and($result[1]->initial)->toBeNull()
                ->and($result[1]->last_name)->toBe('Doe');
        });

        it('handles empty or invalid inputs', function (string $input) {
            $result = $this->service->parseNameString($input);

            expect($result)->toBeNull();
        })->with([
            'empty string' => [''],
            'whitespace only' => ['   '],
        ]);

        it('handles various titles', function (string $title) {
            $result = $this->service->parseNameString("{$title} Smith");

            expect($result)->toHaveCount(1)
                ->and($result[0]->title)->toBe($title)
                ->and($result[0]->last_name)->toBe('Smith');
        })->with([
            'Dr', 'Prof', 'Sir', 'Lady', 'Miss', 'Ms',
        ]);

        it('infers spouse title', function (string $input, string $firstTitle, string $secondTitle) {
            $result = $this->service->parseNameString($input);

            expect($result)->toHaveCount(2)
                ->and($result[0]->title)->toBe($firstTitle)
                ->and($result[1]->title)->toBe($secondTitle);
        })->with([
            'Mr to Mrs inference' => ['Mr John Smith & Jane Doe', 'Mr', 'Mrs'],
            'Mrs to Mr inference' => ['Mrs Jane Smith & John Doe', 'Mrs', 'Mr'],
        ]);

        it('handles multiple people with different surnames', function () {
            $result = $this->service->parseNameString('Mr Tom Staff and Mr John Doe');

            expect($result)->toHaveCount(2)
                ->and($result[0]->title)->toBe('Mr')
                ->and($result[0]->first_name)->toBe('Tom')
                ->and($result[0]->initial)->toBeNull()
                ->and($result[0]->last_name)->toBe('Staff')
                ->and($result[1]->title)->toBe('Mr')
                ->and($result[1]->first_name)->toBe('John')
                ->and($result[1]->initial)->toBeNull()
                ->and($result[1]->last_name)->toBe('Doe');
        });

        it('handles invalid conjunction input', function () {
            $result = $this->service->parseNameString('Mr &');

            expect($result)->toHaveCount(1)
                ->and($result[0]->title)->toBe('Mr')
                ->and($result[0]->first_name)->toBeNull()
                ->and($result[0]->initial)->toBeNull()
                ->and($result[0]->last_name)->toBe('&');
        });

    });

    describe('parseCsv', function () {
        it('parses CSV file correctly', function () {
            $csvPath = __DIR__.'/../../Data/example.csv';
            $results = $this->service->parseCsv($csvPath);

            expect($results)->toHaveCount(18)
                ->and($results[0]->title)->toBe('Mr')
                ->and($results[0]->first_name)->toBe('John')
                ->and($results[0]->last_name)->toBe('Smith')
                ->and($results[1]->title)->toBe('Mrs')
                ->and($results[1]->first_name)->toBe('Jane')
                ->and($results[1]->last_name)->toBe('Smith')
                ->and($results[2]->title)->toBe('Mister')
                ->and($results[2]->first_name)->toBe('John')
                ->and($results[2]->last_name)->toBe('Doe')
                ->and($results[4]->title)->toBe('Mr')
                ->and($results[4]->last_name)->toBe('Smith')
                ->and($results[5]->title)->toBe('Mrs')
                ->and($results[5]->last_name)->toBe('Smith');

            $profResults = collect($results)->where('title', 'Prof');
            expect($profResults)->toHaveCount(1)
                ->and($profResults->first()->first_name)->toBe('Alex')
                ->and($profResults->first()->last_name)->toBe('Brogan');

            $hyphenatedResults = collect($results)->filter(fn ($p) => str_contains($p->last_name ?? '', '-'));
            expect($hyphenatedResults)->toHaveCount(1)
                ->and($hyphenatedResults->first()->first_name)->toBe('Faye')
                ->and($hyphenatedResults->first()->last_name)->toBe('Hughes-Eastwood');
        });
    });
});
