<?php

namespace Modules\Product\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Models\Product;

/**
 * @mixin Product
 *
 * @property int $id
 * @property string $name
 * @property string $sku
 * @property string $slug
 * @property string|null $description
 * @property float $price
 * @property float|null $cost
 * @property string|null $tax
 * @property int $quantity
 * @property bool $stockable
 * @property bool $active
 * @property CategoryResource|null $category
 */
class ProductEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => (float) $this->price,
            'cost' => $this->cost !== null ? (float) $this->cost : null,
            'tax' => $this->tax !== null ? (string) $this->tax : null,
            'quantity' => (int) $this->quantity,
            'stockable' => (bool) $this->stockable,
            'active' => (bool) $this->active,
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ]),
            'created_at' => optional($this->created_at)?->toISOString(),
            'updated_at' => optional($this->updated_at)?->toISOString(),
        ];
    }
}
