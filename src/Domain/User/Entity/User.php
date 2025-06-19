<?php

namespace App\Domain\User\Entity;

use App\Domain\EntityInterface;
use App\Domain\Traits\CreatedAtTimestampableInterface;
use App\Domain\Traits\CreatedAtTimestampableTrait;
use App\Domain\Traits\TimestampableUpdateTrait;
use App\Domain\Traits\UpdatedAtTimestampableInterface;
use App\Domain\Traits\UpdatedAtTimestampableTrait;
use App\Infrastructure\User\Doctrine\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements EntityInterface, CreatedAtTimestampableInterface, UpdatedAtTimestampableInterface
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
    public string $name;

    #[ORM\Column(type: Types::SMALLINT)]
    public int $age;

    #[ORM\Column(type: Types::STRING, length: 2)]
    public string $region;

    #[ORM\Column(type: Types::INTEGER)]
    public int $income;

    #[ORM\Column(type: Types::INTEGER)]
    public int $score;

    #[ORM\Column(type: Types::STRING, length: 11)]
    public string $pin;

    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    public string $email;

    #[ORM\Column(type: Types::STRING, length: 15)]
    public string $phone;


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

    public function setName(string $name): User
    {
        $this->name = $name;

        return $this;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): User
    {
        $this->age = $age;

        return $this;
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function setRegion(string $region): User
    {
        $this->region = $region;

        return $this;
    }

    public function getIncome(): int
    {
        return $this->income;
    }

    public function setIncome(int $income): User
    {
        $this->income = $income;

        return $this;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): User
    {
        $this->score = $score;

        return $this;
    }

    public function getPin(): string
    {
        return $this->pin;
    }

    public function setPin(string $pin): User
    {
        $this->pin = $pin;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): User
    {
        $this->phone = $phone;

        return $this;
    }

    #endregion
}
