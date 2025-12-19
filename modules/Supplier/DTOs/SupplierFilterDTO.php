<?php

namespace Modules\Supplier\DTOs;

class SupplierFilterDTO extends BaseDTO
{
    public ?string $full_name = null;

    public ?string $type = null;

    public ?string $phone = null;

    public ?string $email = null;

    public ?string $address = null;

    public string $sortBy = 'created_at';

    public string $sortDir = 'desc';

    private const array SORTABLES = [
        'full_name',
        'type',
        'phone',
        'email',
        'address',
        'created_at',
    ];

    private const array SELECTABLE = [
        'id',
        'full_name',
        'email',
        'phone_number',
        'type',
        'address',
        'created_at',
    ];

    private const array SORT_DIRECTIONS = ['asc', 'desc'];

    public static function fromArray(array $data): static
    {
        $dto = parent::fromArray($data);

        $dto->sortBy = self::normalize(
            $dto->sortBy,
            self::SORTABLES,
            'created_at'
        );

        $dto->sortDir = self::normalize(
            $dto->sortDir,
            self::SORT_DIRECTIONS,
            'desc'
        );

        return $dto;
    }

    private static function normalize(
        ?string $value,
        array $allowed,
        string $default
    ): string {
        $value = strtolower((string) $value);

        return in_array($value, $allowed, true)
            ? $value
            : $default;
    }
}
