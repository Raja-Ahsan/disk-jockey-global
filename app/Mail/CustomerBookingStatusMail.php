<?php

namespace App\Mail;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerBookingStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public BookingRequest $bookingRequest,
        public string $type
    ) {}

    public function envelope(): Envelope
    {
        $subjects = [
            'submitted' => 'Your booking request was sent',
            'accepted' => 'DJ accepted your booking — complete payment',
            'rejected' => 'DJ declined — please choose another DJ/MC',
            'expiry_notice' => 'Booking request expiration notice',
        ];

        return new Envelope(
            subject: $subjects[$this->type] ?? 'Booking update — Disk Jockey Global',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-booking-status',
        );
    }
}
