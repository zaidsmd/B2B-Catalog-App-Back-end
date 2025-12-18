<?php

namespace Modules\Product\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
        $productId = $this->route('product')?->id;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'sku' => ['sometimes', 'nullable', 'string', 'max:255', 'unique:products,sku,'.$productId],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['sometimes', 'required', 'integer', 'min:0'],
            'stockable' => ['sometimes', 'required', 'boolean'],
            'active' => ['sometimes', 'required', 'boolean'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            // Optional image payload coming from temp upload API
            'image' => ['nullable', 'array'],
            'image.path' => ['required_with:image', 'string'],
            'image.file_name' => ['nullable', 'string'],
            'image.name' => ['nullable', 'string'],
            'image.mime_type' => ['nullable', 'string'],
            'image.size' => ['nullable', 'integer'],
            'image.custom_properties' => ['nullable', 'array'],
            'image.temporary' => ['nullable', 'boolean'],
        ];
    }
}
