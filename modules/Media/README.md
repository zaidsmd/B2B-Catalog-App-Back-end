# Media Module

This module provides a service for media uploads that works with the Spatie MediaLibrary package.

## Features

- Upload single or multiple files to the media library
- Delete media items
- Retrieve media for a model
- Support for different media collections

## Installation

The module is already installed and registered in the application.

## Usage

### Implementing HasMedia Interface

To use the media upload service with a model, the model must implement the `HasMedia` interface and use the `InteractsWithMedia` trait:

```php
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class YourModel extends Model implements HasMedia
{
    use InteractsWithMedia;

    // ...

    /**
     * Register media collections for the model
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('public');

        $this->addMediaCollection('documents')
            ->useDisk('public');
    }
}
```

### Using the MediaService

You can inject the `MediaService` into your controllers or other services:

```php
use Modules\Media\Services\MediaService;

class YourController extends Controller
{
    protected MediaService $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function uploadImage(Request $request)
    {
        $model = YourModel::find($request->input('model_id'));
        $file = $request->file('image');

        $media = $this->mediaService->upload($model, $file, 'images');

        return response()->json([
            'success' => true,
            'data' => $media
        ]);
    }
}
```

### API Endpoints

The module provides the following API endpoints:

- `POST /api/media/upload` - Upload a file or files to the media library
- `DELETE /api/media/{id}` - Delete a media item
- `GET /api/media` - Get media for a model

#### Upload Request Example

```
POST /api/media/upload
{
    "model_type": "Modules\\Product\\Models\\Product",
    "model_id": 1,
    "collection": "images",
    "custom_properties": {
        "alt": "Product Image"
    },
    "file": [file]
}
```

#### Get Media Request Example

```
GET /api/media?model_type=Modules\\Product\\Models\\Product&model_id=1&collection=images
```

## Examples with Product Models

### Product Model

The Product model has been configured to work with the media library:

```php
// Upload an image to a product
$product = Product::find(1);
$file = $request->file('image');
$mediaService->upload($product, $file, 'images');

// Get all images for a product
$images = $product->getMedia('images');

// Get the URL of the first image
$imageUrl = $product->getFirstMediaUrl('images');
```

### ProductVariation Model

The ProductVariation model also supports media:

```php
// Upload an image to a product variation
$variation = ProductVariation::find(1);
$file = $request->file('variation_image');
$mediaService->upload($variation, $file, 'images');

// Get all images for a product variation
$images = $variation->getMedia('images');

// Get the URL of the first image
$imageUrl = $variation->getFirstMediaUrl('images');
```
