<?php

declare(strict_types=1);

namespace App\Domain\Loan\Rule;

use App\Domain\Loan\Entity\LoanEligibilityResult;
use App\Domain\Loan\Model\LoanApplication;

final class AgeRule implements RuleInterface
{
    public function supports(LoanApplication $application): bool
    {
        return true;
    }

    public function check(LoanApplication $application, LoanEligibilityResult $loanEligibilityResult): void
    {
        $user = $application->getUser();
        // Возраст клиента от 18 до 60 лет.
        if ($user->getAge() < 18 || $user->getAge() >= 60) {
            $loanEligibilityResult->reject('Возраст клиента должен быть от 18 до 60 лет.');
        }
    }
}
