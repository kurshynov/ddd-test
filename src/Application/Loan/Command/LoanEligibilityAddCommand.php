<?php

declare(strict_types=1);

namespace App\Application\Loan\Command;

use App\Domain\Loan\ValueObject\Rate;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;

final readonly class LoanEligibilityAddCommand
{
    public function __construct(
        private string $name,
        private int $amount,
        private string $rate,
        private string $startDate,
        private string $endDate
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getRate(): float
    {
        return (new Rate($this->rate))->getValue();
    }

    /**
     * @throws Exception
     */
    public function getStartDate(): DateTimeInterface
    {
        return new DateTimeImmutable($this->startDate);
    }

    /**
     * @throws Exception
     */
    public function getEndDate(): DateTimeInterface
    {
        return new DateTimeImmutable($this->endDate);
    }
}
