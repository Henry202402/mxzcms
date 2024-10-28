<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        // $schedule->command('inspire')->hourly();
        hook('RunCronJob', ['schedule' => $schedule]);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands() {
        $this->load(__DIR__ . '/Commands');
        $commandList = hook('GetCommandList', ['moduleName' => 'System'])[0];
        $this->commands = $commandList ?: [];
//        require base_path('routes/console.php');
    }
}
