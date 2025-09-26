<?php

declare(strict_types=1);

namespace App\Exception;

use App\Constants\ErrorMessages;

class ServiceException extends \Exception
{
    public function __construct(string $message = ErrorMessages::DEFAULT_SERVICE_EXCEPTION, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
