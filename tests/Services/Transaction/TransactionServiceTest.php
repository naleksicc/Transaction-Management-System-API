<?php

declare(strict_types=1);

namespace App\Tests\Services\Transaction;

use App\Services\Storage\TransactionStorageInterface;
use App\Services\Transaction\TransactionService;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class TransactionServiceTest extends TestCase
{
    private TransactionService $service;
    private TransactionStorageInterface|MockObject $mockStorage;

    protected function setUp(): void
    {
        $this->mockStorage = $this->createMock(TransactionStorageInterface::class);

        $this->service = new TransactionService($this->mockStorage);
    }

    public function testGetAllReturnsStorageData(): void
    {
        $expectedData = [
            [
                'Transaction Date' => '2025-09-25',
                'Account Number' => '1234-5678-9012',
                'Account Holder Name' => 'John Doe',
                'Amount' => '100.50',
                'Status' => 'Settled'
            ],
            [
                'Transaction Date' => '2025-09-24',
                'Account Number' => '2345-6789-0123',
                'Account Holder Name' => 'Jane Smith',
                'Amount' => '75.25',
                'Status' => 'Pending'
            ]
        ];

        $this->mockStorage
            ->expects($this->once())
            ->method('readAll')
            ->willReturn($expectedData);

        $result = $this->service->getAll();

        $this->assertEquals($expectedData, $result);
    }

    public function testCreateDelegatesToStorage(): void
    {
        $inputData = [
            'transactionDate' => '2025-09-25',
            'accountNumber' => '1234-5678-9012',
            'accountHolderName' => 'John Doe',
            'amount' => 100.50
        ];

        $expectedTransaction = [
            'Transaction Date' => '2025-09-25',
            'Account Number' => '1234-5678-9012',
            'Account Holder Name' => 'John Doe',
            'Amount' => 100.50,
            'Status' => 'Settled'
        ];

        $this->mockStorage
            ->expects($this->once())
            ->method('createTransaction')
            ->with($inputData)
            ->willReturn($expectedTransaction);

        $result = $this->service->create($inputData);

        $this->assertEquals($expectedTransaction, $result);
    }
}
