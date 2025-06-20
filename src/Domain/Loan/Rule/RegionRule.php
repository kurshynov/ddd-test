<?php

declare(strict_types=1);

namespace App\Domain\Loan\Rule;

use App\Domain\Loan\Entity\LoanEligibilityResult;
use App\Domain\Loan\Enum\RegionEnum;
use App\Domain\User\Entity\User;

final class RegionRule implements RuleInterface
{
    public function apply(User $user, LoanEligibilityResult $result): void
    {
        if (!in_array($user->getRegion(), [
            RegionEnum::PRAHA->value,
            RegionEnum::BRNO->value,
            RegionEnum::OSTRAVA->value,
        ])) {
            $result->reject('Регион пользователя не поддерживается для кредитования.');
        }
    }
}
