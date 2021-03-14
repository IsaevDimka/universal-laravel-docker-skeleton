<?php

declare(strict_types=1);

namespace IsaevDimka\RussianPost\Exceptions;

class RussianPostTarrificatorException extends \Exception
{
    /**
     * @var array
     */
    private $errors = [];

    public function __construct($message = '', $code = 0, $errors = [])
    {
        $this->setErrors($errors);
        parent::__construct($message, $code);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }
}
