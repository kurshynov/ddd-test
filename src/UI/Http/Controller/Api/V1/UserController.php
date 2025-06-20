<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Api\V1;

use App\Application\User\Command\RegisterUserCommand;
use App\Application\User\Handler\RegisterUserHandler;
use App\UI\Http\Controller\Api\BaseController;
use App\UI\Http\Dto\Request\Api\V1\User\RegisterUserRequest;
use App\UI\Http\Dto\Response\Api\V1\User\RegisterUserResponse;
use App\UI\Http\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Throwable;

#[Route('/user', name: 'api_')]
class UserController extends BaseController
{
    /**
     * @throws ExceptionInterface
     * @throws ApiException
     * @throws Throwable
     */
    #[Route('/register', name: 'user_register', methods: ['PUT'])]
    public function register(
        #[MapRequestPayload] RegisterUserRequest $request,
        RegisterUserResponse $response,
        RegisterUserHandler $handler
    ): Response {
        $command = new RegisterUserCommand(
            name: $request->getName(),
            age: $request->getAge(),
            region: $request->getRegion(),
            income: $request->getIncome(),
            score: $request->getScore(),
            pin: $request->getPin(),
            email: $request->getEmail(),
            phone: $request->getPhone()
        );

        $handler->handle($command);

        return $this->getResponse(
            $response
                ->setSuccess(true)
        );
    }
}
