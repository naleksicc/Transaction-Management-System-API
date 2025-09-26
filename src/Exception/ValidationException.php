<?php

declare(strict_types=1);

namespace App\Exception;

use App\Constants\ErrorMessages;

class ValidationException extends \Exception
{
    private array $violations;

    public function __construct(array $violations, string $message = ErrorMessages::DEFAULT_VALIDATION_EXCEPTION)
    {
        $this->violations = $violations;
        parent::__construct($message);
    }

    public function getViolations(): array
    {
        return $this->violations;
    }
}
