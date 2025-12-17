<?php

namespace Modules\Product\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Modules\Product\Contracts\Repositories\CategoryRepository;
use Modules\Product\DTOs\CategoryCreateDTO;
use Modules\Product\DTOs\CategoryUpdateDTO;
use Modules\Product\Models\Category;

class EloquentCategoryRepository implements CategoryRepository
{
    public function __construct(public Category $model) {}

    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    public function create(CategoryCreateDTO $attributes): Category
    {
        return $this->model->newQuery()->create($attributes->toArray());
    }

    public function update(Category $category, CategoryUpdateDTO $attributes): bool
    {
        return $category->update($attributes->toArray());
    }

    public function delete(Category $category): bool
    {
        return $category->delete();
    }

    public function findById(int $id): ?Category
    {
        return $this->model->newQuery()->find($id);
    }

    public function findBySlug(string $slug): ?Category
    {
        return $this->model->newQuery()->where('slug', $slug)->first();
    }
}
