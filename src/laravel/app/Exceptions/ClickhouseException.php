<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class ClickhouseException extends Exception
{
    /**
     * Report the exception.
     */
    public function report()
    {
        logger()->channel('telegram')->error('ClickhouseException', [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
        ]);
        logger()->error($this);
    }
}
