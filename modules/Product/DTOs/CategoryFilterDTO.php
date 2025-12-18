<?php

namespace Modules\Product\DTOs;

/**
 * @phpstan-type CategoryCreateShape array{
 *     name: string,
 *     slug: string,
 *     description?: string|null
 * }
 */
class CategoryFilterDTO extends BaseDTO
{
    public ?string $name = null;

    public ?string $slug = null;

    /**
     * The column to sort by (request: sortBy).
     */
    public ?string $sortBy = null;

    /**
     * The sort direction (request: sortDir) - expected 'asc' or 'desc'.
     */
    public ?string $sortDir = null;

    public static function fromArray(array $data): static
    {
        $dto = parent::fromArray($data);
        $dto->slug = (string) $dto->slug;
        $dto->name = (string) $dto->name;
        $dto->sortBy = $dto->sortBy !== null ? (string) $dto->sortBy : null;
        $dto->sortDir = $dto->sortDir !== null ? strtolower((string) $dto->sortDir) : null;

        return $dto;
    }
}
