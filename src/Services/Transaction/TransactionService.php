<?php

declare(strict_types=1);

namespace App\Services\Transaction;

use App\Services\Storage\TransactionStorageInterface;

class TransactionService implements TransactionServiceInterface
{
    public function __construct(
        private readonly TransactionStorageInterface $storageService
    ) {
    }

    public function getAll(): array
    {
        return $this->storageService->readAll();
    }

    public function create(array $data): array
    {
        return $this->storageService->createTransaction($data);
    }
}
