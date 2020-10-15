<?php

/**
 * @param string $string
 * @param int    $pre
 * @param int    $post
 * @param string $mark
 *
 * @return string
 */

if (!function_exists('mark_string')) {
    function mark_string(string $string, $pre = 1, $post = 1, $mark = '*') {
        return preg_replace_callback('/^(\+?\w{' . $pre . '})([\S\s]+)(\w{' . $post . '})$/', function($match) use
        (
            $mark
        ) {
            return $match[1] . str_repeat($mark, strlen($match[2])) . $match[3];
        }, $string);
    }
}

/**
 * @param float $seconds
 *
 * @return string
 */
if (!function_exists('format_duration')) {
    function format_duration(float $seconds)
    {
        if($seconds < 0.001) {
            return round($seconds * 1000000) . 'μs';
        }elseif($seconds < 1){
            return round($seconds * 1000, 2) . 'ms';
        }

        return round($seconds, 2) . 's';
    }
}

if (!function_exists('api')) {
    /**
     * Create a new APIResponse instance.
     *
     * @param int         $status
     * @param string|null $message
     * @param array       $data
     * @param array       $extraData
     *
     * @return App\Contracts\ApiInterface
     */
    function api($status = 200, $message = null, $data = [], ...$extraData)
    {
        if (func_num_args() === 0) {
            return app(\App\Contracts\ApiInterface::class);
        }

        return app(\App\Contracts\ApiInterface::class)->response($status, $message, $data, ...$extraData);
    }
}

if (!function_exists('array_values_to_int')) {
    /**
     * @param array $array
     *
     * @return array
     */
    function array_values_to_int(array $array = []) : array
    {
        return $array ? array_map(fn(string $x): int => (int) $x, explode(',', implode(',', $array))) : $array;
    }
}

if (!function_exists('array_values_to_string')) {
    /**
     * @param array $array
     *
     * @return array
     */
    function array_values_to_string(array $array = []) : array
    {
        return $array ? array_map(fn(string $x): string => (string) $x, explode(',', implode(',', $array))) : $array;
    }
}