<?php

declare(strict_types=1);

namespace App\UI\Http\Exception;

interface ApiExceptionInterface
{
    public function getResponse(): array;
}
