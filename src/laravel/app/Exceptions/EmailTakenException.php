<?php

namespace App\Exceptions;

use Exception;

class EmailTakenException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return api()->validation('Email already taken');
    }
}
