<?php

declare(strict_types=1);

namespace App\Services\Storage;

interface TransactionStorageInterface
{
    public function readAll(): array;
    public function appendRow(array $row): void;
    public function createTransaction(array $data): array;
}
