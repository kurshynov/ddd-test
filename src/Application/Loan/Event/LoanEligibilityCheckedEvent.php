<?php

declare(strict_types=1);

namespace App\Application\Loan\Event;


final readonly class LoanEligibilityCheckedEvent
{
    public function __construct(
        private int $userId,
        private bool $isEligible,
        private float $rate,
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function isEligible(): bool
    {
        return $this->isEligible;
    }

    public function getRate(): float
    {
        return $this->rate;
    }
}
