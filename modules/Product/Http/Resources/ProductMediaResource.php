<?php

namespace Modules\Product\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Models\Product;
use Spatie\MediaLibrary\MediaCollections\MediaCollection;

/**
 * Class ProductMediaResource
 *
 * @property int $id The unique identifier of the media item
 * @property string $name The name of the media file
 * @property int $size The size of the media file in bytes
 * @property string $url The URL to access the media file
 * @property string $mime_type The MIME type of the media file
 *
 * This class represents the resource responsible for converting product media data into an array format.
 */
class ProductMediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            'id' => $this->uuid,
            'name' => $this->file_name,
            'size' => $this->size,
            'url' => $this->original_url,
            'mime_type' => $this->mime_type,
        ];
    }
}
