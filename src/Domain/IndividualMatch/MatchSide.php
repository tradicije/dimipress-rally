<?php

declare(strict_types=1);

namespace DimiPress\Rally\Domain\IndividualMatch;

enum MatchSide: string
{
    case Home = 'home';
    case Away = 'away';
}
