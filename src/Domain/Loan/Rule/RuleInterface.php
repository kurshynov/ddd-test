<?php

declare(strict_types=1);

namespace App\Domain\Loan\Rule;

use App\Domain\Loan\Entity\LoanEligibilityResult;
use App\Domain\User\Entity\User;

interface RuleInterface
{
    public function apply(User $user, LoanEligibilityResult $result): void;
}
