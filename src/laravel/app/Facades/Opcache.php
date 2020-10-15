<?php


namespace App\Facades;


use App\Contracts\OpcacheInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static OpcacheInterface clear()
 * @method static OpcacheInterface getConfig()
 * @method static OpcacheInterface getStatus()
 * @method static OpcacheInterface compile()
 *
 * @see OpcacheInterface
 */
class Opcache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return OpcacheInterface::class;
    }
}
