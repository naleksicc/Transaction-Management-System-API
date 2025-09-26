<?php

declare(strict_types=1);

namespace App\Services\Randomizer;

use App\Constants\ErrorMessages;

class Randomizer implements RandomizerInterface
{
    public function choice(array $items): mixed
    {
        if (empty($items)) {
            throw new \InvalidArgumentException(ErrorMessages::CANNOT_CHOOSE_FROM_EMPTY_ARRAY);
        }

        return $items[array_rand($items)];
    }
}
