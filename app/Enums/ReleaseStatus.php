<?php

declare(strict_types=1);

namespace App\Enums;

use ArchTech\Enums\Values;

enum ReleaseStatus: int
{
    use Values;

    case UNKNOWN = 0;
    case RELEASED = 1;
    case POST_PRODUCTION = 2;
    case PRE_PRODUCTION = 3;
    case ANNOUNCED = 4;
    case CANCELED = 5;

    /**
     * Get the name for the release status.
     *
     * @return string
     */
    public function getName(): string
    {
        return match ($this) {
            self::RELEASED => 'Released',
            self::POST_PRODUCTION => 'Post Production',
            self::PRE_PRODUCTION => 'Pre Production',
            self::ANNOUNCED => 'Announced',
            self::CANCELED => 'Canceled',
        };
    }
}
