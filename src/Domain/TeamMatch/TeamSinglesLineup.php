<?php

declare(strict_types=1);

namespace DimiPress\Rally\Domain\TeamMatch;

use DimiPress\Rally\Domain\Player\PlayerId;
use DomainException;

final readonly class TeamSinglesLineup
{
    /**
     * @param array<string, PlayerId> $playersBySlot
     */
    private function __construct(
        private TeamMatchRoster $roster,
        private array $playersBySlot,
    ) {
        $playerIds = [];

        foreach ($this->playersBySlot as $player) {
            if (!$this->roster->contains($player)) {
                throw new DomainException('Every singles player must be registered in the team-match roster.');
            }

            $playerIds[] = $player->value;
        }

        if (count($playerIds) !== count(array_unique($playerIds))) {
            throw new DomainException('A player cannot occupy more than one singles slot.');
        }
    }

    public static function home(
        TeamMatchRoster $roster,
        PlayerId $playerA,
        PlayerId $playerB,
        PlayerId $playerC,
    ): self {
        return new self($roster, [
            PlayerSlot::HomeA->value => $playerA,
            PlayerSlot::HomeB->value => $playerB,
            PlayerSlot::HomeC->value => $playerC,
        ]);
    }

    public static function away(
        TeamMatchRoster $roster,
        PlayerId $playerY,
        PlayerId $playerX,
        PlayerId $playerZ,
    ): self {
        return new self($roster, [
            PlayerSlot::AwayY->value => $playerY,
            PlayerSlot::AwayX->value => $playerX,
            PlayerSlot::AwayZ->value => $playerZ,
        ]);
    }

    public function playerIn(PlayerSlot $slot): PlayerId
    {
        $player = $this->playersBySlot[$slot->value] ?? null;

        if ($player === null) {
            throw new DomainException('The requested slot does not belong to this singles lineup.');
        }

        return $player;
    }

    /**
     * @param array<string, PlayerId> $replacementsBySlot
     */
    public function withReplacements(array $replacementsBySlot): self
    {
        $updatedPlayers = $this->playersBySlot;

        foreach ($replacementsBySlot as $slot => $replacement) {
            if (!isset($updatedPlayers[$slot])) {
                throw new DomainException('A replacement can only target an active lineup slot.');
            }

            if (!$this->roster->contains($replacement)) {
                throw new DomainException('A replacement player must be registered in the team-match roster.');
            }

            $updatedPlayers[$slot] = $replacement;
        }

        return new self($this->roster, $updatedPlayers);
    }

    /**
     * @return list<PlayerId>
     */
    public function players(): array
    {
        return array_values($this->playersBySlot);
    }
}
