<?php

declare(strict_types=1);

namespace App\Tests\Services\Storage;

use App\Services\Storage\TransactionStorageService;
use App\Services\Status\TransactionStatusInterface;
use App\Enum\TransactionStatus;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class TransactionStorageServiceTest extends TestCase
{
    private string $testCsvPath;
    private TransactionStorageService $service;
    private TransactionStatusInterface|MockObject $mockStatusService;

    protected function setUp(): void
    {
        // Create mock status service
        $this->mockStatusService = $this->createMock(TransactionStatusInterface::class);

        // Create a temporary test file
        $this->testCsvPath = sys_get_temp_dir() . '/test_transactions_' . uniqid() . '.csv';
        $this->service = new TransactionStorageService($this->mockStatusService, $this->testCsvPath);
    }

    protected function tearDown(): void
    {
        // Clean up test file
        if (file_exists($this->testCsvPath)) {
            unlink($this->testCsvPath);
        }
    }

    public function testConstructorCreatesFileWithHeaders(): void
    {
        $this->assertTrue(file_exists($this->testCsvPath));

        $content = file_get_contents($this->testCsvPath);
        $expectedHeaders = "Transaction Date,Account Number,Account Holder Name,Amount,Status\n";

        $this->assertEquals($expectedHeaders, $content);
    }

    public function testAppendRowAddsDataToFile(): void
    {
        $testData = [
            'Transaction Date' => '2025-09-25',
            'Account Number' => '1234-5678-9012',
            'Account Holder Name' => 'John Doe',
            'Amount' => 100.50,
            'Status' => 'Settled'
        ];

        $this->service->appendRow($testData);

        $content = file_get_contents($this->testCsvPath);
        $lines = explode("\n", trim($content));

        // Should have header + 1 data row
        $this->assertCount(2, $lines);

        // Check the data row
        $this->assertStringContainsString('2025-09-25', $lines[1]);
        $this->assertStringContainsString('1234-5678-9012', $lines[1]);
        $this->assertStringContainsString('John Doe', $lines[1]);
        $this->assertStringContainsString('100.5', $lines[1]);
        $this->assertStringContainsString('Settled', $lines[1]);
    }

    public function testReadAllReturnsEmptyArrayForEmptyFile(): void
    {
        $result = $this->service->readAll();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testReadAllReturnsCorrectDataStructure(): void
    {
        // Add test data
        $testData1 = [
            'Transaction Date' => '2025-09-25',
            'Account Number' => '1234-5678-9012',
            'Account Holder Name' => 'John Doe',
            'Amount' => 100.50,
            'Status' => 'Settled'
        ];

        $testData2 = [
            'Transaction Date' => '2025-09-24',
            'Account Number' => '2345-6789-0123',
            'Account Holder Name' => 'Jane Smith',
            'Amount' => 75.25,
            'Status' => 'Pending'
        ];

        $this->service->appendRow($testData1);
        $this->service->appendRow($testData2);

        $result = $this->service->readAll();

        $this->assertIsArray($result);
        $this->assertCount(2, $result);

        // Check first row structure
        $this->assertArrayHasKey('Transaction Date', $result[0]);
        $this->assertArrayHasKey('Account Number', $result[0]);
        $this->assertArrayHasKey('Account Holder Name', $result[0]);
        $this->assertArrayHasKey('Amount', $result[0]);
        $this->assertArrayHasKey('Status', $result[0]);

        $this->assertEquals('2025-09-25', $result[0]['Transaction Date']);
        $this->assertEquals('1234-5678-9012', $result[0]['Account Number']);
        $this->assertEquals('John Doe', $result[0]['Account Holder Name']);
    }

    public function testCreateTransactionBuildsAndStoresTransaction(): void
    {
        $inputData = [
            'transactionDate' => '2025-09-25',
            'accountNumber' => '1234-5678-9012',
            'accountHolderName' => 'John Doe',
            'amount' => 100.50
        ];

        $this->mockStatusService
            ->expects($this->once())
            ->method('getRandomStatus')
            ->willReturn(TransactionStatus::Settled);

        $result = $this->service->createTransaction($inputData);

        // Check the returned transaction structure
        $this->assertIsArray($result);
        $this->assertEquals('2025-09-25', $result['Transaction Date']);
        $this->assertEquals('1234-5678-9012', $result['Account Number']);
        $this->assertEquals('John Doe', $result['Account Holder Name']);
        $this->assertEquals(100.50, $result['Amount']);
        $this->assertEquals('Settled', $result['Status']);

        // Verify it was written to file
        $storedData = $this->service->readAll();
        $this->assertCount(1, $storedData);
        $this->assertEquals($result, $storedData[0]);
    }

    public function testMultipleAppendRowsWork(): void
    {
        $testData1 = [
            'Transaction Date' => '2025-09-25',
            'Account Number' => '1234-5678-9012',
            'Account Holder Name' => 'John Doe',
            'Amount' => 100.50,
            'Status' => 'Settled'
        ];

        $testData2 = [
            'Transaction Date' => '2025-09-24',
            'Account Number' => '2345-6789-0123',
            'Account Holder Name' => 'Jane Smith',
            'Amount' => 75.25,
            'Status' => 'Pending'
        ];

        $this->service->appendRow($testData1);
        $this->service->appendRow($testData2);

        $result = $this->service->readAll();
        $this->assertCount(2, $result);
    }
}
