<?php

/**
 * @param string $string
 * @param int    $pre
 * @param int    $post
 * @param string $mark
 *
 * @return string
 */
function markString(string $string, $pre = 1, $post = 1, $mark = '*')
{
    return preg_replace_callback('/^(\+?\w{' . $pre . '})([\S\s]+)(\w{' . $post . '})$/',
        function ($match) use ($mark) {
            return $match[1] . str_repeat($mark, strlen($match[2])) . $match[3];
    }, $string);
}

/**
 * @param float $seconds
 *
 * @return string
 */
function formatDuration(float $seconds)
{
    if ($seconds < 0.001) {
        return round($seconds * 1000000).'μs';
    } elseif ($seconds < 1) {
        return round($seconds * 1000, 2).'ms';
    }

    return round($seconds, 2).'s';
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
