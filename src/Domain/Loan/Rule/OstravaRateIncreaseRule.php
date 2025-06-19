<?php

declare(strict_types=1);

namespace App\Domain\Loan\Rule;

use App\Domain\Loan\Entity\LoanEligibilityResult;
use App\Domain\User\Entity\User;

final class OstravaRateIncreaseRule implements RuleInterface
{
    public function apply(User $user, LoanEligibilityResult $result): void
    {
        if ($user->getRegion() === 'OS') {
            $result->increaseRate(5.0); // Increase the rate by 5% for an Ostrava region
        }
    }
}
