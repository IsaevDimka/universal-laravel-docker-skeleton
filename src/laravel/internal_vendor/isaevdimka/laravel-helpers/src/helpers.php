<?php

declare(strict_types=1);

/**
 * @param string $string
 * @param int    $pre
 * @param int    $post
 * @param string $mark
 *
 * @return string
 */
if (! function_exists('mark_string')) {
    function mark_string(string $string, $pre = 1, $post = 1, $mark = '*')
    {
        return preg_replace_callback('/^(\+?\w{' . $pre . '})([\S\s]+)(\w{' . $post . '})$/', function ($match) use (
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
if (! function_exists('format_duration')) {
    function format_duration(float $seconds)
    {
        if ($seconds < 0.001) {
            return round($seconds * 1000000) . 'μs';
        } elseif ($seconds < 1) {
            return round($seconds * 1000, 2) . 'ms';
        }

        return round($seconds, 2) . 's';
    }
}

if (! function_exists('array_values_to_int')) {
    function array_values_to_int(array $array = []): array
    {
        return $array ? array_map(fn (string $x): int => (int) $x, explode(',', implode(',', $array))) : $array;
    }
}

if (! function_exists('array_values_to_string')) {
    function array_values_to_string(array $array = []): array
    {
        return $array ? array_map(fn (string $x): string => (string) $x, explode(',', implode(',', $array))) : $array;
    }
}

if (! function_exists('explode_string_to_array')) {
    /**
     * @param null $string
     *
     * @param bool $format_int
     */
    function explode_string_to_array(&$string = null, $format_int = false): array
    {
        if (empty($string)) {
            return [];
        }
        // remove gaps
        $string = str_replace(' ', '', $string);

        // explode string to array
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

if (! function_exists('convertToPostgresArray')) {
    /**
     * Laravel не умеет конвертировать массив в вид приемлимый для postgres.
     * Эта функция готовит нужный формат массива
     */
    function convertToPostgresArray(array $params): string
    {
        return '{' . implode(',', $params) . '}';
    }
}

if (! function_exists('covertFromPostgresArray')) {
    /**
     * Laravel не умеет конвертировать postgres массив в ассоциативный массив php.
     * Эта функция извлекает данные из такой строки и сеттит ее в php массив
     */
    function covertFromPostgresArray(string $pgArray): array
    {
        $pgArray = trim($pgArray, '{}');

        if (empty($pgArray)) {
            return [];
        }

        return explode(',', $pgArray);
    }
}
