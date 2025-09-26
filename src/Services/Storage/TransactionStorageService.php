<?php

declare(strict_types=1);

namespace App\Services\Storage;

use App\Constants\StorageConstants;
use App\Constants\ErrorMessages;
use App\Exception\StorageException;
use App\Services\Status\TransactionStatusInterface;

class TransactionStorageService implements TransactionStorageInterface
{
    private array $headers = StorageConstants::CSV_HEADERS;

    public function __construct(
        private readonly TransactionStatusInterface $statusService,
        private readonly string $csvPath
    ) {
        $directory = dirname($this->csvPath);
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true)) {
                throw new StorageException(ErrorMessages::FAILED_TO_CREATE_DIRECTORY . ": {$directory}");
            }
        }

        if (!file_exists($this->csvPath)) {
            if (file_put_contents($this->csvPath, implode(',', $this->headers) . "\n") === false) {
                throw new StorageException(ErrorMessages::FAILED_TO_CREATE_CSV_FILE . ": {$this->csvPath}");
            }
        }
    }

    public function readAll(): array
    {
        try {
            $rows = [];

            if (!file_exists($this->csvPath)) {
                throw new StorageException(ErrorMessages::CSV_FILE_DOES_NOT_EXIST . ": {$this->csvPath}");
            }

            if (!is_readable($this->csvPath)) {
                throw new StorageException(ErrorMessages::CSV_FILE_NOT_READABLE . ": {$this->csvPath}");
            }

            $handle = fopen($this->csvPath, 'r');
            if ($handle === false) {
                throw new StorageException(ErrorMessages::FAILED_TO_OPEN_CSV_FOR_READING . ": {$this->csvPath}");
            }

            $fileHeaders = fgetcsv($handle);
            if ($fileHeaders === false) {
                fclose($handle);
                throw new StorageException(ErrorMessages::FAILED_TO_READ_CSV_HEADERS);
            }

            while (($data = fgetcsv($handle)) !== false) {
                if (count($data) !== count($fileHeaders)) {
                    continue; // Skip malformed rows
                }
                $rows[] = array_combine($fileHeaders, $data);
            }

            fclose($handle);
            return $rows;
        } catch (\Exception $e) {
            throw new StorageException(ErrorMessages::ERROR_READING_FROM_CSV . ": " . $e->getMessage(), 0, $e);
        }
    }

    public function appendRow(array $row): void
    {
        try {
            if (!is_writable(dirname($this->csvPath))) {
                throw new StorageException(ErrorMessages::DIRECTORY_NOT_WRITABLE . ": " . dirname($this->csvPath));
            }

            $handle = fopen($this->csvPath, 'a');
            if ($handle === false) {
                throw new StorageException(ErrorMessages::FAILED_TO_OPEN_CSV_FOR_WRITING . ": {$this->csvPath}");
            }

            if (fputcsv($handle, $row) === false) {
                fclose($handle);
                throw new StorageException(ErrorMessages::FAILED_TO_WRITE_ROW_TO_CSV);
            }

            fclose($handle);
        } catch (\Exception $e) {
            throw new StorageException(ErrorMessages::ERROR_WRITING_TO_CSV . ": " . $e->getMessage(), 0, $e);
        }
    }

    public function createTransaction(array $data): array
    {
        $status = $this->statusService->getRandomStatus();

        $transaction = [
            'Transaction Date' => $data['transactionDate'],
            'Account Number' => $data['accountNumber'],
            'Account Holder Name' => $data['accountHolderName'],
            'Amount' => $data['amount'],
            'Status' => $status->value,
        ];

        $this->appendRow($transaction);

        return $transaction;
    }
}
