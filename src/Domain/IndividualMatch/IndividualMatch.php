<?php

declare(strict_types=1);

namespace DimiPress\Rally\Domain\IndividualMatch;

use DomainException;

final class IndividualMatch
{
    private const SETS_REQUIRED_TO_WIN = 3;

    /**
     * @var list<SetScore>
     */
    private array $sets = [];

    public function recordSet(SetScore $set): void
    {
        if ($this->isCompleted()) {
            throw new DomainException('A completed individual match cannot receive another set.');
        }

        $this->sets[] = $set;
    }

    /**
     * @return list<SetScore>
     */
    public function sets(): array
    {
        return $this->sets;
    }

    public function setsWonBy(MatchSide $side): int
    {
        $wonSets = 0;

        foreach ($this->sets as $set) {
            if ($set->winner() === $side) {
                $wonSets++;
            }
        }

        return $wonSets;
    }

    public function winner(): ?MatchSide
    {
        foreach (MatchSide::cases() as $side) {
            if ($this->setsWonBy($side) === self::SETS_REQUIRED_TO_WIN) {
                return $side;
            }
        }

        return null;
    }

    public function isCompleted(): bool
    {
        return $this->winner() !== null;
    }
}
