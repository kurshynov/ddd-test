<?php

declare(strict_types=1);

namespace App\Domain\Loan\Rule;

use App\Domain\Loan\Entity\LoanEligibilityResult;
use App\Domain\Loan\Model\LoanApplication;

final class ScoreRule implements RuleInterface
{
    public function supports(LoanApplication $application): bool
    {
        return true;
    }

    public function check(LoanApplication $application, LoanEligibilityResult $loanEligibilityResult): void
    {
        $user = $application->getUser();

        if ($user->getScore() <= 500) {
            $loanEligibilityResult->reject('Кредитный рейтинг ниже 500.');
        }
    }
}
