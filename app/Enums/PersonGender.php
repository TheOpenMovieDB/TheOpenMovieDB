<?php

declare(strict_types=1);

namespace App\Enums;

use ArchTech\Enums\Values;

enum PersonGender: int
{
    use Values;

    case UNKNOWN = 0;
    case FEMALE = 1;
    case MALE = 2;
}
