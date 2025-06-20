<?php

declare(strict_types=1);

namespace App\Domain\Loan\Rule;

use App\Domain\Loan\Entity\LoanEligibilityResult;
use App\Domain\Loan\Enum\RegionEnum;
use App\Domain\Loan\Model\LoanApplication;

final class OstravaRateIncreaseRule implements RuleInterface
{
    public function supports(LoanApplication $application): bool
    {
        return $application->getUser()->getRegion() === RegionEnum::OSTRAVA->value;
    }

    public function check(LoanApplication $application, LoanEligibilityResult $loanEligibilityResult): void
    {
        $loanEligibilityResult->increaseRate(5.0); // +5%
    }
}
