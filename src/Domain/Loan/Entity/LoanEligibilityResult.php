<?php

declare(strict_types=1);

namespace App\Domain\Loan\Entity;

final class LoanEligibilityResult
{
    #region [properties]

    /**
     * @var bool Indicates if the user is eligible for a loan.
     */
    private bool $isEligible = true;

    /**
     * @var string[] Reasons for ineligibility or adjustments.
     */
    private array $reasons = [];

    /**
     * @var float Adjusted interest rate if applicable.
     */
    private float $adjustedRate = 0;

    #endregion

    /**
     * Rejects the loan eligibility for the user with a reason.
     *
     * @param string $reason
     * @return void
     */
    public function reject(string $reason): void
    {
        $this->isEligible = false;
        $this->reasons[] = $reason;
    }

    public function increaseRate(float $percent): void
    {
        $this->adjustedRate = $percent;
    }

    #region [Getters]

    public function isEligible(): bool
    {
        return $this->isEligible;
    }

    public function getReasons(): array
    {
        return $this->reasons;
    }

    public function getAdjustedRate(): float
    {
        return $this->adjustedRate;
    }

    #endregion
}
