<?php

namespace HuserG\LaravelExpirationMailer\Commands;

use HuserG\LaravelExpirationMailer\Mail\ExpirationNotification;
use HuserG\LaravelExpirationMailer\Models\Expiration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendExpirationEmails extends Command
{
    protected $signature = 'expiration:send-emails';
    protected $description = 'Send emails to notify about upcoming expirations';

    public function handle(): void
    {
        $this->info('Checking for expirations...');
        $expirations = Expiration::whereDate('expiration_date', now()->toDateString())->get();

        foreach ($expirations as $expiration) {
            foreach ($expiration->emails as $email) {
                Mail::to($email)->send(mailable: new ExpirationNotification($expiration));
                $this->info("Email sent to: {$email}");
            }
        }

        $this->info(__('Expiration emails sent successfully.'));
    }

}
