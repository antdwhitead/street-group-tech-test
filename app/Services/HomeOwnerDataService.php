<?php

declare(strict_types=1);

namespace App\Services;

use App\DataTransferObjects\HomeOwner;
use App\Enums\Conjunction;
use App\Enums\Title;
use Illuminate\Support\Arr;
use Spatie\SimpleExcel\SimpleExcelReader;

class HomeOwnerDataService
{
    public function parseCsv(string $filePath): array
    {
        $people = [];

        SimpleExcelReader::create($filePath, 'csv')
            ->getRows()
            ->each(function (array $row) use (&$people) {
                foreach ($row as $nameField) {
                    if (! empty($nameField)) {
                        $parsedPeople = $this->parseNameString(trim($nameField));
                        if ($parsedPeople) {
                            $people = Arr::flatten([$people, $parsedPeople]);
                        }
                    }
                }
            });

        return $people;
    }

    public function parseNameString(string $nameString): ?array
    {
        $nameString = trim($nameString);

        if (empty($nameString)) {
            return null;
        }

        if ($this->containsMultiplePeople($nameString)) {
            return $this->parseMultiplePeople($nameString);
        }

        $person = $this->parseSinglePerson($nameString);

        return $person ? [$person] : null;
    }

    private function containsMultiplePeople(string $nameString): bool
    {
        foreach (Conjunction::values() as $conjunction) {
            if (str_contains($nameString, " {$conjunction} ")) {
                return true;
            }
        }

        return false;
    }

    private function parseMultiplePeople(string $nameString): ?array
    {
        $conjunction = $this->findConjunction($nameString);

        if (! $conjunction) {
            return null;
        }

        $parts = $this->splitByConjunction($nameString, $conjunction);

        if (! $this->isValidConjunctionSplit($parts)) {
            return null;
        }

        return $this->createPeopleFromParts(Arr::get($parts, 0), Arr::get($parts, 1));
    }

    private function findConjunction(string $nameString): ?string
    {
        foreach (Conjunction::values() as $conjunction) {
            if (str_contains($nameString, " {$conjunction} ")) {
                return $conjunction;
            }
        }

        return null;
    }

    private function splitByConjunction(string $nameString, string $conjunction): array
    {
        return explode(" {$conjunction} ", $nameString, 2);
    }

    private function isValidConjunctionSplit(array $parts): bool
    {
        return count($parts) === 2;
    }

    private function createPeopleFromParts(string $firstPart, string $secondPart): array
    {
        $firstPart = trim($firstPart);
        $secondPart = trim($secondPart);

        $firstPart = $this->addSharedLastNameIfNeeded($firstPart, $secondPart);
        $firstPerson = $this->parseSinglePerson($firstPart);

        $secondPart = $this->addInferredTitleIfNeeded($secondPart, $firstPerson->title);
        $secondPerson = $this->parseSinglePerson($secondPart);

        return [$firstPerson, $secondPerson];
    }

    private function addSharedLastNameIfNeeded(string $firstPart, string $secondPart): string
    {
        $sharedLastName = $this->extractSharedLastName($firstPart, $secondPart);

        if ($sharedLastName && ! str_contains($firstPart, $sharedLastName)) {
            return $firstPart.' '.$sharedLastName;
        }

        return $firstPart;
    }

    private function addInferredTitleIfNeeded(string $secondPart, string $firstPersonTitle): string
    {
        if ($this->hasTitle($secondPart)) {
            return $secondPart;
        }

        $inferredTitle = $this->inferTitle($firstPersonTitle);

        return $inferredTitle.' '.$secondPart;
    }

    private function extractSharedLastName(string $firstPart, string $secondPart): ?string
    {
        $firstWords = explode(' ', $firstPart);
        $secondWords = explode(' ', $secondPart);

        if (! $this->canExtractSharedLastName($firstWords, $secondWords)) {
            return null;
        }

        return Arr::last($secondWords);
    }

    private function canExtractSharedLastName(array $firstWords, array $secondWords): bool
    {
        return $this->hasMultipleWordsWithTitle($secondWords) &&
               $this->isSingleTitleWord($firstWords);
    }

    private function hasMultipleWordsWithTitle(array $words): bool
    {
        return count($words) > 1 && $this->isTitle(Arr::first($words));
    }

    private function isSingleTitleWord(array $words): bool
    {
        return count($words) === 1 && $this->isTitle(Arr::first($words));
    }

    private function parseSinglePerson(string $nameString): ?HomeOwner
    {
        $words = $this->cleanWords($nameString);

        if (empty($words)) {
            return null;
        }

        $nameComponents = $this->extractNameComponents($words);

        return $this->createPersonFromComponents($nameComponents);
    }

    private function cleanWords(string $nameString): array
    {
        $words = explode(' ', trim($nameString));
        $words = Arr::where($words, fn ($word) => ! empty($word));

        return array_values($words);
    }

    private function extractNameComponents(array $words): array
    {
        $components = [
            'title' => '',
            'firstName' => null,
            'initial' => null,
            'lastName' => '',
        ];

        $wordIndex = $this->extractTitle($words, $components);
        $this->extractNamesFromRemainingWords($words, $wordIndex, $components);

        return $components;
    }

    private function extractTitle(array $words, array &$components): int
    {
        if ($this->isTitle(Arr::first($words))) {
            Arr::set($components, 'title', Arr::first($words));

            return 1;
        }

        return 0;
    }

    private function extractNamesFromRemainingWords(array $words, int $startIndex, array &$components): void
    {
        if ($startIndex >= count($words)) {
            return;
        }

        $remainingWords = array_slice($words, $startIndex);

        if ($this->hasSingleRemainingWord($remainingWords)) {
            Arr::set($components, 'lastName', Arr::first($remainingWords));

            return;
        }

        if ($this->hasMultipleRemainingWords($remainingWords)) {
            $this->extractFirstNameOrInitial(Arr::first($remainingWords), $components);
            Arr::set($components, 'lastName', Arr::last($remainingWords));
        }
    }

    private function hasSingleRemainingWord(array $remainingWords): bool
    {
        return count($remainingWords) === 1;
    }

    private function hasMultipleRemainingWords(array $remainingWords): bool
    {
        return count($remainingWords) >= 2;
    }

    private function extractFirstNameOrInitial(string $firstNameOrInitial, array &$components): void
    {
        if ($this->isInitial($firstNameOrInitial)) {
            Arr::set($components, 'initial', rtrim($firstNameOrInitial, '.'));

            return;
        }

        Arr::set($components, 'firstName', $firstNameOrInitial);
    }

    private function createPersonFromComponents(array $components): HomeOwner
    {
        return new HomeOwner(
            Arr::get($components, 'title'),
            Arr::get($components, 'firstName'),
            Arr::get($components, 'initial'),
            Arr::get($components, 'lastName')
        );
    }

    private function isTitle(string $word): bool
    {
        return Title::isValid($word);
    }

    private function hasTitle(string $nameString): bool
    {
        $words = explode(' ', trim($nameString));

        return $this->isTitle(Arr::first($words));
    }

    private function isInitial(string $word): bool
    {
        return strlen($word) <= 2 && preg_match('/^[A-Z]\.?$/', $word);
    }

    private function inferTitle(string $firstPersonTitle): string
    {
        return match ($firstPersonTitle) {
            'Mr' => 'Mrs',
            'Mrs' => 'Mr',
            default => $firstPersonTitle,
        };
    }
}
