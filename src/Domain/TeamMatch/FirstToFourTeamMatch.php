<?php

declare(strict_types=1);

namespace DimiPress\Rally\Domain\TeamMatch;

use DimiPress\Rally\Domain\Player\PlayerId;
use DomainException;

final class FirstToFourTeamMatch
{
    private const WINS_REQUIRED = 4;

    /**
     * @var array<int, TeamMatchSide>
     */
    private array $rubberWinners = [];

    private bool $substitutionsApplied = false;

    public function __construct(
        private TeamSinglesLineup $homeLineup,
        private TeamSinglesLineup $awayLineup,
    ) {
        $this->assertDistinctTeams($homeLineup, $awayLineup);
        $homeLineup->playerIn(PlayerSlot::HomeA);
        $homeLineup->playerIn(PlayerSlot::HomeB);
        $homeLineup->playerIn(PlayerSlot::HomeC);
        $awayLineup->playerIn(PlayerSlot::AwayY);
        $awayLineup->playerIn(PlayerSlot::AwayX);
        $awayLineup->playerIn(PlayerSlot::AwayZ);
    }

    public function homeLineup(): TeamSinglesLineup
    {
        return $this->homeLineup;
    }

    public function awayLineup(): TeamSinglesLineup
    {
        return $this->awayLineup;
    }

    /**
     * @param array<string, PlayerId> $homeReplacements
     * @param array<string, PlayerId> $awayReplacements
     */
    public function applySubstitutions(array $homeReplacements, array $awayReplacements): void
    {
        if (!$this->isSubstitutionWindowOpen()) {
            throw new DomainException(
                'Substitutions are only allowed once, after rubber three and before rubber four.',
            );
        }

        if ($homeReplacements === [] && $awayReplacements === []) {
            throw new DomainException('At least one replacement is required.');
        }

        $newHomeLineup = $this->homeLineup->withReplacements($homeReplacements);
        $newAwayLineup = $this->awayLineup->withReplacements($awayReplacements);
        $this->assertDistinctTeams($newHomeLineup, $newAwayLineup);

        $this->homeLineup = $newHomeLineup;
        $this->awayLineup = $newAwayLineup;
        $this->substitutionsApplied = true;
    }

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

    public function isSubstitutionWindowOpen(): bool
    {
        return !$this->isCompleted()
            && !$this->substitutionsApplied
            && count($this->rubberWinners) === 3;
    }

    private function assertDistinctTeams(TeamSinglesLineup $homeLineup, TeamSinglesLineup $awayLineup): void
    {
        $homePlayerIds = array_map(
            static fn (PlayerId $player): int => $player->value,
            $homeLineup->players(),
        );

        foreach ($awayLineup->players() as $player) {
            if (in_array($player->value, $homePlayerIds, true)) {
                throw new DomainException('A player cannot occupy a singles slot for both teams.');
            }
        }
    }
}
