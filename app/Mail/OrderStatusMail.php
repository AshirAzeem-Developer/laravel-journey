<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $myorder;

    public function __construct(Order $order)
    {
        $this->myorder = $order;
    }

    public function envelope(): Envelope
    {
        $status = ucfirst($this->myorder->order_status);

        return new Envelope(
            subject: "Update: Your Order #{$this->myorder->order_number} is now {$status}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order-status',
            with: [
                'order' => $this->myorder
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
