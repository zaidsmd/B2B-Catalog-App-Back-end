<?php

namespace Modules\Product\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Product\DTOs\CategoryCreateDTO;
use Modules\Product\DTOs\CategoryFilterDTO;
use Modules\Product\DTOs\CategoryUpdateDTO;
use Modules\Product\Http\Requests\Admin\CategoryStoreRequest;
use Modules\Product\Http\Requests\Admin\CategoryUpdateRequest;
use Modules\Product\Http\Resources\CategoryResource;
use Modules\Product\Models\Category;
use Modules\Product\Services\CategoryService;
use Yajra\DataTables\Exceptions\Exception;

class CategoryController extends Controller
{
    public function __construct(public CategoryService $service) {}

    /**
     * @throws Exception
     */
    public function index(CategoryFilterDTO $filterDTO)
    {
        return $this->service->index($filterDTO);
    }

    public function store(CategoryStoreRequest $request)
    {
        $dto = CategoryCreateDTO::fromArray($request->validated());
        $category = $this->service->create($dto);

        return new CategoryResource($category)->response()->setStatusCode(201);
    }

    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $dto = CategoryUpdateDTO::fromArray($request->validated());
        $this->service->update($category, $dto);

        return new CategoryResource($category)->response()->setStatusCode(200);
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return response()->json(['message' => __('category.messages.category_has_products')], 422);
        }
        $this->service->delete($category);

        return response()->json(['message' => __('category.messages.deleted')], 200);
    }

    public function options()
    {
        return $this->service->getOptions();
    }
}
