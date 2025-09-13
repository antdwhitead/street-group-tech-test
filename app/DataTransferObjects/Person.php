<?php

namespace App\DataTransferObjects;

class Person
{
    public function __construct(
        public string $title,
        public ?string $first_name = null,
        public ?string $initial = null,
        public string $last_name = '',
    ) {}

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'first_name' => $this->first_name,
            'initial' => $this->initial,
            'last_name' => $this->last_name,
        ];
    }
}
