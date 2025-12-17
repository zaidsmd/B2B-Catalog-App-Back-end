<?php

namespace Modules\Media\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaService
{
    /**
     * Upload a single file to the media library
     *
     * @param  HasMedia  $model  The model to attach the media to
     * @param  UploadedFile  $file  The file to upload
     * @param  string  $collection  The collection name to use
     * @param  array  $customProperties  Custom properties to add to the media
     * @return Media The uploaded media
     *
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function upload(HasMedia $model, UploadedFile $file, string $collection = 'default', array $customProperties = []): Media
    {
        return $model->addMedia($file)
            ->withCustomProperties($customProperties)
            ->toMediaCollection($collection);
    }

    /**
     * Upload multiple files to the media library
     *
     * @param  HasMedia  $model  The model to attach the media to
     * @param  array  $files  Array of files to upload
     * @param  string  $collection  The collection name to use
     * @param  array  $customProperties  Custom properties to add to the media
     * @return Collection Collection of uploaded media
     *
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function uploadMultiple(HasMedia $model, array $files, string $collection = 'default', array $customProperties = []): Collection
    {
        $uploadedMedia = collect();

        foreach ($files as $file) {
            $uploadedMedia->push(
                $this->upload($model, $file, $collection, $customProperties)
            );
        }

        return $uploadedMedia;
    }

    /**
     * Delete media by ID
     *
     * @param  int  $mediaId  The ID of the media to delete
     * @return bool Whether the media was deleted
     */
    public function delete(int $mediaId): bool
    {
        $media = Media::find($mediaId);

        if (! $media) {
            return false;
        }

        $media->delete();

        return true;
    }

    /**
     * Get all media for a model
     *
     * @param  HasMedia  $model  The model to get media for
     * @param  string|null  $collection  The collection to get media from
     * @return Collection Collection of media
     */
    public function getMedia(HasMedia $model, ?string $collection = null): Collection
    {
        return $collection ? $model->getMedia($collection) : $model->media;
    }

    // Add these methods to your MediaService class

    /**
     * Upload a file to temporary storage
     */
    public function uploadToTemp(\Illuminate\Http\UploadedFile $file, array $customProperties = []): array
    {
        // Store file in temporary directory
        $path = $file->store('temp', 'public');

        return [
            'name' => $file->getClientOriginalName(),
            'file_name' => basename($path),
            'path' => $path,
            'url' => asset('storage/'.$path),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'custom_properties' => $customProperties,
            'temporary' => true,
        ];
    }

    /**
     * Upload multiple files to temporary storage
     */
    public function uploadMultipleToTemp(array $files, array $customProperties = []): array
    {
        $media = [];

        foreach ($files as $file) {
            $media[] = $this->uploadToTemp($file, $customProperties);
        }

        return $media;
    }

    /**
     * Attach a file from temporary storage to a model's media collection.
     *
     * Expected $temp structure (example):
     * [
     *   'name' => 'char (1).png',
     *   'file_name' => 'z8Mr....png',
     *   'path' => 'temp/z8Mr....png',
     *   'url' => 'http://.../storage/temp/z8Mr....png',
     *   'mime_type' => 'image/png',
     *   'size' => 3044,
     *   'custom_properties' => [],
     *   'temporary' => true
     * ]
     *
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function attachFromTemp(array $temp, HasMedia $model, string $collection = 'default'): Media
    {
        $path = $temp['path'] ?? null;
        if ($path === null || ! is_string($path)) {
            throw new \InvalidArgumentException('Invalid temp media payload: missing path.');
        }

        // Ensure it is within the public/temp directory
        if (! str_starts_with($path, 'temp/')) {
            throw new \InvalidArgumentException('Invalid temp media path.');
        }

        $absolutePath = Storage::disk('public')->path($path);
        $customProperties = is_array($temp['custom_properties'] ?? null) ? $temp['custom_properties'] : [];

        $media = $model->addMedia($absolutePath)
            ->usingFileName($temp['file_name'] ?? basename($absolutePath))
            ->withCustomProperties($customProperties)
            ->toMediaCollection($collection);

        // Clean up temp file after successful attach
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        return $media;
    }

    /**
     * Attach multiple temp files to a model's media collection.
     *
     * @param  array<int, array>  $temps
     */
    public function attachMultipleFromTemp(array $temps, HasMedia $model, string $collection = 'default'): Collection
    {
        $uploaded = collect();

        foreach ($temps as $temp) {
            $uploaded->push($this->attachFromTemp($temp, $model, $collection));
        }

        return $uploaded;
    }

    /**
     * Delete a temporary file
     *
     * @param  string  $path  The path of the temporary file to delete
     * @param  string|null  $collection  Optional collection parameter
     * @return bool Whether the file was deleted successfully
     */
    public function deleteTemp(string $path, ?string $collection = null): bool
    {
        try {
            // Ensure the path is in the temp directory for security
            if (! str_starts_with($path, 'temp/')) {
                return false;
            }

            // Delete the file from storage
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);

                return true;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Delete multiple temporary files
     *
     * @param  array  $paths  Array of file paths to delete
     * @param  string|null  $collection  Optional collection parameter
     * @return array Array with success status and results for each file
     */
    public function deleteMultipleTemp(array $paths, ?string $collection = null): array
    {
        $results = [];

        foreach ($paths as $path) {
            $results[] = [
                'path' => $path,
                'deleted' => $this->deleteTemp($path, $collection),
            ];
        }

        return $results;
    }
}
