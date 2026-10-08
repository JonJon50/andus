<?php

namespace App\Mail;

use App\Models\ContactInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactInquirySubmitted extends Mailable implements ShouldQueueAfterCommit
{
    use Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 60;

    public array $backoff = [60, 300];

    public function __construct(public mixed $inquiry)
    {
        if ($this->inquiry instanceof ContactInquiry) {
            $this->inquiry->load('service');
        }
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New AndUs Website Inquiry',
            replyTo: [
                new Address($this->inquiry->email, $this->inquiry->name),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-inquiry-submitted',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
