<?php

namespace Modules\Product\DTOs;

class ProductFilterDTO extends BaseDTO
{
    public ?int $category_id = null;

    public ?bool $active = null;

    public ?bool $stockable = null;
}
