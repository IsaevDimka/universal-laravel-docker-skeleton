<?php

declare(strict_types=1);

namespace App\Traits;

use Carbon\Carbon;

trait HelperReportTrait
{
    /**
     * @param string|Carbon $date_from | Y-m-d
     * @param string|Carbon $data_to   | Y-m-d
     * @param string|null   $timezone  | Y-m-d
     *
     * @return array[Carbon, Carbon]
     */
    private function setTimezone($date_from, $data_to, ?string $timezone = null, ?string $date_format = 'Y-m-d'): array
    {
        if (! $date_from instanceof Carbon) {
            $date_from = Carbon::createFromFormat($date_format, $date_from);
        }
        if (! $data_to instanceof Carbon) {
            $data_to = Carbon::createFromFormat($date_format, $data_to);
        }
        $date_from->setTime(00, 00, 00);
        $data_to->setTime(23, 59, 59);

        if (! empty($timezone)) {
            $date_from->timezone($timezone);
            $data_to->timezone($timezone);
        }

        return [$date_from, $data_to];
    }

    private function generateDateRange(Carbon $start_date, Carbon $end_date): array
    {
        $dates = [];
        for ($date = $start_date; $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }
        return $dates;
    }

    /**
     * Calc percent
     *
     * @param $num
     * @param $total
     *
     * @return string
     */
    private function getPercent($num, $total): float
    {
        $percent = 0;
        if ($num > 0 && $total > 0) {
            $percent = $num / $total;
        }
        return $percent;
    }

    /**
     * @param bool $format_int
     */
    private function splitStringToArray(?string $string = null, $format_int = false): array
    {
        if (empty($string)) {
            return [];
        }
        // remove gaps
        $string = str_replace(' ', '', $string);

        $array_values = explode(',', $string);

        if ($format_int) {
            $array_values_format_int = [];
            foreach ($array_values as $value) {
                $value_to_int = (int) $value;
                if ($value_to_int) {
                    array_push($array_values_format_int, $value_to_int);
                }
            }
            return $array_values_format_int ?? [];
        }

        return $array_values ?? [];
    }
}
