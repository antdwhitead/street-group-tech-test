<?php

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

    it('can be created with factory states', function () {
        $homeOwnerWithFirstName = HomeOwnerModel::factory()->withFirstName()->create();
        $homeOwnerWithInitial = HomeOwnerModel::factory()->withInitial()->create();
        $homeOwnerWithFullName = HomeOwnerModel::factory()->withFullName()->create();

        expect($homeOwnerWithFirstName->first_name)->not->toBeNull()
            ->and($homeOwnerWithInitial->initial)->not->toBeNull()
            ->and($homeOwnerWithFullName->first_name)->not->toBeNull()
            ->and($homeOwnerWithFullName->initial)->not->toBeNull();
    });

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

    it('generates full name attribute correctly', function () {
        $homeOwner = HomeOwnerModel::factory()
            ->withTitle(Title::DR)
            ->withFirstName('Jane')
            ->withLastName('Doe')
            ->make();

        expect($homeOwner->full_name)->toBe('Dr Jane Doe');
    });

    it('generates full name with initial correctly', function () {
        $homeOwner = HomeOwnerModel::factory()
            ->withTitle(Title::MR)
            ->withInitial('J')
            ->withLastName('Smith')
            ->make();

        expect($homeOwner->full_name)->toBe('Mr J. Smith');
    });
});
