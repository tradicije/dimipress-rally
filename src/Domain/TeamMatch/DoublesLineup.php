<?php

declare(strict_types=1);

namespace DimiPress\Rally\Domain\TeamMatch;

use DimiPress\Rally\Domain\Player\PlayerId;
use DomainException;

final readonly class DoublesLineup
{
    private function __construct(
        public PlayerId $firstPlayer,
        public PlayerId $secondPlayer,
    ) {
    }

    public static function fromRoster(
        TeamMatchRoster $roster,
        PlayerId $firstPlayer,
        PlayerId $secondPlayer,
    ): self {
        if ($firstPlayer->value === $secondPlayer->value) {
            throw new DomainException('A doubles lineup requires two different players.');
        }

        if (!$roster->contains($firstPlayer) || !$roster->contains($secondPlayer)) {
            throw new DomainException('Both doubles players must be registered in the team-match roster.');
        }

        return new self($firstPlayer, $secondPlayer);
    }
}
