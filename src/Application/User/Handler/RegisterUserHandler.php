<?php

declare(strict_types=1);

namespace App\Application\User\Handler;

use App\Application\User\Command\RegisterUserCommand;
use App\Domain\User\Entity\User;
use App\Infrastructure\User\Doctrine\UserRepository;
use App\UI\Http\Exception\ApiException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

final readonly class RegisterUserHandler
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    /**
     * Handles the registration of a new user.
     *
     * @param RegisterUserCommand $command The command containing user registration data.
     */
    public function handle(RegisterUserCommand $command): void
    {
        $user = (new User())
            ->setName($command->getName())
            ->setAge($command->getAge())
            ->setRegion($command->getRegion())
            ->setIncome($command->getIncome())
            ->setScore($command->getScore())
            ->setPin($command->getPin())
            ->setEmail($command->getEmail())
            ->setPhone($command->getPhone());;

        try {
            $this->userRepository->insert($user);
        } catch (UniqueConstraintViolationException $e) {
            throw new ApiException(409, 'User with this email or phone already exists.');
        }
    }
}
