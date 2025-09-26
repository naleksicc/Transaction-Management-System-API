<?php

declare(strict_types=1);

namespace App\Services\Transaction;

interface TransactionServiceInterface
{
    public function getAll(): array;

    public function create(array $data): array;
}
