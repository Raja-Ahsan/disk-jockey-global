<?php

namespace App\Mail;

use App\Models\PlanMyEventRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PlanMyEventSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PlanMyEventRequest $planMyEventRequest
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Plan My Event Request — Disk Jockey Global',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.plan-my-event-submitted',
        );
    }
}
