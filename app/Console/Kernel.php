<?php

namespace App\Console;

use App\Console\Commands\NotifyDriverForOdometer;
use App\Console\Commands\OdometerEntryOverdueAlert;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //        $schedule->command(OdometerEntryOverdueAlert::class)->dailyAt('00:00')->withoutOverlapping();
        //$schedule->command(OdometerEntryOverdueAlert::class)->everyMinute()->withoutOverlapping();
        $schedule->command(NotifyDriverForOdometer::class)
            ->dailyAt('09:00') // 9 AM
            ->timezone('Africa/Accra');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
