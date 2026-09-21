<?php

declare(strict_types=1);

namespace DimiPress\Rally\Domain\TeamMatch;

use DomainException;

final class FirstToFourTeamMatch
{
    private const WINS_REQUIRED = 4;

    /**
     * @var array<int, TeamMatchSide>
     */
    private array $rubberWinners = [];

    public function nextRubber(): ?RubberDefinition
    {
        if ($this->isCompleted()) {
            return null;
        }

        return match (count($this->rubberWinners) + 1) {
            1 => new RubberDefinition(1, RubberDiscipline::Singles, PlayerSlot::HomeA, PlayerSlot::AwayY),
            2 => new RubberDefinition(2, RubberDiscipline::Singles, PlayerSlot::HomeB, PlayerSlot::AwayX),
            3 => new RubberDefinition(3, RubberDiscipline::Singles, PlayerSlot::HomeC, PlayerSlot::AwayZ),
            4 => new RubberDefinition(4, RubberDiscipline::Singles, PlayerSlot::HomeA, PlayerSlot::AwayX),
            5 => new RubberDefinition(5, RubberDiscipline::Singles, PlayerSlot::HomeC, PlayerSlot::AwayY),
            6 => new RubberDefinition(6, RubberDiscipline::Singles, PlayerSlot::HomeB, PlayerSlot::AwayZ),
            7 => new RubberDefinition(7, RubberDiscipline::Doubles),
            default => throw new DomainException('The team match cannot contain more than seven rubbers.'),
        };
    }

    public function recordNextRubberWin(TeamMatchSide $winner): void
    {
        if ($this->nextRubber() === null) {
            throw new DomainException('A completed team match cannot receive another rubber result.');
        }

        $this->rubberWinners[] = $winner;
    }

    public function scoreFor(TeamMatchSide $side): int
    {
        return count(array_filter(
            $this->rubberWinners,
            static fn (TeamMatchSide $winner): bool => $winner === $side,
        ));
    }

    public function winner(): ?TeamMatchSide
    {
        foreach (TeamMatchSide::cases() as $side) {
            if ($this->scoreFor($side) === self::WINS_REQUIRED) {
                return $side;
            }
        }

        return null;
    }

    public function isCompleted(): bool
    {
        return $this->winner() !== null;
    }

    public function isDoublesRequired(): bool
    {
        return !$this->isCompleted()
            && count($this->rubberWinners) === 6
            && $this->scoreFor(TeamMatchSide::Home) === 3
            && $this->scoreFor(TeamMatchSide::Away) === 3;
    }
}
