<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class OrderReceivedAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Nova encomenda] '.$this->order->ref.' — '.number_format((float) $this->order->total, 2, ',', ' ').'€',
            replyTo: [$this->order->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.orders.admin',
            with: ['order' => $this->order->loadMissing('items')],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $path = $this->order->transfer_proof_path;
        if (! is_string($path) || $path === '' || ! Storage::disk('local')->exists($path)) {
            return [];
        }

        $name = $this->order->transfer_proof_name ?: basename($path);

        return [
            Attachment::fromStorageDisk('local', $path)
                ->as($name)
                ->withMime(Storage::disk('local')->mimeType($path) ?: 'application/octet-stream'),
        ];
    }
}
