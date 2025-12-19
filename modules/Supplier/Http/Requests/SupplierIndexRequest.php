<?php

namespace Modules\Supplier\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string'],
            'email' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'sortBy' => ['nullable', 'string'],
            'sortDir' => ['nullable', 'in:asc,desc'],
        ];
    }
}
