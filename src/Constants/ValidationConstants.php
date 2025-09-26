<?php

declare(strict_types=1);

namespace App\Constants;

final class ValidationConstants
{
    // Validation Rules
    public const NAME_MIN_LENGTH = 2;
    public const NAME_MAX_LENGTH = 100;
    public const ACCOUNT_NUMBER_PATTERN = '/^\d{4}-\d{4}-\d{4}$/';
    public const DATE_FORMAT = 'Y-m-d';
    public const NAME_PATTERN = '/^[a-zA-Z\s]+$/';
}
