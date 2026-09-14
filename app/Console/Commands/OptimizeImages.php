<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AvifEncoder;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class OptimizeImages extends Command
{
    protected $signature = 'optimize:images
                            {--path= : Specific subdirectory under storage/app/public}
                            {--dry-run : Preview changes without writing}';

    protected $description = 'Bulk optimize existing product images (main + gallery)';

    public function handle(): int
    {
        $manager = new ImageManager(Driver::class);
        $disk = Storage::disk('public');
        $dryRun = $this->option('dry-run');

        $paths = $this->gatherImagePaths();

        $this->info(sprintf('Found %d images to optimize%s.', count($paths), $dryRun ? ' (DRY RUN)' : ''));

        $totalBefore = 0;
        $totalAfter = 0;
        $optimized = 0;
        $skipped = 0;

        $bar = $this->output->createProgressBar(count($paths));

        foreach ($paths as $path) {
            $fullPath = $disk->path($path);

            if (! file_exists($fullPath)) {
                $bar->advance();

                continue;
            }

            $beforeSize = filesize($fullPath);
            $totalBefore += $beforeSize;

            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'avif'])) {
                $skipped++;
                $bar->advance();

                continue;
            }

            try {
                $image = $manager->decodePath($fullPath);

                // Resize if dimension exceeds 1600px (preserving aspect ratio)
                $image->scaleDown(1600, 1600);

                if (! $dryRun) {
                    $encoded = match ($ext) {
                        'png' => $image->encode(new PngEncoder),
                        'jpg', 'jpeg' => $image->encode(new JpegEncoder(quality: 85, progressive: true)),
                        'webp' => $image->encode(new WebpEncoder(quality: 85)),
                        'avif' => $image->encode(new AvifEncoder(quality: 75)),
                    };

                    $encoded->save($fullPath);
                }

                $afterSize = $dryRun ? $beforeSize * 0.5 : filesize($fullPath); // rough estimate for dry-run
                $totalAfter += $afterSize;
                $optimized++;
            } catch (\Throwable $e) {
                $this->warn("Failed: {$path} — {$e->getMessage()}");
                $skipped++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $saved = $totalBefore - $totalAfter;
        $percent = $totalBefore > 0 ? round(($saved / $totalBefore) * 100, 1) : 0;

        $this->info("Done. Optimized: {$optimized}, Skipped: {$skipped}");
        $this->info(sprintf('Size: %s → %s (saved %s, %s%%)',
            $this->formatBytes($totalBefore),
            $this->formatBytes($totalAfter),
            $this->formatBytes($saved),
            $percent
        ));

        return self::SUCCESS;
    }

    /** @return list<string> */
    private function gatherImagePaths(): array
    {
        $paths = [];

        if ($customPath = $this->option('path')) {
            $fullDir = Storage::disk('public')->path($customPath);
            if (is_dir($fullDir)) {
                foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($fullDir)) as $file) {
                    if ($file->isFile()) {
                        $paths[] = $customPath.'/'.$file->getFilename();
                    }
                }
            }

            return $paths;
        }

        // Gather from database
        foreach (Product::query()
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->cursor() as $product) {
            $paths[] = $product->image;
        }

        foreach (Product::query()
            ->whereNotNull('gallery')
            ->cursor() as $product) {
            foreach ($product->gallery ?? [] as $path) {
                if ($path) {
                    $paths[] = $path;
                }
            }
        }

        return array_values(array_unique($paths));
    }

    private function formatBytes(int|float $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;

        while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
            $bytes /= 1024;
            $unitIndex++;
        }

        return round($bytes, 2).' '.$units[$unitIndex];
    }
}
