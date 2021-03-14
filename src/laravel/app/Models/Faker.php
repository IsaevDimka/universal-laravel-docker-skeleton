<?php

declare(strict_types=1);

namespace App\Models;

final class Faker
{
    /**
     * Return random string with $length
     */
    public static function randomString(int $length = 0): string
    {
        if ($length === 0) {
            $length = mt_rand(10, 100);
        }

        $characters = ' 0123456789 abcdefghijklmnopqrstuvwxyz ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($index = 0; $index < $length; $index++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function randomDateTime(): \DateTime
    {
        $dateTime = new \DateTime();
        $randomHours = mt_rand(0, 1000);
        $dateTime->modify(sprintf('-%s hours', $randomHours));

        return $dateTime;
    }

    /**
     * @param array $array
     * @return mixed
     */
    public static function randomInArray($array)
    {
        return $array[array_rand($array)];
    }

    public static function randomBoolean(): bool
    {
        return (bool) mt_rand(0, 1);
    }
}
