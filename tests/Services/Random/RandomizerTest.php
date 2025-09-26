<?php

declare(strict_types=1);

namespace App\Tests\Services\Random;

use App\Services\Randomizer\Randomizer;
use PHPUnit\Framework\TestCase;

class RandomizerTest extends TestCase
{
    private Randomizer $randomizer;

    protected function setUp(): void
    {
        $this->randomizer = new Randomizer();
    }

    public function testChoiceReturnsElementFromArray(): void
    {
        $items = ['apple', 'banana', 'cherry'];

        $result = $this->randomizer->choice($items);

        $this->assertContains($result, $items);
    }

    public function testChoiceWithSingleElementReturnsThatElement(): void
    {
        $items = ['only_item'];

        $result = $this->randomizer->choice($items);

        $this->assertEquals('only_item', $result);
    }

    public function testChoiceThrowsExceptionForEmptyArray(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot choose from empty array');

        $this->randomizer->choice([]);
    }

    public function testChoiceProducesVarietyOverMultipleCalls(): void
    {
        $items = ['a', 'b', 'c', 'd', 'e'];
        $results = [];

        // Run choice() multiple times to increase chance of getting different values
        for ($i = 0; $i < 100; $i++) {
            $result = $this->randomizer->choice($items);
            $results[$result] = true;
        }

        // We should get at least 3 different results in 100 attempts (very likely)
        $this->assertGreaterThanOrEqual(3, count($results));
    }
}
