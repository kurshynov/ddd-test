<?php

declare(strict_types=1);

namespace App\Domain\Traits;

use DateTimeInterface;

interface UpdatedAtTimestampableInterface
{
    public function updateTimestamps(): void;

    public function getUpdatedAt(): ?DateTimeInterface;

    public function setUpdatedAt(DateTimeInterface $updatedAt): void;
}
