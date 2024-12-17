<?php

namespace HuserG\LaravelExpirationMailer\Commands;

use HuserG\LaravelExpirationMailer\Mail\ExpirationNotification;
use HuserG\LaravelExpirationMailer\Models\Expiration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendExpirationEmails extends Command
{
    protected $signature = 'expiration-mailer:send-email {id?}';

    protected $description = 'Send expiration emails. Pass an ID to send for a specific expiration.';

    public function handle(): void
    {
        $id = $this->argument('id');

        if ($id) {
            // Envoyer les emails pour une expiration spécifique
            $expiration = Expiration::find($id);

            if (! $expiration) {
                $this->error("No expiration found with ID: {$id}");

                return;
            }

            $this->sendEmails($expiration);
            $this->info("Emails sent successfully for expiration ID: {$id}");
        } else {
            // Envoyer pour toutes les expirations valides
            $expirations = Expiration::whereDate('expiration_date', now()->toDateString())->get();

            foreach ($expirations as $expiration) {
                $this->sendEmails($expiration);
            }

            $this->info('Emails sent successfully for all expirations.');
        }
    }

    private function sendEmails(Expiration $expiration)
    {
        $emails = is_array($expiration->emails) ? $expiration->emails : json_decode($expiration->emails, true);

        foreach ($emails as $email) {
            Mail::to($email)->send(new ExpirationNotification($expiration));
        }
    }
}
