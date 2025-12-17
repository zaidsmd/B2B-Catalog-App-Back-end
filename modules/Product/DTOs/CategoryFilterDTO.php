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

    public static function fromArray(array $data): static
    {
        $dto = parent::fromArray($data);
        $dto->slug = (string) $dto->slug;
        $dto->name = (string) $dto->name;

        return $dto;
    }
}
