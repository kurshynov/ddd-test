<?php

declare(strict_types=1);

namespace App\UI\Http\Dto\Request;

use Symfony\Component\HttpFoundation\Request;

interface DtoRequestInterface
{
    /**
     * @return Request|null
     */
    public function getRequest(): ?Request;

    /**
     * @return $this
     */
    public function validate(): static;
}
