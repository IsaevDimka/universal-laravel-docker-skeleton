<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class Opcache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'opcache';
    }
}
