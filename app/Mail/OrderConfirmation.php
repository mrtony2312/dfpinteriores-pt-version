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

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmação da encomenda '.$this->order->ref.' — DFP Interiores',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.orders.confirmation',
            with: ['order' => $this->order->loadMissing('items')],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return $this->transferProofAttachment();
    }

    /**
     * @return array<int, Attachment>
     */
    private function transferProofAttachment(): array
    {
        $path = $this->order->transfer_proof_path;
        if (! $path || ! Storage::disk('local')->exists($path)) {
            return [];
        }

        return [
            Attachment::fromStorageDisk('local', $path)
                ->as($this->order->transfer_proof_name ?: 'comprovativo-transferencia')
                ->withMime(Storage::disk('local')->mimeType($path) ?: 'application/octet-stream'),
        ];
    }
}
