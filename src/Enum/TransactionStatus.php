<?php

declare(strict_types=1);

namespace App\Enum;

enum TransactionStatus: string
{
    case Pending = 'Pending';
    case Settled = 'Settled';
    case Failed = 'Failed';
}
