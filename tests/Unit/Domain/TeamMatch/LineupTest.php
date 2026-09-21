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

        $updatedLineup = $lineup->replaceAfterThirdRubber([
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

        $lineup->replaceAfterThirdRubber([
            PlayerSlot::HomeA->value => $reserve,
        ]);
        $doubles = DoublesLineup::fromRoster($roster, $playerA, $reserve);

        self::assertSame($playerA, $doubles->firstPlayer);
        self::assertSame($reserve, $doubles->secondPlayer);
    }

    public function testTheSubstitutionWindowIsOpenOnlyAfterTheThirdRubber(): void
    {
        $teamMatch = new FirstToFourTeamMatch();

        self::assertFalse($teamMatch->isSubstitutionWindowOpen());

        foreach (range(1, 3) as $_) {
            $teamMatch->recordNextRubberWin(TeamMatchSide::Home);
        }

        self::assertTrue($teamMatch->isSubstitutionWindowOpen());

        $teamMatch->recordNextRubberWin(TeamMatchSide::Away);

        self::assertFalse($teamMatch->isSubstitutionWindowOpen());
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
}
