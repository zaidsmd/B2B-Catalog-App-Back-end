<?php

namespace Modules\Product\DTOs;

/**
 * @phpstan-type ProductCreateShape array{
 *   name: string,
 *   sku: string,
 *   slug: string,
 *   description?: string|null,
 *   price: float|int|string,
 *   cost?: float|int|string|null,
 *   tax?: float|int|string|null,
 *   quantity: int|string,
 *   stockable: bool,
 *   active: bool,
 *   category_id?: int|null
 *   category?: int|null
 * }
 */
class ProductCreateDTO extends BaseDTO
{
    public string $name;

    public string $sku;

    public string $slug;

    public ?string $description = null;

    public float $price;

    public ?float $cost = null;

    public ?float $tax = null;

    public int $quantity;

    public bool $stockable;

    public bool $active;

    public ?int $category_id = null;

    /**
     * @param  ProductCreateShape  $data
     */
    public static function fromArray(array $data): static
    {
        $dto = parent::fromArray($data);
        $dto->stockable = (bool) $dto->stockable;
        $dto->price = (float) $dto->price;
        $dto->cost = $dto->cost !== null ? (float) $dto->cost : null;
        $dto->tax = $dto->tax !== null ? (float) $dto->tax : null;
        $dto->category_id = $data['category'] ?? null;
        $dto->quantity = 0;

        return $dto;
    }
}
