<?php

namespace Modules\Product\Contracts\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Modules\Product\Models\Product;

interface ProductRepository
{
    public function query(array $with = []): Builder;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Product;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Product $product, array $attributes): bool;

    public function delete(Product $product): bool;

    public function findById(int $id, array $with = []): ?Product;

    public function findBySlug(string $slug, array $with = []): ?Product;

    public function inStock(array $with = []): Builder;
}
