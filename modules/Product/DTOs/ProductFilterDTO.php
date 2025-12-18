<?php

namespace Modules\Product\DTOs;

class ProductFilterDTO extends BaseDTO
{
    public ?int $category_id = null;

    public ?bool $active = null;

    public ?bool $stockable = null;

    /**
     * The column to sort by (request: sortBy).
     */
    public ?string $sortBy = null;

    /**
     * The sort direction (request: sortDir) - expected 'asc' or 'desc'.
     */
    public ?string $sortDir = null;
}
