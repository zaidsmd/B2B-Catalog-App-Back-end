<?php

namespace Modules\Supplier\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Supplier\Models\Supplier;

/**
 * @mixin Supplier
 */
class SupplierIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'full_name' => (string) $this->full_name,
            'phone' => (string) $this->phone,
            'email' => (string) $this->email,
            'address' => (string) $this->address,
            'type' => (string) $this->type,
        ];
    }
}
