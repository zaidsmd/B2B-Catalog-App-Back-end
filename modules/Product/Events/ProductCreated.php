<?php

namespace Modules\Product\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Product\Models\Product;

class ProductCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(public Product $product) {}
}
