<?php

declare(strict_types=1);

namespace App\Application\Loan\Factory;

use App\Domain\Loan\Model\LoanApplication;
use App\Infrastructure\Loan\Doctrine\LoanRepository;
use App\Infrastructure\User\Doctrine\UserRepository;
use App\UI\Http\Exception\ApiException;

final readonly class LoanApplicationFactory
{
    public function __construct(
        private UserRepository $userRepository,
        private LoanRepository $loanRepository
    ) {
    }

    /**
     * Creates a new LoanApplication instance for a user and a loan.
     *
     * @param int $userId The ID of the user applying for the loan.
     * @param int $loanId The ID of the loan being applied for.
     * @return LoanApplication The created LoanApplication instance.
     * @throws ApiException
     */
    public function create(int $userId, int $loanId): LoanApplication
    {
        return new LoanApplication(
            $this->userRepository->findById($userId),
            $this->loanRepository->findById($loanId)
        );
    }
}