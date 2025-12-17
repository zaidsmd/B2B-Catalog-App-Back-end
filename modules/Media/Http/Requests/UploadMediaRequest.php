<?php

namespace Modules\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadMediaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // If provided, these drive attaching to a specific model implementing HasMedia
            'model_type' => 'nullable|string',
            // Accept string to allow UUIDs or numeric IDs; controller will resolve appropriately
            'model_id' => 'nullable|string',
            'collection' => 'sometimes|string',
            'custom_properties' => 'sometimes|array',
            'file' => 'required_without:files|file|max:10240', // 10MB max
            'files' => 'required_without:file|array',
            'files.*' => 'file|max:10240', // 10MB max for each file
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'model_type.required' => 'The model type is required.',
            'model_id.required' => 'The model ID is required.',
            'file.required_without' => 'Please provide a file to upload.',
            'files.required_without' => 'Please provide files to upload.',
            'file.max' => 'The file size must not exceed 10MB.',
            'files.*.max' => 'Each file size must not exceed 10MB.',
        ];
    }
}
