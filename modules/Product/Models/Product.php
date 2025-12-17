<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'sku',
        'slug',
        'description',
        'price',
        'cost',
        'tax',
        'quantity',
        'stockable',
        'active',
        'category_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'cost' => 'float',
            'tax' => 'float',
            'quantity' => 'integer',
            'stockable' => 'boolean',
            'active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->performOnCollections('images')
            ->fit(Fit::Crop,64,64)
            ->format('webp')
            ->quality(80);

        $this->addMediaConversion('preview')
            ->performOnCollections('images')
            ->fit(Fit::Crop, 256, 256)
            ->format('webp')
            ->quality(82);

        $this->addMediaConversion('card')
            ->performOnCollections('images')
            ->fit(Fit::Crop, 800, 800)
            ->format('webp')
            ->quality(85);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')->useDisk('public');
        $this->addMediaCollection('documents')->useDisk('public');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
