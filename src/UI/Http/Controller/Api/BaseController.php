<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Api;

use App\UI\Http\Dto\Response\DtoResponseInterface;
use App\UI\Http\Exception\ApiExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

abstract class BaseController extends AbstractController
{
    /**
     * @var array<string, string>
     */
    private array $headers;

    public function __construct(
        private readonly SerializerInterface $serializer,
        protected readonly LoggerInterface $logger
    ) {
        $this->headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Authorization, Content-Type, X-Requested-With',
            'Access-Control-Allow-Methods' => 'POST, OPTIONS',
            'Content-Type' => 'application/json; charset=utf-8',
        ];
    }

    /**
     * @param DtoResponseInterface $dtoResponse
     * @param int|null $httpCode
     * @return Response
     */
    protected function getResponse(DtoResponseInterface $dtoResponse, ?int $httpCode = 200): Response
    {
        try {
            $dtoResponse->validate();
            $groups = array_merge(['required'], $dtoResponse->getGroups());

            /** 1.900000002 to 1.9 */
            ini_set('serialize_precision', '8');
            $json = $this->serializer->serialize($dtoResponse, 'json', [
                'groups' => $groups,
                'json_encode_options' => JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
            ]);

            $this->logger->debug('BaseController::getResponse', [$httpCode, $json, $this->headers]);

            return (new Response($json, $httpCode, $this->headers));
        } catch (Throwable $e) {
            $this->logger->error('BaseController::getResponse', [$e, $json ?? 'json fail']);

            return $this->getErrorResponse($e);
        }
    }

    /**
     * Получить ошибку
     * @param Throwable $e
     * @return Response
     */
    protected function getErrorResponse(Throwable $e): Response
    {
        if ($e instanceof ApiExceptionInterface) {
            $code = $this->getHttpStatusCode($e);
            $error = $e->getResponse();
        } else {
            $code = 0;
            $error = [
                'error' => [
                    'code' => $e->getCode(),
                    'message' => 'Internal Server Error',
                ],
            ];

            $this->logger->critical('BaseController::getErrorResponse', [$e, $error]);
        }

        try {
            return new Response(
                $this->serializer->serialize($error, 'json', [
                    'json_encode_options' => JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
                ]),
                $code === 0 ? Response::HTTP_INTERNAL_SERVER_ERROR : $code,
                $this->headers
            );
        } catch (Throwable $e) {
            $this->logger->error('BaseController::getErrorResponse', [$e, $error]);

            return new Response(
                json_encode([
                    'error' => [
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => 'Internal Server Error',
                    ],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $this->headers
            );
        }
    }

    /**
     * Получение статус кода
     * @param Throwable $e
     * @return int
     */
    private function getHttpStatusCode(Throwable $e): int
    {
        $appCode = $e->getCode();
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($appCode >= 200 && $appCode < 300) {
            $statusCode = Response::HTTP_OK;

            $this->logger->warning('BaseController::getErrorResponse]', [$e]);
        } elseif ($appCode >= 400 && $appCode < 500) {
            $statusCode = Response::HTTP_BAD_REQUEST;

            $this->logger->error('BaseController::getErrorResponse', [$e]);
        }

        $this->logger->debug('BaseController::getHttpStatusCode', [$appCode, $statusCode]);

        return $statusCode;
    }
}
