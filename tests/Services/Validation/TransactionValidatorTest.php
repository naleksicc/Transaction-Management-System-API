<?php

declare(strict_types=1);

namespace App\Tests\Services\Validation;

use App\Exception\ValidationException;
use App\Services\Validation\TransactionValidator;
use PHPUnit\Framework\TestCase;

class TransactionValidatorTest extends TestCase
{
    private TransactionValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new TransactionValidator();
    }

    public function testValidateWithValidData(): void
    {
        $validData = [
            'transactionDate' => '2025-09-25',
            'accountNumber' => '1234-5678-9012',
            'accountHolderName' => 'John Doe',
            'amount' => 100.50
        ];

        // Should not throw any exception
        $this->validator->validate($validData);
        $this->assertTrue(true); // If we get here, validation passed
    }

    public function testValidateThrowsExceptionForMissingTransactionDate(): void
    {
        $invalidData = [
            'accountNumber' => '1234-5678-9012',
            'accountHolderName' => 'John Doe',
            'amount' => 100.50
        ];

        $this->expectException(ValidationException::class);
        $this->validator->validate($invalidData);
    }

    public function testValidateThrowsExceptionForInvalidDateFormat(): void
    {
        $invalidData = [
            'transactionDate' => '25/09/2025', // Wrong format
            'accountNumber' => '1234-5678-9012',
            'accountHolderName' => 'John Doe',
            'amount' => 100.50
        ];

        $this->expectException(ValidationException::class);
        $this->validator->validate($invalidData);
    }

    public function testValidateThrowsExceptionForInvalidAccountNumber(): void
    {
        $invalidData = [
            'transactionDate' => '2025-09-25',
            'accountNumber' => '12345678', // Wrong format
            'accountHolderName' => 'John Doe',
            'amount' => 100.50
        ];

        $this->expectException(ValidationException::class);
        $this->validator->validate($invalidData);
    }

    public function testValidateThrowsExceptionForShortName(): void
    {
        $invalidData = [
            'transactionDate' => '2025-09-25',
            'accountNumber' => '1234-5678-9012',
            'accountHolderName' => 'J', // Too short
            'amount' => 100.50
        ];

        $this->expectException(ValidationException::class);
        $this->validator->validate($invalidData);
    }

    public function testValidateThrowsExceptionForNegativeAmount(): void
    {
        $invalidData = [
            'transactionDate' => '2025-09-25',
            'accountNumber' => '1234-5678-9012',
            'accountHolderName' => 'John Doe',
            'amount' => -50.00 // Negative amount
        ];

        $this->expectException(ValidationException::class);
        $this->validator->validate($invalidData);
    }

    public function testValidateWithMultipleErrorsReturnsAllViolations(): void
    {
        $invalidData = [
            'transactionDate' => '',
            'accountNumber' => 'invalid',
            'accountHolderName' => '',
            'amount' => -10
        ];

        try {
            $this->validator->validate($invalidData);
            $this->fail('Expected ValidationException was not thrown');
        } catch (ValidationException $e) {
            $violations = $e->getViolations();

            $this->assertArrayHasKey('transactionDate', $violations);
            $this->assertArrayHasKey('accountNumber', $violations);
            $this->assertArrayHasKey('accountHolderName', $violations);
            $this->assertArrayHasKey('amount', $violations);
        }
    }
}
