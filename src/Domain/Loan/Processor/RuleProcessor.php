<?php

declare(strict_types=1);

namespace App\Domain\Loan\Processor;

use App\Domain\Loan\Entity\LoanEligibilityResult;
use App\Domain\Loan\Model\LoanApplication;

final readonly class RuleProcessor
{
    public function __construct(
        private iterable $rules
    ) {
    }

    public function evaluate(LoanApplication $application): LoanEligibilityResult
    {
        $result = new LoanEligibilityResult();

        foreach ($this->rules as $rule) {
            if ($rule->supports($application)) {
                $rule->check($application, $result);

                if (!$result->isEligible()) {
                    break; // цепочка прерывается при отказе
                }
            }
        }

        return $result;
    }
}