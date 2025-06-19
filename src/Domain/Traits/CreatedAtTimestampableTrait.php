<?php

declare(strict_types=1);

namespace App\Domain\Traits;

use DateTimeInterface;

trait CreatedAtTimestampableTrait
{
    /**
     * @var DateTimeInterface|null
     */
    protected ?DateTimeInterface $createdAt = null;

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
