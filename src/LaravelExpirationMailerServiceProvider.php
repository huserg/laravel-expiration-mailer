<?php

namespace HuserG\LaravelExpirationMailer;

use HuserG\LaravelExpirationMailer\Commands\SendExpirationEmails;
use HuserG\LaravelExpirationMailer\Models\Expiration;
use HuserG\LaravelExpirationMailer\Scheduler\TaskScheduler;
use Illuminate\Console\Scheduling\Schedule;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
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
            ->hasMigration('create_expirations_table')
            ->hasViews()
            ->hasCommand(Commands\SendExpirationEmails::class)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishMigrations()
                    ->publishAssets()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('huserg/laravel-expiration-mailer');
            });
    }


    public function boot(): void
    {
        parent::boot();

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            TaskScheduler::register($schedule);
        });
    }
}
