<?php

declare(strict_types=1);

namespace App\UI\Http\Dto\Response\Api\V1\Loan;

use App\UI\Http\Dto\Response\BaseDtoResponse;

final class LoanEligibilityCheckerResponse extends BaseDtoResponse
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
     * @var float|null Adjusted interest rate if applicable.
     */
    private ?float $adjustedRate = null;

    #endregion

    #region [Getters & Setters]

    public function isEligible(): bool
    {
        return $this->isEligible;
    }

    public function setIsEligible(bool $isEligible): LoanEligibilityCheckerResponse
    {
        $this->isEligible = $isEligible;

        return $this;
    }

    public function getReasons(): array
    {
        return $this->reasons;
    }

    public function setReasons(array $reasons): LoanEligibilityCheckerResponse
    {
        $this->reasons = $reasons;

        return $this;
    }

    public function getAdjustedRate(): ?float
    {
        return $this->adjustedRate;
    }

    public function setAdjustedRate(?float $adjustedRate): LoanEligibilityCheckerResponse
    {
        $this->adjustedRate = $adjustedRate;

        return $this;
    }

    #endregion
}
