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
        if (app()->environment(['production']))
        {
            $schedule->command('schedule-monitor:sync')->dailyAt('04:56');
            $schedule->command('schedule-monitor:clean')->daily();

            // $schedule->command('inspire')->hourly();
            # check uptime
            $schedule->command('monitor:uptime-server')->hourly();
            $schedule->command('monitor:uptime-docker')->hourly();

            # Horizon includes a metrics dashboard
            $schedule->command('horizon:snapshot')->everyFiveMinutes();

            # update GeoIP DB: /storage/app/geoip.mmdb
            $schedule->command('geoip:update')->weekly();
            //
            # delete all records created over 48 hours ago
            $schedule->command('telescope:prune --hours=48')->daily();
            //
            # Fix permission files & clear config cache
            $schedule->exec('composer build')->dailyAt('6:00');
            //
            # Check server disk usage
            $schedule->command('monitor:diskusage')->hourly();

            # Cleanup oldest userLoginActivity data
            $schedule->command('userLoginActivity:cleanup')->dailyAt('04:00');

            $schedule->command('telescope:prune')->daily();
            $schedule->command('gauge:prune')->daily();

            //            $schedule->command('backup:clean')->daily()->at('01:00');
            //            $schedule
            //                ->command('backup:run')->daily()->at('01:00')
            //                ->onFailure(function () {
            //                })
            //                ->onSuccess(function () {
            //                });
            //        $schedule->command('emails:send')
            //            ->daily()
            //            ->before(function () {
            //                // Task is about to start...
            //            })
            //            ->after(function () {
            //                // Task is complete...
            //            });

            //        $schedule->command('emails:send')
            //            ->daily()
            //            ->onSuccess(function () {
            //                // The task succeeded...
            //            })
            //            ->onFailure(function () {
            //                // The task failed...
            //            });

            //        $schedule->command('analytics:report')
            //            ->daily()
            //            ->runInBackground();
        }
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
