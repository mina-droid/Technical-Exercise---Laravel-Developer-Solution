<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductOutOfStockNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function build()
    {
        return $this->view('emails.product_out_of_stock');
    }
}
