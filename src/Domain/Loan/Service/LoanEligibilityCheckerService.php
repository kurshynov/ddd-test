<?php

declare(strict_types=1);

namespace App\Domain\Loan\Service;

use App\Domain\Loan\Entity\LoanEligibilityResult;
use App\Domain\Loan\Rule\RuleInterface;
use App\Domain\User\Entity\User;

final class LoanEligibilityCheckerService
{
    /** @var RuleInterface[] */
    private iterable $rules;

    public function __construct(iterable $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Handles the loan eligibility check for a user.
     *
     * @param User $user The user to check for loan eligibility.
     * @return LoanEligibilityResult The result of the loan eligibility check.
     */
    public function check(User $user): LoanEligibilityResult
    {
        $result = new LoanEligibilityResult();

        foreach ($this->rules as $rule) {
            $rule->apply($user, $result);
        }

        return $result;
    }
}
