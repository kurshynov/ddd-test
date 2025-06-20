<?php

declare(strict_types=1);

namespace App\Domain\Loan\Service;

use App\Domain\Loan\Entity\LoanEligibilityResult;
use App\Domain\Loan\Model\LoanApplication;
use App\Domain\Loan\Processor\RuleProcessor;

final readonly class LoanEligibilityCheckerService
{
    public function __construct(
        private RuleProcessor $ruleProcessor
    ) {
    }

    /**
     * Checks the eligibility of a loan application.
     *
     * @param LoanApplication $application The loan application to check.
     * @return LoanEligibilityResult The result of the eligibility check.
     */
    public function check(LoanApplication $application): LoanEligibilityResult
    {
        $loanEligibilityResult = $this->ruleProcessor->evaluate($application);

        if ($loanEligibilityResult->isEligible()) {
            $baseRate = $application->getLoan()->getRate();
            $adjustedRate = $loanEligibilityResult->getAdjustedRate();
            $finalRate = $baseRate + $adjustedRate;

            $loanEligibilityResult->increaseRate($finalRate);
        }

        return $loanEligibilityResult;
    }
}
