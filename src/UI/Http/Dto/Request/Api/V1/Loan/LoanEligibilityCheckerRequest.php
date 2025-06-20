<?php

declare(strict_types=1);

namespace App\UI\Http\Dto\Request\Api\V1\Loan;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class LoanEligibilityCheckerRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        #[Assert\GreaterThanOrEqual(0)]
        private int $userId, // User ID for whom the loan eligibility is being checked.

        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        #[Assert\GreaterThanOrEqual(0)]
        private int $loanId, // Loan ID for which the eligibility is being checked.
    )
    {
    }

    #region [Getters]

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getLoanId(): int
    {
        return $this->loanId;
    }

    #endregion
}
