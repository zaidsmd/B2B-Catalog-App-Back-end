<?php

namespace Modules\Product\DTOs;

/**
 * @phpstan-type CategoryCreateShape array{
 *     name: string,
 *     slug: string,
 *     description?: string|null
 * }
 */
class CategoryCreateDTO extends BaseDTO
{
    public string $name;

    public ?string $description = null;

    public ?string $slug = null;

    public static function fromArray(array $data): static
    {
        $dto = parent::fromArray($data);
        $dto->description = (string) $dto->description;
        $dto->name = (string) $dto->name;
        $dto->slug = (string) $dto->slug ?? null;

        return $dto;
    }
}
