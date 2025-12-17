<?php

namespace Modules\Product\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 */
class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array format.
     *
     * @param  Request  $request  The incoming HTTP request instance.
     * @return array The array representation of the resource.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
        ];
    }
}
