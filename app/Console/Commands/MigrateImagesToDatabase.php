<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Image;
use Illuminate\Support\Facades\File;

class MigrateImagesToDatabase extends Command
{
    protected $signature = 'images:migrate';
    protected $description = 'Migrate images from public/images to the database';

    public function handle()
    {
        $imagePath = public_path('images');
        if (!File::exists($imagePath)) {
            $this->error('Directory public/images does not exist.');
            return 1;
        }

        $files = File::allFiles($imagePath);
        $count = 0;

        foreach ($files as $file) {
            $name = $file->getFilename();
            $mime = File::mimeType($file->getRealPath());
            $data = File::get($file->getRealPath());

            // Skip if already exists
            if (Image::where('name', $name)->exists()) {
                $this->line("Skipping existing: $name");
                continue;
            }

            Image::create([
                'name' => $name,
                'mime_type' => $mime,
                'data' => $data,
            ]);
            $this->info("Migrated: $name");
            $count++;
        }

        $this->info("Migration complete. $count images migrated.");
        return 0;
    }
}
