<?php

namespace HuserG\LaravelExpirationMailer;

use HuserG\LaravelExpirationMailer\Mail\ExpirationNotification;
use HuserG\LaravelExpirationMailer\Models\Expiration;
use Illuminate\Support\Facades\Schedule;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use HuserG\LaravelExpirationMailer\Commands\LaravelExpirationMailerCommand;

class LaravelExpirationMailerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-expiration-mailer')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_expirations_table');
    }


    public function boot()
    {
        // Planifier l'envoi des emails
        Schedule::call(function () {
            $expirations = Expiration::whereDate('expiration_date', now()->toDateString())->get();

            foreach ($expirations as $expiration) {
                foreach ($expiration->emails as $email) {
                    Mail::to($email)->send(new ExpirationNotification($expiration->message));
                }
            }
        })->daily();
    }
}
