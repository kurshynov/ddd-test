<?php

declare(strict_types=1);

namespace App\Domain\Traits;

use DateTimeInterface;

trait UpdatedAtTimestampableTrait
{
    /**
     * @var DateTimeInterface|null
     */
    protected ?DateTimeInterface $updatedAt = null;

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
