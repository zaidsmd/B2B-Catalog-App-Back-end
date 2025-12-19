<?php

namespace Modules\Supplier\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Supplier\Models\Supplier;

/**
 * @mixin Supplier
 */
class SupplierResource extends JsonResource
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
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'type' => $this->type,
            'default_reduction' => (float) $this->default_reduction,
            'credit_limit' => $this->credit_limit !== null ? (float) $this->credit_limit : null,
            'address' => $this->address,
            'ice' => $this->ice,
            'vat_number' => $this->vat_number,
            'rc' => $this->rc,
            'tax_id' => $this->tax_id,
            'rib' => $this->rib,
            'iban' => $this->iban,
            'swift_bic' => $this->swift_bic,
            'account_number' => $this->account_number,
            'routing_number' => $this->routing_number,
        ];
    }
}
