<?php

declare(strict_types=1);

namespace ModelChangesHistory\Exceptions;

class StorageNotFoundException extends \InvalidArgumentException
{
    protected $message = 'No current storage found or installed.';
}
