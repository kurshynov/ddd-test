<?php

declare(strict_types=1);

namespace App\Domain\Loan\Model;

use App\Domain\Loan\Entity\Loan;
use App\Domain\User\Entity\User;

final readonly class LoanApplication
{
    public function __construct(
        private User $user,
        private Loan $loan
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getLoan(): Loan
    {
        return $this->loan;
    }
}