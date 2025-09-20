<?php

declare(strict_types=1);

use App\Enums\Title;

describe('Title', function () {
    it('has all expected title values', function () {
        $expectedTitles = [
            'Mr', 'Mrs', 'Miss', 'Ms', 'Dr', 'Prof', 'Sir', 'Lord', 'Lady', 'Mister',
        ];

        expect(Title::values())->toBe($expectedTitles);
    });

    it('validates valid titles correctly', function (string $title) {
        expect(Title::isValid($title))->toBeTrue();
    })->with([
        'Mr', 'Mrs', 'Miss', 'Ms', 'Dr', 'Prof', 'Sir', 'Lord', 'Lady', 'Mister',
    ]);

    it('rejects invalid titles', function (string $invalidTitle) {
        expect(Title::isValid($invalidTitle))->toBeFalse();
    })->with([
        'mr', 'MR', 'Doctor', 'Professor', 'Invalid', '',
    ]);

    it('can be instantiated from string values', function () {
        expect(Title::MR->value)->toBe('Mr')
            ->and(Title::MRS->value)->toBe('Mrs')
            ->and(Title::DR->value)->toBe('Dr');
    });
});
