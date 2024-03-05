<?php

namespace App\Listeners;

use App\Mail\ProductOutOfStockNotification;
use App\Events\ProductOutOfStock;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class ProductOutOfStockListener implements ShouldQueue
{
    use InteractsWithQueue;


    public function handle(ProductOutOfStock $event)
    {
        $product = $event->product;

        // Use the correct Mailable class
        Mail::to('admin@34ml.com')->send(new ProductOutOfStockNotification($product));
    }
}
