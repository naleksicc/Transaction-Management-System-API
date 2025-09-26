<?php

declare(strict_types=1);

namespace App\Exception;

use App\Constants\ErrorMessages;

class StorageException extends \Exception
{
    public function __construct(string $message = ErrorMessages::DEFAULT_STORAGE_EXCEPTION, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
