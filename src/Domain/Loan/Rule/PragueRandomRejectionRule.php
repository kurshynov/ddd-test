<?php

declare(strict_types=1);

namespace App\Domain\Loan\Rule;

use App\Domain\Loan\Entity\LoanEligibilityResult;
use App\Domain\Loan\Enum\RegionEnum;
use App\Domain\User\Entity\User;
use Random\RandomException;

final class PragueRandomRejectionRule implements RuleInterface
{
    /**
     * @throws RandomException
     */
    public function apply(User $user, LoanEligibilityResult $result): void
    {
        if ($user->getRegion() === RegionEnum::PRAHA->value && random_int(0, 1) === 0) {
            $result->reject('Случайный отказ клиенту из Праги.');
        }
    }
}
