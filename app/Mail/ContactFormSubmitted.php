<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $userMessage;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $email, $message)
    {
        $this->name = $name;
        $this->email = $email;
        $this->userMessage = $message;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->markdown('emails.contact-form-submitted')
                    ->subject('New Contact Form Submission')
                    ->with([
                        'name' => $this->name,
                        'email' => $this->email,
                        'message' => $this->userMessage
                    ]);
    }
}
