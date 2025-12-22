<?php

namespace Modules\Product\Http\Controllers\Public;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Modules\Product\DTOs\ProductCreateDTO;
use Modules\Product\DTOs\ProductFilterDTO;
use Modules\Product\Http\Requests\Admin\ProductStoreRequest;
use Modules\Product\Http\Resources\ProductMediaResource;
use Modules\Product\Http\Resources\ProductResource;
use Modules\Product\Models\Product;
use Modules\Product\Services\mobile\v1\ProductService;

class ProductController extends BaseController
{
    public function __construct(public ProductService $service) {}

    public function index(Request $request): JsonResponse
    {
        $filters = ProductFilterDTO::fromArray($request->all());

        return $this->service->index($filters);
    }

    public function store(ProductStoreRequest $request): JsonResponse
    {
        $dto = ProductCreateDTO::fromArray($request->validated());
        $image = $request->input('media');
        $product = $this->service->create($dto, is_array($image) ? $image : null);

        return new ProductResource($product->load('category'))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Product $product): ProductResource
    {
        $product->load('category', 'media');

        return new ProductResource($product);
    }


    public function getMedia(Product $product, string $collection = 'images'): JsonResponse
    {
        $media = $this->service->getMedia($product, $collection);

        //        return  response()->json($media,500);
        return ProductMediaResource::collection($media)->response()->setStatusCode(200);
    }
}
