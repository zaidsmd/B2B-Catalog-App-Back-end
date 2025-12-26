<?php

namespace Modules\Product\Services\mobile\v1;

use Illuminate\Http\JsonResponse;
use Modules\Media\Services\MediaService;
use Modules\Product\Contracts\Repositories\ProductRepository;
use Modules\Product\DTOs\ProductFilterDTO;
use Modules\Product\Http\Resources\mobile\v1\ProductMobileResource;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Yajra\DataTables\Facades\DataTables;

class ProductService
{
    public function __construct(public ProductRepository $products, public MediaService $media) {}

    public function index(ProductFilterDTO $filters): JsonResponse
    {
        $query = $this->products->query()->with(['category:id,name', 'media' => function ($query) {
            $query->orderBy('order_column')->limit(1);
        }]);

        if ($filters->category_id !== null) {
            $query->where('category_id', $filters->category_id);
        }
        if ($filters->active !== null) {
            $query->where('active', $filters->active);
        }
        if ($filters->stockable !== null) {
            $query->where('stockable', $filters->stockable);
        }

        // Apply sorting from filters (sortBy, sortDir) with a whitelist of sortable columns
        $sortable = [
            'id', 'name', 'price', 'active', 'stockable', 'created_at', 'updated_at', 'sku', 'category', 'category_name',
        ];
        $sortBy = $filters->sortBy;
        $sortDir = strtolower((string) ($filters->sortDir ?? 'desc')) === 'asc' ? 'asc' : 'desc';

        if ($sortBy !== null && in_array($sortBy, $sortable, true)) {
            // Special handling for sorting by related category name
            if ($sortBy === 'category' || $sortBy === 'category_name') {
                $query->orderBy(
                    Category::query()
                        ->select('name')
                        ->whereColumn('categories.id', 'products.category_id'),
                    $sortDir
                );
            } else {
                $query->orderBy($sortBy, $sortDir);
            }
        } else {
            // Default sort
            $query->orderBy('created_at', 'desc');
        }

        return DataTables::eloquent($query)->setTransformer(fn (Product $product) => ProductMobileResource::make($product)->resolve())
            ->toJson();
    }

    public function getMedia(Product $product, string $collection = 'images'): MediaCollection
    {
        return $product->getMedia($collection);
    }
}
