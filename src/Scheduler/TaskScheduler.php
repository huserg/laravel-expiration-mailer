<?php

namespace HuserG\LaravelExpirationMailer\Scheduler;

use HuserG\LaravelExpirationMailer\Commands\SendExpirationEmails;
use HuserG\LaravelExpirationMailer\Models\Expiration;
use Illuminate\Console\Scheduling\Schedule;

class TaskScheduler
{
    public static function register(Schedule $schedule): void
    {
        // 30 jours avant expiration
        $schedule->command(SendExpirationEmails::class)
            ->daily()
            ->when(fn () => self::shouldSendEmails(30));

        // 14 jours avant expiration
        $schedule->command(SendExpirationEmails::class)
            ->daily()
            ->when(fn () => self::shouldSendEmails(14));
    }

    protected static function shouldSendEmails(int $daysBefore): bool
    {
        return Expiration::whereDate('expiration_date', now()->addDays($daysBefore)->toDateString())->exists();
    }
}
