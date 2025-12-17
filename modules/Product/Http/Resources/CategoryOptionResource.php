<?php

namespace Modules\Product\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $name
 */
class CategoryOptionResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'value' => (string) $this->id,
            'label' => (string) $this->name,
        ];
    }
}
