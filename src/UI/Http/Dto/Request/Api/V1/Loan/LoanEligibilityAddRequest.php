<?php

declare(strict_types=1);

namespace App\UI\Http\Dto\Request\Api\V1\Loan;

use App\UI\Http\Dto\Request\BaseDtoRequest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints as Assert;

final class LoanEligibilityAddRequest extends BaseDtoRequest
{
    #region [properties]

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private string $name; // Name of the loan or loan product

    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    #[Assert\GreaterThanOrEqual(0)]
    private int $amount; // Amount of the loan in the smallest currency unit (e.g., cents for USD)

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\\d+(\\.\\d+)?%$/', message: 'Rate must be a percentage string (e.g. "10%")')]
    private string $rate; // Interest rate for the loan, formatted as a percentage string

    #[Assert\NotBlank]
    #[Assert\Date]
    private string $startDate; // Start date of the loan, formatted as a date string (YYYY-MM-DD)

    #[Assert\NotBlank]
    #[Assert\Date]
    private string $endDate; // End date of the loan, formatted as a date string (YYYY-MM-DD)

    #endregion

    public function __construct(
        protected RequestStack $requestStack,
        protected LoggerInterface $logger
    ) {
        parent::__construct(
            requestStack: $requestStack,
            logger: $this->logger
        );

        if ($this->getJsonArray()) {
            $this->name = $this->getJsonArrayByField('name') ?: '';
            $this->amount = $this->getJsonArrayByField('amount', 'int') ?: 0;
            $this->rate = $this->getJsonArrayByField('rate') ?: '';
            $this->startDate = $this->getJsonArrayByField('start_date') ?: '';
            $this->endDate = $this->getJsonArrayByField('end_date') ?: '';
        }
    }

    #region [Getters]

    public function getName(): string
    {
        return $this->name;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getRate(): string
    {
        return $this->rate;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }

    #endregion
}
