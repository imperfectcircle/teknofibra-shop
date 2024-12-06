<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrderEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Order $order, public $forAdmin = true)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('shop@teknofibra.it'),
            subject: 'New Order Created',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $billingAddress = $this->order->user->customer->billingAddress;
        $shippingAddress = $this->order->user->customer->shippingAddress;
        $name = $this->order->user->name;

        return new Content(
            view: 'mail.new-order',
            with: [
                'order' => $this->order,
                'name' => $name,
                'billingAddress' => $billingAddress,
                'shippingAddress' => $shippingAddress,
                'isAdmin' => $this->forAdmin
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
