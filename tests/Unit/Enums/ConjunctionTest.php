<?php

declare(strict_types=1);

use App\Enums\Conjunction;

describe('Conjunction', function () {
    it('has all expected conjunction values', function () {
        $expectedConjunctions = ['&', 'and', 'And', 'AND'];

        expect(Conjunction::values())->toBe($expectedConjunctions);
    });

    it('validates valid conjunctions correctly', function (string $conjunction) {
        expect(Conjunction::isValid($conjunction))->toBeTrue();
    })->with([
        '&', 'and', 'And', 'AND',
    ]);

    it('rejects invalid conjunctions', function (string $invalidConjunction) {
        expect(Conjunction::isValid($invalidConjunction))->toBeFalse();
    })->with([
        'or', 'OR', 'plus', '+', 'with', '',
    ]);

    it('can be instantiated from string values', function () {
        expect(Conjunction::AMPERSAND->value)->toBe('&')
            ->and(Conjunction::AND_LOWERCASE->value)->toBe('and')
            ->and(Conjunction::AND_TITLECASE->value)->toBe('And')
            ->and(Conjunction::AND_UPPERCASE->value)->toBe('AND');
    });
});
