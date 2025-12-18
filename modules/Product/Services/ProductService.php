<?php

namespace Modules\Product\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Modules\Media\Services\MediaService;
use Modules\Product\Contracts\Repositories\ProductRepository;
use Modules\Product\DTOs\ProductCreateDTO;
use Modules\Product\DTOs\ProductFilterDTO;
use Modules\Product\DTOs\ProductUpdateDTO;
use Modules\Product\Events\ProductCreated;
use Modules\Product\Events\ProductDeleted;
use Modules\Product\Events\ProductUpdated;
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

        return DataTables::eloquent($query)
            ->addColumn('category_name', function (Product $product): ?string {
                return $product->category?->name;
            })->editColumn('media', function (Product $product) {
                // Use optimized conversion for listing
                $url = $product->getFirstMediaUrl('images', 'thumb');

                return $url !== '' ? $url : $product->getFirstMediaUrl('images');
            })
            ->editColumn('price', fn (Product $p): float => (float) $p->price)
            ->toJson();
    }

    public function create(ProductCreateDTO $dto, ?array $image = null): Product
    {
        return DB::transaction(function () use ($dto, $image): Product {

            $product = $this->products->create([...$dto->toArray(),
                'slug' => SlugGenerator::unique($dto->name, fn (string $slug) => $this->products->slugExists($slug)),
            ]);

            // Attach image if provided
            if ($image !== null) {
                $this->media->attachFromTemp($image, $product, 'images');
            }
            event(new ProductCreated($product));

            return $product->load('category');
        });
    }

    public function update(Product $product, ProductUpdateDTO $dto, ?array $image = null): Product
    {
        return DB::transaction(function () use ($product, $dto, $image): Product {
            $this->products->update($product, array_filter(
                $dto->toArray(),
                static fn ($v) => $v !== null
            ));

            // Replace image if provided
            if ($image !== null) {
                $product->clearMediaCollection('images');
                $this->media->attachFromTemp($image, $product, 'images');
            }

            $product->refresh()->load('category');
            event(new ProductUpdated($product));

            return $product;
        });
    }

    public function delete(Product $product): bool
    {
        return DB::transaction(function () use ($product): bool {
            $deleted = $this->products->delete($product);
            event(new ProductDeleted($product));

            return $deleted;
        });
    }

    public function getMedia(Product $product, string $collection = 'images'): MediaCollection
    {
        return $product->getMedia($collection);
    }
}
