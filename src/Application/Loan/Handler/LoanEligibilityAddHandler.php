<?php

declare(strict_types=1);

namespace App\Application\Loan\Handler;

use App\Application\Loan\Command\LoanEligibilityAddCommand;
use App\Domain\Loan\Entity\Loan;
use App\Infrastructure\Loan\Doctrine\LoanRepository;
use App\UI\Http\Exception\ApiException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;

final readonly class LoanEligibilityAddHandler
{
    public function __construct(
        private LoanRepository $loanRepository
    ) {
    }

    /**
     * Handles the command to add a new loan eligibility.
     *
     * @param LoanEligibilityAddCommand $command The command containing loan details.
     *
     * @throws Exception If there is an error during the insertion.
     */
    public function handle(LoanEligibilityAddCommand $command): void
    {
        $loan = (new Loan())
            ->setName($command->getName())
            ->setAmount($command->getAmount())
            ->setRate($command->getRate())
            ->setStartDate($command->getStartDate())
            ->setEndDate($command->getEndDate());

        $this->loanRepository->insert($loan);
    }
}
