<?php

namespace App\Models;

use App\DataTransferObjects\HomeOwner;
use App\Enums\Title;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeOwnerModel extends Model
{
    /** @use HasFactory<\Database\Factories\HomeOwnerModelFactory> */
    use HasFactory;

    protected $table = 'home_owners';

    protected $fillable = [
        'title',
        'first_name',
        'initial',
        'last_name',
    ];

    protected $casts = [
        'title' => Title::class,
    ];

    public static function fromDto(HomeOwner $homeOwner): array
    {
        return [
            'title' => $homeOwner->title,
            'first_name' => $homeOwner->first_name,
            'initial' => $homeOwner->initial,
            'last_name' => $homeOwner->last_name,
        ];
    }

    public function scopeByFullName($query, Title|string $title, ?string $firstName, ?string $initial, string $lastName)
    {
        $titleValue = $title instanceof Title ? $title->value : $title;

        return $query->where([
            'title' => $titleValue,
            'first_name' => $firstName,
            'initial' => $initial,
            'last_name' => $lastName,
        ]);
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => implode(' ', array_filter([
                $this->title->value,
                $this->first_name,
                $this->initial ? $this->initial.'.' : null,
                $this->last_name,
            ])),
        );
    }
}
