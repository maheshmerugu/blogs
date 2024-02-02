<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Reminder;
use Log;

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

        $schedule->command('delete:old-videos')->daily();
        $schedule->command('telescope:delete')->daily();
        $schedule->command('notify:expiry')->everyMinute();
        $schedule->command('email:send_before_five_minutes')->everyMinute();
        $schedule->command('notify:class')->everyFiveMinutes();
        $schedule->command('set:reminder')->everyFiveMinutes();
        // $reminders = Reminder::all();

        // foreach ($reminders as $reminder) {

        //     $time = $reminder->notify_me;

        //     // Convert the string to a Carbon instance
        //     $createdAt = \Carbon\Carbon::parse($reminder->datetime);

        //     // Subtract $time minutes from the Carbon instance
        //     $newTimestamp = $createdAt->subMinutes($time);

        //     $hour = Carbon::parse($newTimestamp)->hour;
        //     $minute = Carbon::parse($newTimestamp)->minute;

        //     // print the hour and minute
        //     $intervalTime = $hour . ":" . $minute;
        //     $schedule->command('set:reminder')
        //         ->dailyAt($intervalTime);

        // }
        //$this->info("Hello this is ripen");

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