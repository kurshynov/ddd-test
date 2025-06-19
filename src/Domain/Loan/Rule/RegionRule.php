<?php

declare(strict_types=1);

namespace App\Domain\Loan\Rule;

use App\Domain\Loan\Entity\LoanEligibilityResult;
use App\Domain\User\Entity\User;

final class RegionRule implements RuleInterface
{
    public function apply(User $user, LoanEligibilityResult $result): void
    {
        if (!in_array($user->getRegion(), ['PR', 'BR', 'OS'])) {
            $result->reject('Регион пользователя не поддерживается для кредитования.');
        }
    }
}
