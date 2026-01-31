<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\DatabaseBackup::class,
        Commands\CheckForUpdates::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Daily database backup at 4:00 PM
        $schedule->command('backup:database --keep=7')
                 ->dailyAt('16:00')
                 ->appendOutputTo(storage_path('logs/backup.log'))
                 ->onSuccess(function () {
                     \Log::info('Scheduled backup completed successfully at ' . now());
                 })
                 ->onFailure(function () {
                     \Log::error('Scheduled backup failed at ' . now());
                 });

        // Check for updates every hour
        $schedule->command('update:check')
                 ->hourly()
                 ->appendOutputTo(storage_path('logs/update-check.log'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
