<?php

declare(strict_types=1);

namespace Api;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Api\ApiApi
 */
/**
 * @method static ApiResponse response($status, $message, $data, ...$extraData)
 * @method static ApiResponse ok($message = null, $data = [], ...$extraData)
 * @method static ApiResponse success($message = null, $data = [], ...$extraData)
 * @method static ApiResponse bad($message = null, $errors = [], ...$extraData)
 * @method static ApiResponse notFound($message = null)
 * @method static ApiResponse validation($message = null, $errors = [], ...$extraData)
 * @method static ApiResponse forbidden($message = null, $data = [], ...$extraData)
 * @method static ApiResponse error($message = null, $data = [], ...$extraData)
 *
 * @see \Api\ApiApi
 */
class ApiFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ApiInterface::class;
    }
}
