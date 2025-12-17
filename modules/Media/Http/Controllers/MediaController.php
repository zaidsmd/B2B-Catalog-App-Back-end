<?php

namespace Modules\Media\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Media\Http\Requests\UploadMediaRequest;
use Modules\Media\Services\MediaService;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class MediaController extends Controller
{
    protected MediaService $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * Upload a file to the media library
     */
    public function upload(UploadMediaRequest $request): JsonResponse
    {
        try {
            $modelType = $request->filled('model_type');
            $modelId = $request->filled('model_id');

            // If model type and ID are not provided, upload to temporary storage
            if (! $modelType || ! $modelId) {
                return $this->uploadToTemp($request);
            }

            $model = $this->getModelFromRequest($request);
            $collection = $request->input('collection', 'default');
            $customProperties = $request->input('custom_properties', []);

            if ($request->hasFile('files')) {
                $files = $request->file('files');
                $media = $this->mediaService->uploadMultiple($model, $files, $collection, $customProperties);
            } else {
                $file = $request->file('file');
                $media = $this->mediaService->upload($model, $file, $collection, $customProperties);
            }

            return response()->json([
                'success' => true,
                'data' => $media,
            ]);
        } catch (FileDoesNotExist|FileIsTooBig $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading the file(s).',
            ], 500);
        }
    }

    /**
     * Upload files to temporary storage
     */
    protected function uploadToTemp(UploadMediaRequest $request): JsonResponse
    {
        try {
            $customProperties = $request->input('custom_properties', []);

            if ($request->hasFile('files')) {
                $files = $request->file('files');
                $media = $this->mediaService->uploadMultipleToTemp($files, $customProperties);
            } else {
                $file = $request->file('file');
                $media = $this->mediaService->uploadToTemp($file, $customProperties);
            }

            return response()->json([
                'success' => true,
                'data' => $media,
                'temporary' => true,
            ]);
        } catch (FileDoesNotExist|FileIsTooBig $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading the file(s) to temporary storage.',
            ], 500);
        }
    }

    /**
     * Delete a media item
     */
    public function delete(int $id): JsonResponse
    {
        $deleted = $this->mediaService->delete($id);

        if (! $deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Media not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully.',
        ]);
    }

    /**
     * Get media for a model
     */
    public function getMedia(Request $request): JsonResponse
    {
        try {
            $model = $this->getModelFromRequest($request);
            $collection = $request->input('collection');
            $media = $this->mediaService->getMedia($model, $collection);

            return response()->json([
                'success' => true,
                'data' => $media,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving media.',
            ], 500);
        }
    }

    /**
     * Get the model instance from the request
     *
     * @throws \Exception
     */
    protected function getModelFromRequest(Request $request): HasMedia
    {
        $modelType = $request->input('model_type');
        $modelId = $request->input('model_id');

        if (! $modelType || ! $modelId) {
            throw new \Exception('Model type and ID are required.');
        }

        $model = app($modelType)->find($modelId);

        if (! $model || ! ($model instanceof HasMedia)) {
            throw new \Exception('Invalid model or model does not implement HasMedia.');
        }

        return $model;
    }

    /**
     * Delete a temporary file
     */
    public function deleteTemp(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|string',
            'collection' => 'sometimes|string|nullable',
        ]);

        $path = $request->input('file');
        $collection = $request->input('collection');

        $deleted = $this->mediaService->deleteTemp($path, $collection);

        if (! $deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Temporary file not found or could not be deleted.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Temporary file deleted successfully.',
        ]);
    }

    /**
     * Delete multiple temporary files
     */
    public function deleteMultipleTemp(Request $request): JsonResponse
    {
        $request->validate([
            'paths' => 'required|array',
            'paths.*' => 'string',
            'collection' => 'sometimes|string|nullable',
        ]);

        $paths = $request->input('paths');
        $collection = $request->input('collection');

        $results = $this->mediaService->deleteMultipleTemp($paths, $collection);

        return response()->json([
            'success' => true,
            'message' => 'Temporary files deletion completed.',
            'results' => $results,
        ]);
    }
}
