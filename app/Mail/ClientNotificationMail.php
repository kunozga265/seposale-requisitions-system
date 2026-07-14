<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $mailSubject,
        public string $heading,
        public string $body,
        public ?string $actionUrl = null,
        public ?string $actionLabel = null
    ) {}

    public function build(): static
    {
        return $this->view('emails.client-notification')->subject($this->mailSubject);
    }
}
