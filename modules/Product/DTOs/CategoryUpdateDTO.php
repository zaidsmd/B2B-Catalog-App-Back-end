<?php

namespace Modules\Product\DTOs;

/**
 * @phpstan-type CategoryCreateShape array{
 *     name: string,
 *     slug: string,
 *     description?: string|null
 * }
 */
class CategoryUpdateDTO extends BaseDTO
{
    public string $name;

    public ?string $description = null;

    public static function fromArray(array $data): static
    {
        $dto = parent::fromArray($data);
        $dto->description = (string) $dto->description;
        $dto->name = (string) $dto->name;

        return $dto;
    }
}
