<?php

declare(strict_types=1);

namespace App\Application\Loan\Handler;

use App\Application\Loan\Command\LoanEligibilityAddCommand;
use App\Domain\Loan\Entity\Loan;
use App\Infrastructure\Loan\Doctrine\LoanRepository;
use PHPUnit\Framework\TestCase;
use Throwable;

class LoanEligibilityAddHandlerTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testLoanIsAddedToUser(): void
    {
        $repo = $this->createMock(LoanRepository::class);

        $repo->expects($this->once())
            ->method('insert')
            ->with($this->isInstanceOf(Loan::class));

        $handler = new LoanEligibilityAddHandler($repo);

        $command = new LoanEligibilityAddCommand(
            name: 'Personal Loan',
            amount: 1000,
            rate: '10%',
            startDate: '2025-01-01',
            endDate: '2025-12-31',
        );


        $handler->handle($command);
    }
}
