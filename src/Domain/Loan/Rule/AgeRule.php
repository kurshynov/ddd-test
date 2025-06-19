<?php

declare(strict_types=1);

namespace App\Domain\Loan\Rule;

use App\Domain\Loan\Entity\LoanEligibilityResult;
use App\Domain\User\Entity\User;

final class AgeRule implements RuleInterface
{
    public function apply(User $user, LoanEligibilityResult $result): void
    {
        // Возраст клиента от 18 до 60 лет. todo: до 60 лет включительно?
        if ($user->getAge() < 18 || $user->getAge() >= 60) {
            $result->reject('Возраст клиента должен быть от 18 до 60 лет.');
        }
    }
}
