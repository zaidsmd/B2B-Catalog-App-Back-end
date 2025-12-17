<?php

namespace Modules\Product\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Modules\Product\Contracts\Repositories\CategoryRepository;
use Modules\Product\DTOs\CategoryCreateDTO;
use Modules\Product\DTOs\CategoryFilterDTO;
use Modules\Product\DTOs\CategoryUpdateDTO;
use Modules\Product\Http\Resources\CategoryOptionResource;
use Modules\Product\Models\Category;
use Yajra\DataTables\Exceptions\Exception;
use Yajra\DataTables\Facades\DataTables;

class CategoryService
{
    public function __construct(public CategoryRepository $repository) {}

    /**
     * @throws Exception
     */
    public function index(CategoryFilterDTO $filterDTO): JsonResponse
    {

        $query = $this->repository->query();
        $query
            ->when($filterDTO->name, fn ($q) => $q->where('name', 'like', "%{$filterDTO->name}%")
            )
            ->when($filterDTO->slug, fn ($q) => $q->where('slug', 'like', "%{$filterDTO->slug}%")
            );

        return DataTables::eloquent($query)->toJson();
    }

    public function create(CategoryCreateDTO $attributes): Category
    {
        $data = $attributes->toArray();
        $data['slug'] = Str::slug($data['name'], '-');

        return $this->repository->create($data);

    }

    public function update(Category $category, CategoryUpdateDTO $attributes): bool
    {
        return $this->repository->update($category, $attributes);
    }

    public function delete(Category $category): bool
    {
        return $this->repository->delete($category);
    }

    public function getOptions()
    {
        $query = $this->repository->query()->get(['id', 'name']);

        return CategoryOptionResource::collection($query)->response()->setStatusCode(200);
    }
}
