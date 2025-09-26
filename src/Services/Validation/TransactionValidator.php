<?php

declare(strict_types=1);

namespace App\Services\Validation;

use App\Constants\ValidationConstants;
use App\Constants\ErrorMessages;
use App\Exception\ValidationException;

class TransactionValidator
{
    public function validate(array $data): void
    {
        $errors = [];

        if (empty($data['transactionDate'])) {
            $errors['transactionDate'] = ErrorMessages::TRANSACTION_DATE_REQUIRED;
        } elseif (!$this->isValidDate($data['transactionDate'])) {
            $errors['transactionDate'] = ErrorMessages::TRANSACTION_DATE_INVALID_FORMAT;
        }

        if (empty($data['accountNumber'])) {
            $errors['accountNumber'] = ErrorMessages::ACCOUNT_NUMBER_REQUIRED;
        } elseif (!$this->isValidAccountNumber($data['accountNumber'])) {
            $errors['accountNumber'] = ErrorMessages::ACCOUNT_NUMBER_INVALID_FORMAT;
        }

        if (empty($data['accountHolderName'])) {
            $errors['accountHolderName'] = ErrorMessages::ACCOUNT_HOLDER_NAME_REQUIRED;
        } elseif (!$this->isValidName($data['accountHolderName'])) {
            $errors['accountHolderName'] = ErrorMessages::ACCOUNT_HOLDER_NAME_INVALID;
        }

        if (!isset($data['amount']) || $data['amount'] === '') {
            $errors['amount'] = ErrorMessages::AMOUNT_REQUIRED;
        } elseif (!$this->isValidAmount($data['amount'])) {
            $errors['amount'] = ErrorMessages::AMOUNT_INVALID;
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
    }

    private function isValidDate(string $date): bool
    {
        $d = \DateTime::createFromFormat(ValidationConstants::DATE_FORMAT, $date);
        return $d && $d->format(ValidationConstants::DATE_FORMAT) === $date;
    }

    private function isValidAccountNumber(string $accountNumber): bool
    {
        return preg_match(ValidationConstants::ACCOUNT_NUMBER_PATTERN, $accountNumber) === 1;
    }

    private function isValidName(string $name): bool
    {
        $trimmedName = trim($name);

        if (strlen($trimmedName) < ValidationConstants::NAME_MIN_LENGTH || strlen($trimmedName) > ValidationConstants::NAME_MAX_LENGTH) {
            return false;
        }

        return preg_match(ValidationConstants::NAME_PATTERN, $trimmedName) === 1;
    }

    private function isValidAmount($amount): bool
    {
        return is_numeric($amount) && (float) $amount > 0;
    }
}
