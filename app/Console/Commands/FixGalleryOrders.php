<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gallery;

class FixGalleryOrders extends Command
{
    protected $signature = 'gallery:fix-orders';
    protected $description = 'Fix duplicate sort orders in gallery images';

    public function handle()
    {
        $galleries = Gallery::orderBy('created_at')->get();
        
        foreach ($galleries as $index => $gallery) {
            $gallery->update(['sort_order' => $index]);
        }
        
        $this->info('Gallery sort orders have been fixed!');
        return Command::SUCCESS;
    }
}
