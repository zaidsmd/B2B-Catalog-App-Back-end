<?php

namespace Modules\Product\Http\Resources\Mobile\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Models\Product;

/**
 * @mixin Product
 */
class ProductMobileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => (float) $this->price,
            'tax' => $this->tax !== null ? (float) $this->tax : null,
            'stock' => (int) random_int(1, 100),
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
                'slug' => $this->category?->slug,
            ]),
            'imageOriginalUrl' => $this->getFirstMediaUrl('images'),
            'imagePreviewUrl' => $this->getFirstMediaUrl('images', 'preview'),
            'imageCardUrl' => $this->getFirstMediaUrl('images', 'card'),
        ];
    }
}
