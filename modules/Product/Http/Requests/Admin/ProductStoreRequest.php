<?php

namespace Modules\Product\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'stockable' => ['required', 'boolean'],
            'active' => ['required', 'boolean'],
            'category' => ['nullable', 'integer', 'exists:categories,id'],
            'media' => ['nullable', 'array'],
            'media.path' => ['required_with:media', 'string'],
            'media.file_name' => ['nullable', 'string'],
            'media.name' => ['nullable', 'string'],
            'media.mime_type' => ['nullable', 'string'],
            'media.size' => ['nullable', 'integer'],
            'media.custom_properties' => ['nullable', 'array'],
            'media.temporary' => ['nullable', 'boolean'],
        ];
    }
}
