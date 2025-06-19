<?php

declare(strict_types=1);

namespace App\Tools;

use DateTime;
use DateTimeZone;
use Exception;
use RuntimeException;

final readonly class ToolsHelper
{
    /**
     * Get current DateTime with server timezone
     *
     * @return DateTime
     * @throws Exception
     */
    public static function getTimestamp(): DateTime
    {
        // Create a datetime with microseconds
        $dateTime = DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true)));

        if ($dateTime === false) {
            throw new RuntimeException();
        }

        $dateTime->setTimezone(new DateTimeZone(date_default_timezone_get()));

        return $dateTime;
    }

    /**
     * Convert camelCase to snake_case
     *
     * @param string $input
     * @return string
     */
    public static function camelCaseToSnakeCase(string $input): string
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match === strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return implode('_', $ret);
    }
}
