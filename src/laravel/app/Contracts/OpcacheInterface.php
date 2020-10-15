<?php


namespace App\Contracts;


interface OpcacheInterface
{
    public static function clear();

    public static function getConfig();

    public static function getStatus();

    public static function compile($force = false);
}