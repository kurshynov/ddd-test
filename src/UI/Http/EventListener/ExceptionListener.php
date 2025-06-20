<?php

declare(strict_types=1);

namespace App\UI\Http\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        // Проверка: это ошибка 422 с вложенной валидацией
        if (
            $e instanceof UnprocessableEntityHttpException &&
            ($previous = $e->getPrevious()) instanceof ValidationFailedException
        ) {
            $violations = [];
            foreach ($previous->getViolations() as $violation) {
                $violations[$violation->getPropertyPath()] = $violation->getMessage();
            }

            $event->setResponse(new JsonResponse([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'fields' => $violations,
                ],
            ], 422));

            return;
        }

        // Другие исключения (например, RuntimeException, DomainException и т.д.)
        $statusCode = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

        $event->setResponse(new JsonResponse([
            'error' => [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ],
        ], $statusCode));
    }
}