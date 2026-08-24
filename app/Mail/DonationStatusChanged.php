<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Donation $donation,
        public ?string $oldStatus,
        public string $newStatus,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تغيير حالة التبرع: ' . $this->donation->donation_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.donation-status-changed',
        );
    }
}