<?php

declare(strict_types=1);

namespace Opcache;

use Illuminate\Support\Facades\Facade;

/**
 * @method static OpcacheInterface clear()
 * @method static OpcacheInterface getConfig()
 * @method static OpcacheInterface getStatus()
 * @method static OpcacheInterface compile()
 *
 * @see OpcacheInterface
 */
class OpcacheFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return OpcacheInterface::class;
    }
}
