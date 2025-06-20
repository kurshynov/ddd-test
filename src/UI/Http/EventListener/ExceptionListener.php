<?php

declare(strict_types=1);

namespace App\UI\Http\EventListener;

use App\Tools\ToolsHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
readonly class ExceptionListener
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        $this->logger->error('ExceptionListener::__invoke', [$e]);

        // Проверка: это ошибка 422 с вложенной валидацией
        if (
            $e instanceof UnprocessableEntityHttpException &&
            ($previous = $e->getPrevious()) instanceof ValidationFailedException
        ) {
            $violations = [];
            foreach ($previous->getViolations() as $violation) {
                $violations[ToolsHelper::camelCaseToSnakeCase($violation->getPropertyPath())] = $violation->getMessage();
            }

            $event->setResponse(new JsonResponse([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation failed',
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