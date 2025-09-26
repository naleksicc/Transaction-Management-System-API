<?php

declare(strict_types=1);

namespace App\Services\Randomizer;

interface RandomizerInterface
{
    public function choice(array $items): mixed;
}
