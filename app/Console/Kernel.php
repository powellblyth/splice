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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('unleashed:importproductGroups')->cron('55 7,8,9,10,11,12,13,14,15,16,17,18,19,20,21 * * *');
        $schedule->command('unleashed:importproducts')->cron('57 7,8,9,10,11,12,13,14,15,16,17,18,19,20,21 * * *');
        $schedule->command('unleashed:importorders')->everyTenMinutes();

        $schedule->command('unleashed:importwarehousestocktransfers')->cron('41 21 * * *');
        $schedule->command('unleashed:importwarehouses')->cron('25 20 * * *');

        $schedule->command('walker:exportstocktransfers')->cron('40 21 * * *');
        $schedule->command('walker:importOrderConfirms')->cron('10 21 * * *');
        $schedule->command('walker:processDespatchConfirms')->cron('51 21 * * *');
        $schedule->command('core:reportunprocessedorders')->cron('52 22 * * *');

        $schedule->command('walker:exportorders')->cron('0 8,9,10,11,12,13,14,15,16,17,18,19,20,21 * * *');
        $schedule->command('walker:exportproducts')->cron('52 20 * * *');

        $schedule->command('telescope:prune')->weeklyOn(2);
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
