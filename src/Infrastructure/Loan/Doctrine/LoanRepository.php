<?php

declare(strict_types=1);

namespace App\Infrastructure\Loan\Doctrine;

use App\Domain\Loan\Entity\Loan;
use App\Infrastructure\BaseRepository;
use App\UI\Http\Exception\ApiException;
use Doctrine\Persistence\ManagerRegistry;

class LoanRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Loan::class);
    }

    /**
     * Find a loan by its ID.
     *
     * @param int $loanId
     * @return Loan
     * @throws ApiException
     */
    public function findById(int $loanId): Loan
    {
        $loan = $this->find($loanId);

        if (!$loan) {
            // Использую ApiException только для тестового задания
            throw new ApiException(404, 'Loan not found');
        }

        return $loan;
    }
}
