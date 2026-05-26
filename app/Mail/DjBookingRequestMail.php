<?php

namespace App\Mail;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DjBookingRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public BookingRequest $bookingRequest
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Booking Request — Disk Jockey Global',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.dj-booking-request',
        );
    }
}
