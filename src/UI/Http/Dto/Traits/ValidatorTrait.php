<?php

declare(strict_types=1);

namespace App\UI\Http\Dto\Traits;

use App\Tools\ToolsHelper;
use App\UI\Http\Exception\ApiException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;

/**
 * @link https://symfony.com/doc/current/reference/constraints/Type.html
 * @link https://symfony.com/doc/current/reference/constraints.html
 */
trait ValidatorTrait
{
    public function validate(): static
    {
        $validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
        $errors = $validator->validate($this);
        if ($errors->count() > 0) {
            $fields = [];
            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                $field = ToolsHelper::camelCaseToSnakeCase($error->getPropertyPath());
                if (!isset($fields[$field])) {
                    $message = $error->getMessage();

                    foreach ($error->getParameters() as $key => $value) {
                        $message = str_replace($key, $value, $message);
                    }

                    $fields[$field] = $message;

                    $this->logger->warning('ValidatorTrait::validate', [$message, $error->getParameters()]);
                }
            }

            throw new ApiException(
                code: 400,
                message: $message ?? 'Validation error',
                fields: $fields
            );
        }

        return $this;
    }
}
