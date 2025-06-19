<?php

declare(strict_types=1);

namespace App\UI\Http\Dto\Response;

interface DtoResponseInterface
{
    /**
     * @return $this
     */
    public function validate(): static;

    /**
     * @return string[]
     */
    public function getGroups(): array;
}
