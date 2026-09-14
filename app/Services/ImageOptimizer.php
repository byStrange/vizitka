<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AvifEncoder;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

class ImageOptimizer
{
    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(Driver::class);
    }

    /**
     * Compress and store an uploaded image. Returns the relative storage path.
     */
    public function compressAndStore(UploadedFile|SymfonyUploadedFile $file, string $directory, string $disk = 'public'): string
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $hash = uniqid();
        $name = match ($ext) {
            'jpg', 'jpeg' => $hash.'.jpg',
            'png' => $hash.'.png',
            'webp' => $hash.'.webp',
            'avif' => $hash.'.avif',
            default => $hash.'.'.$ext,
        };

        $relativePath = trim($directory, '/').'/'.$name;
        $fullPath = Storage::disk($disk)->path($relativePath);

        // Ensure directory exists
        $dir = dirname($fullPath);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $image = $this->manager->decodePath($file->getRealPath());

        // Scale down large images (max 1600px on longest side, preserving aspect ratio)
        $image->scaleDown(1600, 1600);

        $encoded = match ($ext) {
            'png' => $image->encode(new PngEncoder),
            'jpg', 'jpeg' => $image->encode(new JpegEncoder(quality: 85, progressive: true)),
            'webp' => $image->encode(new WebpEncoder(quality: 85)),
            'avif' => $image->encode(new AvifEncoder(quality: 75)),
            default => $image->encode(new JpegEncoder(quality: 85, progressive: true)),
        };

        $encoded->save($fullPath);

        return $relativePath;
    }

    /**
     * Optimize an existing image file in place.
     */
    public function optimizeFile(string $absolutePath): void
    {
        if (! file_exists($absolutePath)) {
            return;
        }

        $ext = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));

        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'avif'])) {
            return;
        }

        $image = $this->manager->decodePath($absolutePath);
        $image->scaleDown(1600, 1600);

        $encoded = match ($ext) {
            'png' => $image->encode(new PngEncoder),
            'jpg', 'jpeg' => $image->encode(new JpegEncoder(quality: 85, progressive: true)),
            'webp' => $image->encode(new WebpEncoder(quality: 85)),
            'avif' => $image->encode(new AvifEncoder(quality: 75)),
        };

        $encoded->save($absolutePath);
    }
}
