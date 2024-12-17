<?php

namespace HuserG\LaravelExpirationMailer;

use HuserG\LaravelExpirationMailer\Commands\SendExpirationEmails;
use HuserG\LaravelExpirationMailer\Models\Expiration;
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

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'expiration-mailer');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/expiration-mailer'),
        ], 'expiration-mailer-views');

        // Planifier l'exécution de la commande
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);

            // Planification : 30 jours avant expiration
            $schedule->command(SendExpirationEmails::class)
                ->daily()
                ->when(fn () => $this->shouldSendEmails(30));

            // Planification : 14 jours avant expiration
            $schedule->command(SendExpirationEmails::class)
                ->daily()
                ->when(fn () => $this->shouldSendEmails(14));
        });
    }

    protected function shouldSendEmails(int $daysBefore): bool
    {
        // Vérifier s'il y a des expirations pour X jours avant la date
        return Expiration::whereDate('expiration_date', now()->addDays($daysBefore)->toDateString())->exists();
    }
}
