<?php

namespace Database\Factories;

use App\Enums\Title;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HomeOwnerModel>
 */
class HomeOwnerModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->randomElement(Title::values()),
            'last_name' => fake()->lastName(),
        ];
    }

    public function withFirstName(?string $firstName = null): static
    {
        return $this->state(fn (array $attributes) => [
            'first_name' => $firstName ?? fake()->firstName(),
        ]);
    }

    public function withInitial(?string $initial = null): static
    {
        return $this->state(fn (array $attributes) => [
            'initial' => $initial ?? fake()->randomLetter(),
        ]);
    }

    public function withTitle(?Title $title = null): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => $title?->value ?? fake()->randomElement(Title::values()),
        ]);
    }

    public function withLastName(?string $lastName = null): static
    {
        return $this->state(fn (array $attributes) => [
            'last_name' => $lastName ?? fake()->lastName(),
        ]);
    }

    public function withFullName(?string $firstName = null, ?string $initial = null): static
    {
        return $this->withFirstName($firstName)->withInitial($initial);
    }
}
