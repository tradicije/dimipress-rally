<?php

declare(strict_types=1);

namespace DimiPress\Rally\Domain\TeamMatch;

use DimiPress\Rally\Domain\Player\PlayerId;
use DomainException;

final class TeamMatchRoster
{
    /**
     * @var array<int, PlayerId>
     */
    private array $players = [];

    public function register(PlayerId $player): void
    {
        if ($this->contains($player)) {
            throw new DomainException('A player can only be registered once for a team match.');
        }

        $this->players[$player->value] = $player;
    }

    public function contains(PlayerId $player): bool
    {
        return isset($this->players[$player->value]);
    }

    /**
     * @return list<PlayerId>
     */
    public function players(): array
    {
        return array_values($this->players);
    }
}
