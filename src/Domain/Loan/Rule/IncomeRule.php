<?php

declare(strict_types=1);

namespace App\Domain\Loan\Rule;

use App\Domain\Loan\Entity\LoanEligibilityResult;
use App\Domain\Loan\Model\LoanApplication;

final class IncomeRule implements RuleInterface
{
    public function supports(LoanApplication $application): bool
    {
        return true;
    }

    public function check(LoanApplication $application, LoanEligibilityResult $loanEligibilityResult): void
    {
        $user = $application->getUser();
        // Проверка дохода клиента.
        if ($user->getIncome() < 1000) {
            $loanEligibilityResult->reject('Доход клиента ниже 1000.');
        }
    }
}
