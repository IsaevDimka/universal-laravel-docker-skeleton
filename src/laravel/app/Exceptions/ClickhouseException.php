<?php

namespace App\Exceptions;

use Exception;

class ClickhouseException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        logger()->channel('telegram')->error('ClickhouseException', [
            'message' => $this->getMessage(),
            'code'    => $this->getCode(),
        ]);
        logger()->error($this);
    }
}
