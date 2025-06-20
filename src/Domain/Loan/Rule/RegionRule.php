<?php

declare(strict_types=1);

namespace App\Domain\Loan\Rule;

use App\Domain\Loan\Entity\LoanEligibilityResult;
use App\Domain\Loan\Enum\RegionEnum;
use App\Domain\Loan\Model\LoanApplication;

final class RegionRule implements RuleInterface
{
    public function supports(LoanApplication $application): bool
    {
        return true;
    }

    public function check(LoanApplication $application, LoanEligibilityResult $loanEligibilityResult): void
    {
        $user = $application->getUser();

        if (!in_array($user->getRegion(), [
            RegionEnum::PRAHA->value,
            RegionEnum::BRNO->value,
            RegionEnum::OSTRAVA->value,
        ])) {
            $loanEligibilityResult->reject('Регион пользователя не поддерживается для кредитования.');
        }
    }
}
