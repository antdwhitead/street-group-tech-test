<?php

namespace App\Services;

use App\DataTransferObjects\Person;
use Spatie\SimpleExcel\SimpleExcelReader;

class HomeOwnerDataService
{
    private const TITLES = [
        'Mr', 'Mrs', 'Miss', 'Ms', 'Dr', 'Prof', 'Sir', 'Lord', 'Lady', 'Mister',
    ];

    private const CONJUNCTIONS = [
        '&', 'and', 'And', 'AND',
    ];

    public function parseCsv(string $filePath): array
    {
        $people = [];

        SimpleExcelReader::create($filePath, 'csv')
            ->getRows()
            ->each(function (array $row) use (&$people) {
                foreach ($row as $nameField) {
                    if (! empty($nameField)) {
                        $parsedPeople = $this->parseNameString(trim($nameField));
                        $people = array_merge($people, $parsedPeople);
                    }
                }
            });

        return $people;
    }

    public function parseNameString(string $nameString): array
    {
        $nameString = trim($nameString);

        if (empty($nameString)) {
            return [];
        }

        if ($this->containsMultiplePeople($nameString)) {
            return $this->parseMultiplePeople($nameString);
        }

        return [$this->parseSinglePerson($nameString)];
    }

    private function containsMultiplePeople(string $nameString): bool
    {
        foreach (self::CONJUNCTIONS as $conjunction) {
            if (str_contains($nameString, " {$conjunction} ")) {
                return true;
            }
        }

        return false;
    }

    private function parseMultiplePeople(string $nameString): array
    {
        $people = [];

        foreach (self::CONJUNCTIONS as $conjunction) {
            if (str_contains($nameString, " {$conjunction} ")) {
                $parts = explode(" {$conjunction} ", $nameString, 2);

                if (count($parts) === 2) {
                    $firstPart = trim($parts[0]);
                    $secondPart = trim($parts[1]);

                    $sharedLastName = $this->extractSharedLastName($firstPart, $secondPart);

                    if ($sharedLastName && ! str_contains($firstPart, $sharedLastName)) {
                        $firstPart .= ' '.$sharedLastName;
                    }

                    $firstPerson = $this->parseSinglePerson($firstPart);
                    $people[] = $firstPerson;

                    if (! $this->hasTitle($secondPart)) {
                        $inferredTitle = $this->inferTitle($firstPerson->title);
                        $secondPart = $inferredTitle.' '.$secondPart;
                    }

                    $secondPerson = $this->parseSinglePerson($secondPart);
                    $people[] = $secondPerson;

                    return $people;
                }
            }
        }

        return [];
    }

    private function extractSharedLastName(string $firstPart, string $secondPart): ?string
    {
        $firstWords = explode(' ', $firstPart);
        $secondWords = explode(' ', $secondPart);

        if (count($secondWords) > 1 && $this->isTitle($secondWords[0])) {
            $potentialLastName = end($secondWords);

            if (count($firstWords) === 1 && $this->isTitle($firstWords[0])) {
                return $potentialLastName;
            }
        }

        return null;
    }

    private function parseSinglePerson(string $nameString): Person
    {
        $words = explode(' ', trim($nameString));
        $words = array_filter($words, fn ($word) => ! empty($word));
        $words = array_values($words);

        if (empty($words)) {
            return new Person('', null, null, '');
        }

        $title = '';
        $firstName = null;
        $initial = null;
        $lastName = '';
        $wordIndex = 0;

        if ($this->isTitle($words[0])) {
            $title = $words[0];
            $wordIndex = 1;
        }

        if ($wordIndex < count($words)) {
            $remainingWords = array_slice($words, $wordIndex);

            if (count($remainingWords) === 1) {
                $lastName = $remainingWords[0];
            } elseif (count($remainingWords) >= 2) {
                $firstNameOrInitial = $remainingWords[0];

                if ($this->isInitial($firstNameOrInitial)) {
                    $initial = rtrim($firstNameOrInitial, '.');
                } else {
                    $firstName = $firstNameOrInitial;
                }

                $lastName = $remainingWords[count($remainingWords) - 1];
            }
        }

        return new Person($title, $firstName, $initial, $lastName);
    }

    private function isTitle(string $word): bool
    {
        return in_array($word, self::TITLES, true);
    }

    private function hasTitle(string $nameString): bool
    {
        $words = explode(' ', trim($nameString));

        return $this->isTitle($words[0]);
    }

    private function isInitial(string $word): bool
    {
        return strlen($word) <= 2 &&
               preg_match('/^[A-Z]\.?$/', $word) &&
               ctype_alpha(str_replace('.', '', $word));
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
