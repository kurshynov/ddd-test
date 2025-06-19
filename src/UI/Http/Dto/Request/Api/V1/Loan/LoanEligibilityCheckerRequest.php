<?php

declare(strict_types=1);

namespace App\UI\Http\Dto\Request\Api\V1\Loan;

use App\UI\Http\Dto\Request\BaseDtoRequest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints as Assert;

final class LoanEligibilityCheckerRequest extends BaseDtoRequest
{
    #region [properties]

    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    #[Assert\GreaterThanOrEqual(0)]
    public int $userId; // User ID for whom the loan eligibility is being checked.

    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    #[Assert\GreaterThanOrEqual(0)]
    public int $loanId; // Loan ID for which the eligibility is being checked.

    #endregion

    public function __construct(
        protected RequestStack $requestStack,
        protected LoggerInterface $logger
    ) {
        parent::__construct(
            requestStack: $requestStack,
            logger: $this->logger
        );

        if ($this->getJsonArray()) {
            $this->userId = $this->getJsonArrayByField('user_id', 'int') ?: 0;
            $this->loanId = $this->getJsonArrayByField('loan_id', 'int') ?: 0;
        }
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
