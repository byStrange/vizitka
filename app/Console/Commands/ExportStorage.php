<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use ZipArchive;

class ExportStorage extends Command
{
    protected $signature = 'app:export-storage';

    protected $description = 'Zip storage/app directory';

    public function handle()
    {
        $zip = new ZipArchive;
        $zipPath = public_path('storage_backup.zip');

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $files = File::allFiles(storage_path('app'));

            $this->withProgressBar($files, function ($file) use ($zip) {
                $zip->addFile($file->getRealPath(), $file->getRelativePathname());
            });

            $zip->close();
            $this->newLine();
            $this->info('Backup created: '.$zipPath);
        } else {
            $this->error('Failed to create zip.');
        }
    }
}
