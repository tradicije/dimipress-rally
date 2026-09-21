<?php

declare(strict_types=1);

namespace DimiPress\Rally\Domain\TeamMatch;

enum TeamMatchSide: string
{
    case Home = 'home';
    case Away = 'away';
}
