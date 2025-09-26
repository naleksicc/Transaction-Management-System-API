<?php

declare(strict_types=1);

namespace App\Services\Status;

use App\Enum\TransactionStatus;
use App\Services\Randomizer\RandomizerInterface;
use App\Exception\ServiceException;
use App\Constants\ErrorMessages;

class TransactionStatusService implements TransactionStatusInterface
{
    public function __construct(
        private readonly RandomizerInterface $randomizer
    ) {
    }

    public function getRandomStatus(): TransactionStatus
    {
        try {
            $statuses = TransactionStatus::cases();

            if (empty($statuses)) {
                throw new ServiceException(ErrorMessages::NO_TRANSACTION_STATUSES_AVAILABLE);
            }

            return $this->randomizer->choice($statuses);
        } catch (\InvalidArgumentException $e) {
            throw new ServiceException(ErrorMessages::FAILED_TO_SELECT_RANDOM_STATUS . ": " . $e->getMessage(), 0, $e);
        } catch (\Exception $e) {
            throw new ServiceException(ErrorMessages::UNEXPECTED_ERROR_GENERATING_STATUS . ": " . $e->getMessage(), 0, $e);
        }
    }
}
