<?php

declare(strict_types=1);

namespace App\Tests\Services\Status;

use App\Enum\TransactionStatus;
use App\Services\Randomizer\RandomizerInterface;
use App\Services\Status\TransactionStatusService;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class TransactionStatusServiceTest extends TestCase
{
    private TransactionStatusService $service;
    private RandomizerInterface|MockObject $mockRandomizer;

    protected function setUp(): void
    {
        $this->mockRandomizer = $this->createMock(RandomizerInterface::class);
        $this->service = new TransactionStatusService($this->mockRandomizer);
    }

    public function testGetRandomStatusReturnsRandomizerChoice(): void
    {
        $expectedStatus = TransactionStatus::Settled;

        $this->mockRandomizer
            ->expects($this->once())
            ->method('choice')
            ->with(TransactionStatus::cases())
            ->willReturn($expectedStatus);

        $result = $this->service->getRandomStatus();

        $this->assertEquals($expectedStatus, $result);
    }

    public function testGetRandomStatusCallsRandomizerWithAllStatuses(): void
    {
        $this->mockRandomizer
            ->expects($this->once())
            ->method('choice')
            ->with($this->equalTo(TransactionStatus::cases()))
            ->willReturn(TransactionStatus::Pending);

        $this->service->getRandomStatus();
    }
}
