<?php

declare(strict_types=1);

namespace App\UI\Http\Dto\Request\Api\V1\User;

use App\UI\Http\Dto\Request\BaseDtoRequest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints as Assert;

final class RegisterUserRequest extends BaseDtoRequest
{
    #region [properties]

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $name;

    #[Assert\Type('int')]
    #[Assert\Positive]
    public int $age;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    public string $region;

    #[Assert\PositiveOrZero]
    public int $income; // Доход клиента

    #[Assert\Type('int')]
    #[Assert\Range(min: 0, max: 1000)]
    public int $score; // Кредитный рейтинг клиента

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Regex('/^\d{3}-\d{2}-\d{4}$/')]
    public string $pin; // Персональный идентификационный номер клиента (PIN)

    #[Assert\Type('string')]
    #[Assert\NotBlank(message: 'Please enter email')]
    #[Assert\Email(
        message: 'Please enter valid email',
        mode: 'html5'
    )]
    #[Assert\Length(
        min: 6,
        max: 180,
        minMessage: 'Email must be at least {{ limit }} characters long',
        maxMessage: 'Email cannot be longer than {{ limit }} characters'
    )]
    public string $email;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Regex('/^\+?[0-9]{9,15}$/')]
    public string $phone;

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
            $this->age = $this->getJsonArrayByField('age', 'int') ?: 0;
            $this->region = $this->getJsonArrayByField('region') ?: '';
            $this->income = $this->getJsonArrayByField('income', 'int') ?: '';
            $this->score = $this->getJsonArrayByField('score', 'int') ?: 0;
            $this->pin = $this->getJsonArrayByField('pin') ?: '';
            $this->email = $this->getJsonArrayByField('email') ?: '';
            $this->phone = $this->getJsonArrayByField('phone') ?: '';
        }
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
