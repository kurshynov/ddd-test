<?php

declare(strict_types=1);

namespace App\Domain\Loan\ValueObject;

use InvalidArgumentException;

final class Rate
{
    private float $value;

    public function __construct(string $rate)
    {
        if (!preg_match('/^\d+(\.\d+)?%$/', $rate)) {
            throw new InvalidArgumentException('Rate must be a percentage string (e.g. "10%")');
        }

        // Remove the '%' sign and convert to float
        $this->value = (float)rtrim($rate, '%');
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
