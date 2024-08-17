<?php

declare(strict_types=1);

namespace App\Helpers;

use Carbon\CarbonInterval;
use Exception;
use Throwable;

final class Common
{
    /**
     * @throws Exception
     * @throws Throwable
     */
    public static function FormatRuntime(int $minutes, bool $short = true): string
    {
        throw_if($minutes <= 0, 'Minutes must be greater than 0');

        return CarbonInterval::minutes($minutes)->cascade()->forHumans(short: $short);
    }

    /**
     * @param string $path
     * @param string|null $prefix
     * @return string
     * @todo this is a temp function will be removed soon
     *
     */
    public static function ImagePath(string $path = null, string $prefix = null): string
    {
        if (is_null($path)) {
            return '';
        }
        $prefix = $prefix ?? 'https://image.tmdb.org/t/p/original';
        return $prefix . $path;
    }
}
