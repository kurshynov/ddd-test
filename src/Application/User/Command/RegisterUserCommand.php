<?php

declare(strict_types=1);

namespace App\Application\User\Command;

final readonly class RegisterUserCommand
{
    public function __construct(
        private string $name,
        private int $age,
        private string $region,
        private int $income,
        private int $score,
        private string $pin,
        private string $email,
        private string $phone,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function getIncome(): int
    {
        return $this->income;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getPin(): string
    {
        return $this->pin;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}
