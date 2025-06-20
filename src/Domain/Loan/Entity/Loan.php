<?php

declare(strict_types=1);

namespace App\Domain\Loan\Entity;

use App\Domain\EntityInterface;
use App\Domain\Traits\CreatedAtTimestampableInterface;
use App\Domain\Traits\CreatedAtTimestampableTrait;
use App\Domain\Traits\TimestampableUpdateTrait;
use App\Domain\Traits\UpdatedAtTimestampableInterface;
use App\Domain\Traits\UpdatedAtTimestampableTrait;
use App\Infrastructure\Loan\Doctrine\LoanRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LoanRepository::class)]
#[ORM\Table(name: 'loan')]
class Loan implements EntityInterface, CreatedAtTimestampableInterface, UpdatedAtTimestampableInterface
{
    use CreatedAtTimestampableTrait;
    use TimestampableUpdateTrait;
    use UpdatedAtTimestampableTrait;

    #region [properties]

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $name;

    #[ORM\Column(type: Types::INTEGER)]
    private int $amount;

    #[ORM\Column(type: Types::FLOAT)]
    private float $rate;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private DateTimeInterface $startDate;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private DateTimeInterface $endDate;

    #endregion

    #region [Getters & Setters]

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Loan
    {
        $this->name = $name;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): Loan
    {
        $this->amount = $amount;

        return $this;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): Loan
    {
        $this->rate = $rate;

        return $this;
    }

    public function getStartDate(): DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(DateTimeInterface $startDate): Loan
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(DateTimeInterface $endDate): Loan
    {
        $this->endDate = $endDate;

        return $this;
    }

    #endregion
}
