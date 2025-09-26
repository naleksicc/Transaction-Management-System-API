<?php

declare(strict_types=1);

namespace App\Services\Status;

use App\Enum\TransactionStatus;

interface TransactionStatusInterface
{
    public function getRandomStatus(): TransactionStatus;
}
