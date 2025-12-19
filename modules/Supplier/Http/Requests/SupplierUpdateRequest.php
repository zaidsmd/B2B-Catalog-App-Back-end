<?php

namespace Modules\Supplier\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Supplier\Enums\SupplierType;

class SupplierUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'full_name' => ['sometimes', 'required'],
            'type' => ['sometimes', 'required', Rule::in(SupplierType::cases())],
            'default_reduction' => ['nullable', 'numeric'],
            'credit_limit' => ['nullable', 'numeric'],
            'email' => ['nullable', 'email', 'max:254'],
            'phone' => ['nullable', 'max:20'],
            'address' => ['nullable', 'max:255'],
            'ice' => ['nullable', 'max:60'],
            'vat_number' => ['nullable', 'max:60', 'string'],
            'rc' => ['nullable', 'max:60', 'string'],
            'tax_id' => ['nullable', 'max:60', 'string'],
            'rib' => ['nullable', 'max:60', 'string'],
            'iban' => ['nullable', 'string', 'max:60'],
            'swift_bic' => ['nullable', 'string', 'max:60'],
            'account_number' => ['nullable', 'string', 'max:60'],
            'routing_number' => ['nullable', 'string', 'max:60'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
