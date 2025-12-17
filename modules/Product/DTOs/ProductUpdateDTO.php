<?php

namespace Modules\Product\DTOs;

/**
 * @phpstan-type ProductUpdateShape array{
 *   name?: string,
 *   sku?: string,
 *   slug?: string,
 *   description?: string|null,
 *   price?: float|int|string,
 *   cost?: float|int|string|null,
 *   tax?: float|int|string|null,
 *   quantity?: int|string,
 *   stockable?: bool,
 *   active?: bool,
 *   category_id?: int|null
 * }
 */
class ProductUpdateDTO extends BaseDTO
{
    public ?string $name = null;

    public ?string $sku = null;

    public ?string $slug = null;

    public ?string $description = null;

    public ?float $price = null;

    public ?float $cost = null;

    public ?float $tax = null;

    public ?int $quantity = null;

    public ?bool $stockable = null;

    public ?bool $active = null;

    public ?int $category_id = null;

    /**
     * @param  ProductUpdateShape  $data
     */
    public static function fromArray(array $data): static
    {
        $dto = parent::fromArray($data);

        if ($dto->price !== null) {
            $dto->price = (float) $dto->price;
        }
        if ($dto->cost !== null) {
            $dto->cost = (float) $dto->cost;
        }
        if ($dto->tax !== null) {
            $dto->tax = (float) $dto->tax;
        }
        if ($dto->quantity !== null) {
            $dto->quantity = (int) $dto->quantity;
        }

        return $dto;
    }
}
