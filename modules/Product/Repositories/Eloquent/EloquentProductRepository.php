<?php

namespace Modules\Product\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Modules\Product\Contracts\Repositories\ProductRepository;
use Modules\Product\Models\Product;

class EloquentProductRepository implements ProductRepository
{
    public function __construct(public Product $model) {}

    public function query(array $with = []): Builder
    {
        $query = $this->model->newQuery();
        $with = array_merge($with, ['category']);

        return $query->with($with);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Product
    {
        return $this->model->newQuery()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Product $product, array $attributes): bool
    {
        return $product->update($attributes);
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    public function findById(int $id, array $with = []): ?Product
    {
        $with = array_merge($with, ['category']);

        return $this->model->newQuery()->with($with)->find($id);
    }

    public function findBySlug(string $slug, array $with = []): ?Product
    {
        $with = array_merge($with, ['category']);

        return $this->model->newQuery()->with($with)->where('slug', $slug)->first();
    }

    public function inStock(array $with = []): Builder
    {
        $with = array_merge($with, ['category']);

        return $this->query()->with($with)->where('stock_quantity', '>', 0);
    }

    public function slugExists(string $slug): bool
    {
        return $this->model->newQuery()->where('slug', $slug)->exists();
    }
}
