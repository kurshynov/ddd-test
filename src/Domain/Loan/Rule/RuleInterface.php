<?php

declare(strict_types=1);

namespace App\Domain\Loan\Rule;

use App\Domain\Loan\Entity\LoanEligibilityResult;
use App\Domain\Loan\Model\LoanApplication;

interface RuleInterface
{
    public function supports(LoanApplication $application): bool;

    public function check(LoanApplication $application, LoanEligibilityResult $loanEligibilityResult): void;
}
