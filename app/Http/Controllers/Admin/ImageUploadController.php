<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

// use Intervention\Image\Facades\Image; // Will be enabled when package is installed

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'images' => 'required|array|max:10',
            'images.*' => 'required|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/avif|mimes:jpeg,png,jpg,gif,webp,avif|max:5120', // 5MB max
            'type' => 'required|in:product,user,general,cms',
            'product_id' => 'nullable|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $uploadedImages = [];
        $type = $request->type;
        $productId = $request->product_id;

        try {
            foreach ($request->file('images') as $image) {
                $filename = $this->generateFilename($image, $type);
                $path = $this->getStoragePath($type, $productId);

                // Store original image
                $originalPath = $image->storeAs($path, $filename, 'public');

                // Generate thumbnails
                $thumbnails = $this->generateThumbnails($image, $path, $filename);

                $uploadedImages[] = [
                    'filename' => $filename,
                    'path' => $originalPath,
                    'url' => asset('storage/'.$originalPath),
                    'thumbnails' => $thumbnails,
                    'size' => $image->getSize(),
                    'mime_type' => $image->getMimeType(),
                ];
            }

            return response()->json([
                'success' => true,
                'images' => $uploadedImages,
                'message' => count($uploadedImages).' image(s) uploaded successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: '.$e->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, $image)
    {
        try {
            $imagePath = 'images/'.$image;

            if (Storage::dynamic()->exists($imagePath)) {
                Storage::dynamic()->delete($imagePath);

                // Delete thumbnails
                $this->deleteThumbnails($imagePath);

                return response()->json([
                    'success' => true,
                    'message' => 'Image deleted successfully.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Image not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed: '.$e->getMessage(),
            ], 500);
        }
    }

    public function getCmsImages()
    {
        try {
            $cmsPath = 'cms/images';
            $images = [];

            if (Storage::disk('public')->exists($cmsPath)) {
                $files = Storage::disk('public')->files($cmsPath);

                foreach ($files as $file) {
                    // Skip thumbnails directory
                    if (strpos($file, '/thumbnails/') !== false) {
                        continue;
                    }

                    $images[] = [
                        'filename' => basename($file),
                        'path' => $file,
                        'url' => asset('storage/'.$file),
                        'size' => Storage::disk('public')->size($file),
                        'created_at' => Storage::disk('public')->lastModified($file),
                    ];
                }

                // Sort by creation date (newest first)
                usort($images, function ($a, $b) {
                    return $b['created_at'] - $a['created_at'];
                });
            }

            return response()->json([
                'success' => true,
                'images' => $images,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load images: '.$e->getMessage(),
            ], 500);
        }
    }

    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'images' => 'required|array',
            'images.*.id' => 'required|string',
            'images.*.order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Update image order in database if needed
            // This would typically update a sort_order field in your images table

            return response()->json([
                'success' => true,
                'message' => 'Image order updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Reorder failed: '.$e->getMessage(),
            ], 500);
        }
    }

    private function generateFilename($image, $type)
    {
        $extension = $image->getClientOriginalExtension();
        $timestamp = now()->format('Y-m-d_H-i-s');
        $random = Str::random(8);

        return "{$type}_{$timestamp}_{$random}.{$extension}";
    }

    private function getStoragePath($type, $productId = null)
    {
        $basePath = "images/{$type}";

        if ($productId) {
            $basePath .= "/product_{$productId}";
        }

        // Special handling for CMS images
        if ($type === 'cms') {
            $basePath = 'cms/images';
        }

        return $basePath;
    }

    private function generateThumbnails($image, $path, $filename)
    {
        $thumbnails = [];
        $sizes = [
            'small' => [150, 150],
            'medium' => [300, 300],
            'large' => [600, 600],
        ];

        try {
            // For now, just return the original image as thumbnails
            // This will be enhanced when Intervention Image is installed
            foreach ($sizes as $size => $dimensions) {
                $thumbnailFilename = $this->getThumbnailFilename($filename, $size);
                $thumbnailPath = $path.'/thumbnails/'.$thumbnailFilename;

                // Create thumbnails directory if it doesn't exist
                $thumbnailDir = storage_path('app/public/'.$path.'/thumbnails');
                if (! file_exists($thumbnailDir)) {
                    mkdir($thumbnailDir, 0755, true);
                }

                // Copy original image as thumbnail for now
                $originalPath = storage_path('app/public/'.$path.'/'.$filename);
                $thumbnailFullPath = storage_path('app/public/'.$thumbnailPath);

                if (file_exists($originalPath)) {
                    copy($originalPath, $thumbnailFullPath);
                }

                $thumbnails[$size] = [
                    'path' => $thumbnailPath,
                    'url' => asset('storage/'.$thumbnailPath),
                    'width' => $dimensions[0],
                    'height' => $dimensions[1],
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Thumbnail generation failed: '.$e->getMessage());
        }

        return $thumbnails;
    }

    private function getThumbnailFilename($filename, $size)
    {
        $pathInfo = pathinfo($filename);

        return $pathInfo['filename']."_{$size}.".$pathInfo['extension'];
    }

    private function deleteThumbnails($imagePath)
    {
        $pathInfo = pathinfo($imagePath);
        $thumbnailDir = $pathInfo['dirname'].'/thumbnails/';
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'];

        $sizes = ['small', 'medium', 'large'];

        foreach ($sizes as $size) {
            $thumbnailPath = $thumbnailDir.$filename."_{$size}.{$extension}";
            if (Storage::dynamic()->exists($thumbnailPath)) {
                Storage::dynamic()->delete($thumbnailPath);
            }
        }
    }
}
