<?php

use App\DataTransferObjects\Person;
use App\Services\HomeOwnerDataService;

describe('HomeOwnerDataService', function () {
    beforeEach(function () {
        $this->service = new HomeOwnerDataService;
    });

    describe('parseNameString', function () {
        it('parses single person with title, first name and last name', function () {
            $result = $this->service->parseNameString('Mr John Smith');

            expect($result)->toHaveCount(1)
                ->and($result[0])->toBeInstanceOf(Person::class)
                ->and($result[0]->title)->toBe('Mr')
                ->and($result[0]->first_name)->toBe('John')
                ->and($result[0]->initial)->toBeNull()
                ->and($result[0]->last_name)->toBe('Smith');
        });

        it('parses single person with title and initial', function () {
            $result = $this->service->parseNameString('Mr J. Smith');

            expect($result)->toHaveCount(1)
                ->and($result[0]->title)->toBe('Mr')
                ->and($result[0]->first_name)->toBeNull()
                ->and($result[0]->initial)->toBe('J')
                ->and($result[0]->last_name)->toBe('Smith');
        });

        it('parses single person with initial without period', function () {
            $result = $this->service->parseNameString('Mr J Smith');

            expect($result)->toHaveCount(1)
                ->and($result[0]->title)->toBe('Mr')
                ->and($result[0]->first_name)->toBeNull()
                ->and($result[0]->initial)->toBe('J')
                ->and($result[0]->last_name)->toBe('Smith');
        });

        it('parses single person with title and last name only', function () {
            $result = $this->service->parseNameString('Mr Smith');

            expect($result)->toHaveCount(1)
                ->and($result[0]->title)->toBe('Mr')
                ->and($result[0]->first_name)->toBeNull()
                ->and($result[0]->initial)->toBeNull()
                ->and($result[0]->last_name)->toBe('Smith');
        });

        it('parses multiple people connected with &', function () {
            $result = $this->service->parseNameString('Mr & Mrs Smith');

            expect($result)->toHaveCount(2)
                ->and($result[0]->title)->toBe('Mr')
                ->and($result[0]->first_name)->toBeNull()
                ->and($result[0]->initial)->toBeNull()
                ->and($result[0]->last_name)->toBe('Smith')
                ->and($result[1]->title)->toBe('Mrs')
                ->and($result[1]->first_name)->toBeNull()
                ->and($result[1]->initial)->toBeNull()
                ->and($result[1]->last_name)->toBe('Smith');
        });

        it('parses multiple people connected with and', function () {
            $result = $this->service->parseNameString('Mr and Mrs Smith');

            expect($result)->toHaveCount(2)
                ->and($result[0]->title)->toBe('Mr')
                ->and($result[0]->first_name)->toBeNull()
                ->and($result[0]->initial)->toBeNull()
                ->and($result[0]->last_name)->toBe('Smith')
                ->and($result[1]->title)->toBe('Mrs')
                ->and($result[1]->first_name)->toBeNull()
                ->and($result[1]->initial)->toBeNull()
                ->and($result[1]->last_name)->toBe('Smith');
        });

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

        it('handles empty string', function () {
            $result = $this->service->parseNameString('');

            expect($result)->toBeEmpty();
        });

        it('handles whitespace only string', function () {
            $result = $this->service->parseNameString('   ');

            expect($result)->toBeEmpty();
        });

        it('parses person without title', function () {
            $result = $this->service->parseNameString('John Smith');

            expect($result)->toHaveCount(1)
                ->and($result[0]->title)->toBe('')
                ->and($result[0]->first_name)->toBe('John')
                ->and($result[0]->initial)->toBeNull()
                ->and($result[0]->last_name)->toBe('Smith');
        });

        it('handles various titles', function () {
            $titles = ['Dr', 'Prof', 'Sir', 'Lady', 'Miss', 'Ms'];

            foreach ($titles as $title) {
                $result = $this->service->parseNameString("{$title} Smith");

                expect($result)->toHaveCount(1)
                    ->and($result[0]->title)->toBe($title)
                    ->and($result[0]->last_name)->toBe('Smith');
            }
        });

        it('infers Mrs title when first person is Mr', function () {
            $result = $this->service->parseNameString('Mr John Smith & Jane Doe');

            expect($result)->toHaveCount(2)
                ->and($result[0]->title)->toBe('Mr')
                ->and($result[1]->title)->toBe('Mrs');
        });

        it('infers Mr title when first person is Mrs', function () {
            $result = $this->service->parseNameString('Mrs Jane Smith & John Doe');

            expect($result)->toHaveCount(2)
                ->and($result[0]->title)->toBe('Mrs')
                ->and($result[1]->title)->toBe('Mr');
        });

        it('handles multiple middle names by taking last as surname', function () {
            $result = $this->service->parseNameString('Mr John Michael Smith');

            expect($result)->toHaveCount(1)
                ->and($result[0]->title)->toBe('Mr')
                ->and($result[0]->first_name)->toBe('John')
                ->and($result[0]->initial)->toBeNull()
                ->and($result[0]->last_name)->toBe('Smith');
        });

        it('handles Mister title', function () {
            $result = $this->service->parseNameString('Mister John Doe');

            expect($result)->toHaveCount(1)
                ->and($result[0]->title)->toBe('Mister')
                ->and($result[0]->first_name)->toBe('John')
                ->and($result[0]->initial)->toBeNull()
                ->and($result[0]->last_name)->toBe('Doe');
        });

        it('handles hyphenated surnames', function () {
            $result = $this->service->parseNameString('Mrs Faye Hughes-Eastwood');

            expect($result)->toHaveCount(1)
                ->and($result[0]->title)->toBe('Mrs')
                ->and($result[0]->first_name)->toBe('Faye')
                ->and($result[0]->initial)->toBeNull()
                ->and($result[0]->last_name)->toBe('Hughes-Eastwood');
        });

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

            $profResult = collect($results)->first(fn ($p) => $p->title === 'Prof');
            expect($profResult->first_name)->toBe('Alex')
                ->and($profResult->last_name)->toBe('Brogan');

            $hyphenatedResult = collect($results)->first(fn ($p) => str_contains($p->last_name ?? '', '-'));
            expect($hyphenatedResult->first_name)->toBe('Faye')
                ->and($hyphenatedResult->last_name)->toBe('Hughes-Eastwood');
        });
    });
});
