<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $selectedProducts;

    /**
     * Create a new message instance.
     */
    public function __construct($order, $selectedProducts)
    {
        $this->order = $order;
        $this->selectedProducts = $selectedProducts;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Xác nhận đơn hàng #' . $this->order->code)
                    ->view('client.cart.order_confirmation');
    }
}
