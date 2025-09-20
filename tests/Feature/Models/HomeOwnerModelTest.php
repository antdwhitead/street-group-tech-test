<?php

declare(strict_types=1);

use App\Enums\Title;
use App\Models\HomeOwnerModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('HomeOwnerModel', function () {
    it('can be created from factory', function () {
        $homeOwner = HomeOwnerModel::factory()->create();

        expect($homeOwner)->toBeInstanceOf(HomeOwnerModel::class)
            ->and($homeOwner->title)->toBeInstanceOf(Title::class)
            ->and($homeOwner->last_name)->toBeString();
    });

    it('can be created with factory states', function (string $method, string $field) {
        $homeOwner = HomeOwnerModel::factory()->{$method}()->create();

        expect($homeOwner->{$field})->not->toBeNull();
    })->with([
        'withFirstName' => ['withFirstName', 'first_name'],
        'withInitial' => ['withInitial', 'initial'],
        'withFullName has first_name' => ['withFullName', 'first_name'],
        'withFullName has initial' => ['withFullName', 'initial'],
    ]);

    it('scopes by full name correctly', function () {
        HomeOwnerModel::factory()
            ->withTitle(Title::MR)
            ->withFirstName('John')
            ->withLastName('Smith')
            ->create();

        HomeOwnerModel::factory()
            ->withTitle(Title::MRS)
            ->withFirstName('Jane')
            ->withLastName('Doe')
            ->create();

        $result = HomeOwnerModel::byFullName('Mr', 'John', null, 'Smith')->first();

        expect($result)->not->toBeNull()
            ->and($result->title)->toBe(Title::MR)
            ->and($result->first_name)->toBe('John')
            ->and($result->last_name)->toBe('Smith');
    });

    it('generates full name attribute correctly', function (Title $title, ?string $firstName, ?string $initial, string $lastName, string $expected) {
        $factory = HomeOwnerModel::factory()->withTitle($title)->withLastName($lastName);

        if ($firstName) {
            $factory = $factory->withFirstName($firstName);
        }

        if ($initial) {
            $factory = $factory->withInitial($initial);
        }

        $homeOwner = $factory->make();

        expect($homeOwner->full_name)->toBe($expected);
    })->with([
        'with first name' => [Title::DR, 'Jane', null, 'Doe', 'Dr Jane Doe'],
        'with initial' => [Title::MR, null, 'J', 'Smith', 'Mr J. Smith'],
        'title and last name only' => [Title::MRS, null, null, 'Johnson', 'Mrs Johnson'],
        'with both first name and initial' => [Title::PROF, 'Mary', 'A', 'Wilson', 'Prof Mary A. Wilson'],
    ]);
});
