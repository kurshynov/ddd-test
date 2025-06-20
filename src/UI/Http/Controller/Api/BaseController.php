<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Api;

use App\UI\Http\Dto\Response\DtoResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
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
     * @throws Throwable
     * @throws ExceptionInterface
     */
    protected function getResponse(DtoResponseInterface $dtoResponse, ?int $httpCode = 200): Response
    {
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
    }
}
