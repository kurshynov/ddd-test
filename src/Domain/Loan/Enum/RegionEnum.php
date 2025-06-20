<?php

declare(strict_types=1);

namespace App\Domain\Loan\Enum;

enum RegionEnum: string
{
    case OSTRAVA = 'OS';
    case PRAHA = 'PR';
    case BRNO = 'BR';
}
