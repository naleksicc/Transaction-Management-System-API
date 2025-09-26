<?php

declare(strict_types=1);

namespace App\Constants;

final class ErrorMessages
{
    // Validation Error Messages
    public const TRANSACTION_DATE_REQUIRED = 'Transaction date is required';
    public const TRANSACTION_DATE_INVALID_FORMAT = 'Transaction date must be a valid date (YYYY-MM-DD format)';
    public const ACCOUNT_NUMBER_REQUIRED = 'Account number is required';
    public const ACCOUNT_NUMBER_INVALID_FORMAT = 'Account number must be in format XXXX-XXXX-XXXX';
    public const ACCOUNT_HOLDER_NAME_REQUIRED = 'Account holder name is required';
    public const ACCOUNT_HOLDER_NAME_INVALID = 'Account holder name must be between 2-100 characters and contain only letters and spaces';
    public const AMOUNT_REQUIRED = 'Amount is required';
    public const AMOUNT_INVALID = 'Amount must be a positive number';

    // System Error Messages
    public const SERVICE_ERROR = 'Service error';
    public const STORAGE_ERROR = 'Storage error';
    public const INTERNAL_SERVER_ERROR = 'Internal server error';
    public const UNEXPECTED_ERROR_MESSAGE = 'An unexpected error occurred';
    public const VALIDATION_FAILED = 'Validation failed';

    // Status Service Error Messages
    public const NO_TRANSACTION_STATUSES_AVAILABLE = 'No transaction statuses available';
    public const FAILED_TO_SELECT_RANDOM_STATUS = 'Failed to select random transaction status';
    public const UNEXPECTED_ERROR_GENERATING_STATUS = 'Unexpected error generating transaction status';

    // Randomizer Service Error Messages
    public const CANNOT_CHOOSE_FROM_EMPTY_ARRAY = 'Cannot choose from empty array';

    // Default Exception Messages
    public const DEFAULT_SERVICE_EXCEPTION = 'Service operation failed';
    public const DEFAULT_STORAGE_EXCEPTION = 'Storage operation failed';
    public const DEFAULT_VALIDATION_EXCEPTION = 'Validation failed';

    // Storage Service Error Messages
    public const CSV_FILE_DOES_NOT_EXIST = 'CSV file does not exist at path';
    public const CSV_FILE_NOT_READABLE = 'CSV file is not readable at path';
    public const FAILED_TO_OPEN_CSV_FOR_READING = 'Failed to open CSV file for reading';
    public const FAILED_TO_READ_CSV_HEADERS = 'Failed to read headers from CSV file';
    public const ERROR_READING_FROM_CSV = 'Error reading from CSV file';
    public const DIRECTORY_NOT_WRITABLE = 'Directory is not writable';
    public const FAILED_TO_OPEN_CSV_FOR_WRITING = 'Failed to open CSV file for writing';
    public const FAILED_TO_WRITE_ROW_TO_CSV = 'Failed to write row to CSV file';
    public const ERROR_WRITING_TO_CSV = 'Error writing to CSV file';
    public const FAILED_TO_CREATE_DIRECTORY = 'Failed to create directory';
    public const FAILED_TO_CREATE_CSV_FILE = 'Failed to create CSV file';
}
