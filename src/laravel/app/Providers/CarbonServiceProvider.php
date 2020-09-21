<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class CarbonServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Use: Carbon::generateDateRange($start_date, $end_date)
         */
        Carbon::macro('generateDateRange', function(
            $start_date,
            $end_date
        ) {
            if(! $start_date instanceof Carbon) {
                $start_date = Carbon::createFromFormat('Y-m-d', $start_date);
            }
            if(! $end_date instanceof Carbon) {
                $end_date = Carbon::createFromFormat('Y-m-d', $end_date);
            }

            $dates = [];
            for($date = $start_date; $date->lte($end_date); $date->addDay()){
                $dates[] = $date->format('Y-m-d');
            }
            return $dates;
        });
    }
}
