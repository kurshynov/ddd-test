<?php

declare(strict_types=1);

namespace App\Domain\Loan\Rule;

use App\Domain\Loan\Entity\LoanEligibilityResult;
use App\Domain\User\Entity\User;

final class ScoreRule implements RuleInterface
{
    public function apply(User $user, LoanEligibilityResult $result): void
    {
        if ($user->getScore() <= 500) {
            $result->reject('Кредитный рейтинг ниже 500.');
        }
    }
}
