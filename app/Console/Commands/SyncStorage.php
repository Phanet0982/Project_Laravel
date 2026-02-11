<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SyncStorage extends Command
{
    protected $signature = 'storage:sync';
    protected $description = 'Sync storage files to public directory';

    public function handle()
    {
        $this->info('Syncing storage files...');
        
        $source = storage_path('app/public');
        $destination = public_path('storage');
        
        // Create destination directory if it doesn't exist
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }
        
        // Copy all files recursively
        File::copyDirectory($source, $destination);
        
        $this->info('Storage sync completed successfully!');
        return 0;
    }
}