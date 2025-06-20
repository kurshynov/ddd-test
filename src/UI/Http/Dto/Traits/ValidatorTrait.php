<?php

declare(strict_types=1);

namespace App\UI\Http\Dto\Traits;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;

trait ValidatorTrait
{
    public function validate(): static
    {
        $validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
        $errors = $validator->validate($this);
        if ($errors->count() > 0) {
            throw new UnprocessableEntityHttpException(
                'Validation failed',
                new ValidationFailedException(
                    'Validation failed for ' . static::class,
                    $errors
                ),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this;
    }
}
