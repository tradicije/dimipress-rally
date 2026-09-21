<?php

declare(strict_types=1);

namespace DimiPress\Rally\Domain\TeamMatch;

enum PlayerSlot: string
{
    case HomeA = 'A';
    case HomeB = 'B';
    case HomeC = 'C';
    case AwayY = 'Y';
    case AwayX = 'X';
    case AwayZ = 'Z';
}
