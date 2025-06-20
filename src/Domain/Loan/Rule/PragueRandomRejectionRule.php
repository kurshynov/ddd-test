<?php

declare(strict_types=1);

namespace App\Domain\Loan\Rule;

use App\Domain\Loan\Entity\LoanEligibilityResult;
use App\Domain\Loan\Enum\RegionEnum;
use App\Domain\Loan\Model\LoanApplication;
use Random\RandomException;

final class PragueRandomRejectionRule implements RuleInterface
{
    public function supports(LoanApplication $application): bool
    {
        return $application->getUser()->getRegion() === RegionEnum::PRAHA->value;
    }

    /**
     * @throws RandomException
     */
    public function check(LoanApplication $application, LoanEligibilityResult $loanEligibilityResult): void
    {
        if (random_int(0, 1) === 0) {
            $loanEligibilityResult->reject('Случайный отказ клиенту из Праги.');
        }
    }
}
