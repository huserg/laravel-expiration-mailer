<?php

namespace HuserG\LaravelExpirationMailer\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpirationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function build()
    {
        return $this->subject(__('Expiration Notification'))
            ->view('expiration-manager::email')
            ->with('message', $this->message);
    }
}