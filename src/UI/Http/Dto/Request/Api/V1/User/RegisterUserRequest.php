<?php

declare(strict_types=1);

namespace App\UI\Http\Dto\Request\Api\V1\User;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class RegisterUserRequest
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        private string $name,

        #[Assert\Type('int')]
        #[Assert\Positive]
        private int $age,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        private string $region,

        #[Assert\PositiveOrZero]
        private int $income, // Доход клиента

        #[Assert\Type('int')]
        #[Assert\Range(min: 0, max: 1000)]
        private int $score, // Кредитный рейтинг клиента

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\Regex('/^\d{3}-\d{2}-\d{4}$/')]
        private string $pin, // Персональный идентификационный номер клиента (PIN)

        #[Assert\Type('string')]
        #[Assert\NotBlank(message: 'Please enter email')]
        #[Assert\Email(
            message: 'The email {{ value }} is not a valid email.',
            mode: 'strict'
        )]
        #[Assert\Length(
            min: 6,
            max: 180,
            minMessage: 'Email must be at least {{ limit }} characters long',
            maxMessage: 'Email cannot be longer than {{ limit }} characters'
        )]
        private string $email,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\Regex('/^\+?[0-9]{9,15}$/')]
        private string $phone
    ) {
    }

#region [Getters]

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
        return ltrim($this->phone, '+');
    }

    #endregion
}
