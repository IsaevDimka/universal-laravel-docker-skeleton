<?php


namespace App\Facades;

use App\Contracts\ApiInterface;
use App\Responses\ApiResponse;
use Illuminate\Support\Facades\Facade;

/**
 * @method static ApiResponse response($status, $message, $data, ...$extraData)
 * @method static ApiResponse ok($message = null, $data = [], ...$extraData)
 * @method static ApiResponse success($message = null, $data = [], ...$extraData)
 * @method static ApiResponse notFound($message = null)
 * @method static ApiResponse validation($message = null, $errors = [], ...$extraData)
 * @method static ApiResponse forbidden($message = null, $data = [], ...$extraData)
 * @method static ApiResponse error($message = null, $data = [], ...$extraData)
 *
 * @see APIResponse
 */
class Api extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ApiInterface::class;
    }
}