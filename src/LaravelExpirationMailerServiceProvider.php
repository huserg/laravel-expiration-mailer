<?php

namespace HuserG\LaravelExpirationMailer;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->name('expiration-mailer')
            ->hasConfigFile('expiration-mailer')
            ->hasViews()
            ->hasMigration('2024_12_17_111701_create_expirations_table')
            ->hasCommand(Commands\SendExpirationEmails::class);
    }
}
