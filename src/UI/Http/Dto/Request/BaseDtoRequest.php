<?php

declare(strict_types=1);

namespace App\UI\Http\Dto\Request;

use App\UI\Http\Dto\Traits\ValidatorTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints as Assert;
use Throwable;

abstract class BaseDtoRequest implements DtoRequestInterface
{
    use ValidatorTrait;

    #region [properties]

    #[Assert\Type(Request::class)]
    protected ?Request $request;

    /**
     * JSON body
     * @var array|null
     */
    private ?array $jsonArray = null;

    #endregion

    public function __construct(
        protected RequestStack $requestStack,
        protected LoggerInterface $logger
    ) {
        $this->request = $requestStack->getCurrentRequest();

        try {
            $this->jsonArray = $this->getRequest()?->toArray();
        } catch (Throwable) {
            // ignore error
        }
    }

    #region [Getters & Setters]

    /**
     * @return Request|null
     */
    public function getRequest(): ?Request
    {
        return $this->request;
    }

    /**
     * @return array|null
     */
    public function getJsonArray(): ?array
    {
        return $this->jsonArray;
    }

    /**
     * Получаем типизованное значение или null из Json body
     * @param string $field
     * @param string $type
     * @return mixed
     */
    public function getJsonArrayByField(string $field, string $type = 'string'): mixed
    {
        $value = $this->jsonArray[$field] ?? null;

        if ($value !== null) {
            if ($type === 'string') {
                $value = trim((string)$value);
            } elseif ($type === 'int') {
                $value = (int)$value;
            } elseif ($type === 'float') {
                $value = (float)$value;
            } elseif ($type === 'bool') {
                $value = (bool)$value;
            } elseif ($type === 'array') {
                $value = is_array($value) ? $value : [];
            }
        }

        return $value;
    }

    #endregion
}
