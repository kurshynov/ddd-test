<?php

declare(strict_types=1);

namespace App\Domain\Loan\Rule;

use App\Domain\Loan\Entity\LoanEligibilityResult;
use App\Domain\User\Entity\User;

final class IncomeRule implements RuleInterface
{
    public function apply(User $user, LoanEligibilityResult $result): void
    {
        if ($user->getIncome() < 1000) {
            $result->reject('Доход клиента ниже 1000.');
        }
    }
}
