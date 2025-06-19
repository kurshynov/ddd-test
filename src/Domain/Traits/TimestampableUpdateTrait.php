<?php

declare(strict_types=1);

namespace App\Domain\Traits;

use App\Tools\ToolsHelper;
use Exception;

trait TimestampableUpdateTrait
{
    /**
     * Updates createdAt and updatedAt timestamps.
     * @return void
     * @throws Exception
     */
    public function updateTimestamps(): void
    {
        // Create a datetime with microseconds
        $dateTime = ToolsHelper::getTimestamp();

        if (property_exists($this, 'createdAt') && $this->createdAt === null) {
            $this->createdAt = $dateTime;
        }

        if (property_exists($this, 'updatedAt')) {
            $this->updatedAt = $dateTime;
        }
    }
}
