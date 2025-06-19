<?php

declare(strict_types=1);

namespace App\UI\Http\Dto\Response;

use App\Tools\ToolsHelper;
use App\UI\Http\Dto\Traits\ValidatorTrait;
use JsonSerializable;
use ReflectionClass;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

abstract class BaseDtoResponse implements DtoResponseInterface, JsonSerializable
{
    use ValidatorTrait;

    #region [properties]

    #[Groups('success')]
    #[Assert\Type('bool')]
    private bool $success = false;

    #endregion

    #region [Getters]

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     * @return $this
     */
    public function setSuccess(bool $success): static
    {
        $this->success = $success;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getGroups(): array
    {
        return ['success'];
    }

    /**
     * Убираем все свойства с null из ответа json
     * @return array
     */
    public function jsonSerialize(): array
    {
        $data = [];
        $reflection = new ReflectionClass($this);

        foreach ($reflection->getProperties() as $property) {
            $value = $property->getValue($this);
            if ($value !== null) {
                $data[ToolsHelper::camelCaseToSnakeCase($property->getName())] = $value;
            }
        }

        // Забираем свойства BaseDtoResponse
        foreach (get_object_vars($this) as $property => $value) {
            if (isset($this->$property)) {
                $data[ToolsHelper::camelCaseToSnakeCase($property)] = $value;
            }
        }

        return $data;
    }

    #endregion
}
