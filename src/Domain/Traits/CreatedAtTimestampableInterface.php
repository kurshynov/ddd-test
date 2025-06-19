<?php

declare(strict_types=1);

namespace App\Domain\Traits;

use DateTimeInterface;

interface CreatedAtTimestampableInterface
{
    public function updateTimestamps(): void;

    public function getCreatedAt(): ?DateTimeInterface;

    public function setCreatedAt(DateTimeInterface $createdAt): void;
}
