<?php

namespace Modules\Product\Contracts\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Modules\Product\DTOs\CategoryCreateDTO;
use Modules\Product\DTOs\CategoryUpdateDTO;
use Modules\Product\Models\Category;

interface CategoryRepository
{
    public function query(): Builder;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(CategoryCreateDTO $attributes): Category;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Category $category, CategoryUpdateDTO $attributes): bool;

    public function delete(Category $category): bool;

    public function findById(int $id): ?Category;

    public function findBySlug(string $slug): ?Category;
}
