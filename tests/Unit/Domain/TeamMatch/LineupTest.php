<?php

declare(strict_types=1);

namespace DimiPress\Rally\Tests\Unit\Domain\TeamMatch;

use DimiPress\Rally\Domain\Player\PlayerId;
use DimiPress\Rally\Domain\TeamMatch\DoublesLineup;
use DimiPress\Rally\Domain\TeamMatch\FirstToFourTeamMatch;
use DimiPress\Rally\Domain\TeamMatch\PlayerSlot;
use DimiPress\Rally\Domain\TeamMatch\TeamMatchRoster;
use DimiPress\Rally\Domain\TeamMatch\TeamMatchSide;
use DimiPress\Rally\Domain\TeamMatch\TeamSinglesLineup;
use DomainException;
use PHPUnit\Framework\TestCase;

final class LineupTest extends TestCase
{
    public function testAReplacementTakesOverTheReplacedPlayersSlot(): void
    {
        $playerA = PlayerId::fromInt(1);
        $playerB = PlayerId::fromInt(2);
        $playerC = PlayerId::fromInt(3);
        $reserve = PlayerId::fromInt(4);
        $roster = $this->rosterWith($playerA, $playerB, $playerC, $reserve);
        $lineup = TeamSinglesLineup::home($roster, $playerA, $playerB, $playerC);

        $updatedLineup = $lineup->withReplacements([
            PlayerSlot::HomeA->value => $reserve,
        ]);

        self::assertSame($reserve, $updatedLineup->playerIn(PlayerSlot::HomeA));
        self::assertSame($playerB, $updatedLineup->playerIn(PlayerSlot::HomeB));
    }

    public function testASubstitutedOutPlayerRemainsEligibleForDoubles(): void
    {
        $playerA = PlayerId::fromInt(1);
        $playerB = PlayerId::fromInt(2);
        $playerC = PlayerId::fromInt(3);
        $reserve = PlayerId::fromInt(4);
        $roster = $this->rosterWith($playerA, $playerB, $playerC, $reserve);
        $lineup = TeamSinglesLineup::home($roster, $playerA, $playerB, $playerC);

        $lineup->withReplacements([
            PlayerSlot::HomeA->value => $reserve,
        ]);
        $doubles = DoublesLineup::fromRoster($roster, $playerA, $reserve);

        self::assertSame($playerA, $doubles->firstPlayer);
        self::assertSame($reserve, $doubles->secondPlayer);
    }

    public function testTheSubstitutionWindowIsOpenOnlyAfterTheThirdRubber(): void
    {
        $teamMatch = $this->teamMatch();

        self::assertFalse($teamMatch->isSubstitutionWindowOpen());

        foreach (range(1, 3) as $_) {
            $teamMatch->recordNextRubberWin(TeamMatchSide::Home);
        }

        self::assertTrue($teamMatch->isSubstitutionWindowOpen());

        $teamMatch->recordNextRubberWin(TeamMatchSide::Away);

        self::assertFalse($teamMatch->isSubstitutionWindowOpen());
    }

    public function testItAppliesSubstitutionsOnlyOnceInTheAllowedWindow(): void
    {
        $teamMatch = $this->teamMatch();
        $reserve = PlayerId::fromInt(4);

        foreach (range(1, 3) as $_) {
            $teamMatch->recordNextRubberWin(TeamMatchSide::Home);
        }

        $teamMatch->applySubstitutions([PlayerSlot::HomeA->value => $reserve], []);

        self::assertSame($reserve, $teamMatch->homeLineup()->playerIn(PlayerSlot::HomeA));
        self::assertFalse($teamMatch->isSubstitutionWindowOpen());

        $this->expectException(DomainException::class);
        $teamMatch->applySubstitutions([PlayerSlot::HomeB->value => $reserve], []);
    }

    public function testItRejectsSubstitutionsBeforeRubberThree(): void
    {
        $teamMatch = $this->teamMatch();

        $this->expectException(DomainException::class);
        $teamMatch->applySubstitutions([PlayerSlot::HomeA->value => PlayerId::fromInt(4)], []);
    }

    public function testItRejectsSubstitutionsAfterRubberFour(): void
    {
        $teamMatch = $this->teamMatch();

        $winners = [TeamMatchSide::Home, TeamMatchSide::Away, TeamMatchSide::Home, TeamMatchSide::Away];

        foreach ($winners as $winner) {
            $teamMatch->recordNextRubberWin($winner);
        }

        self::assertFalse($teamMatch->isCompleted());
        $this->expectException(DomainException::class);
        $teamMatch->applySubstitutions([PlayerSlot::HomeA->value => PlayerId::fromInt(4)], []);
    }

    public function testItRejectsAnUnregisteredDoublesPlayer(): void
    {
        $firstPlayer = PlayerId::fromInt(1);
        $registeredSecondPlayer = PlayerId::fromInt(2);
        $unregisteredPlayer = PlayerId::fromInt(3);
        $roster = $this->rosterWith($firstPlayer, $registeredSecondPlayer);

        $this->expectException(DomainException::class);

        DoublesLineup::fromRoster($roster, $firstPlayer, $unregisteredPlayer);
    }

    public function testItRejectsAnUnregisteredSinglesPlayer(): void
    {
        $playerA = PlayerId::fromInt(1);
        $playerB = PlayerId::fromInt(2);
        $unregisteredPlayer = PlayerId::fromInt(3);
        $roster = $this->rosterWith($playerA, $playerB);

        $this->expectException(DomainException::class);

        TeamSinglesLineup::home($roster, $playerA, $playerB, $unregisteredPlayer);
    }

    private function rosterWith(PlayerId ...$players): TeamMatchRoster
    {
        $roster = new TeamMatchRoster();

        foreach ($players as $player) {
            $roster->register($player);
        }

        return $roster;
    }

    private function teamMatch(): FirstToFourTeamMatch
    {
        $homeRoster = $this->rosterWith(
            PlayerId::fromInt(1),
            PlayerId::fromInt(2),
            PlayerId::fromInt(3),
            PlayerId::fromInt(4),
        );
        $awayRoster = $this->rosterWith(
            PlayerId::fromInt(5),
            PlayerId::fromInt(6),
            PlayerId::fromInt(7),
        );

        return new FirstToFourTeamMatch(
            TeamSinglesLineup::home(
                $homeRoster,
                PlayerId::fromInt(1),
                PlayerId::fromInt(2),
                PlayerId::fromInt(3),
            ),
            TeamSinglesLineup::away(
                $awayRoster,
                PlayerId::fromInt(5),
                PlayerId::fromInt(6),
                PlayerId::fromInt(7),
            ),
        );
    }
}
