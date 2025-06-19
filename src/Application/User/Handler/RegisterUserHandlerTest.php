<?php

declare(strict_types=1);

namespace App\Application\User\Handler;

use App\Application\User\Command\RegisterUserCommand;
use App\Domain\User\Entity\User;
use App\Infrastructure\User\Doctrine\UserRepository;
use PHPUnit\Framework\TestCase;
use Throwable;

class RegisterUserHandlerTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testSuccessfulRegistration(): void
    {
        $repo = $this->createMock(UserRepository::class);

        $repo->expects($this->once())
            ->method('insert')
            ->with($this->isInstanceOf(User::class));

        $handler = new RegisterUserHandler($repo);

        $command = new RegisterUserCommand(
            name: 'Petr Pavel',
            age: 35,
            region: 'PR',
            income: 1500,
            score: 600,
            pin: '123-45-6789',
            email: 'petr.pavel@example.com',
            phone: '+420123456789'
        );

        $handler->handle($command);
    }
}
