<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class OrderShipped extends Mailable implements ShouldQueue
{
    use SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Your order {$this->order->code} is on its way");
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-shipped',
            with: [
                'order' => $this->order->loadMissing('items'),
                'orderUrl' => URL::signedRoute('order.show', ['order' => $this->order->code]),
            ],
        );
    }
}