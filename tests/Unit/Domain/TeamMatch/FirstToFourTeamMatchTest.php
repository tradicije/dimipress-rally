<?php

declare(strict_types=1);

namespace DimiPress\Rally\Tests\Unit\Domain\TeamMatch;

use DimiPress\Rally\Domain\Player\PlayerId;
use DimiPress\Rally\Domain\TeamMatch\FirstToFourTeamMatch;
use DimiPress\Rally\Domain\TeamMatch\PlayerSlot;
use DimiPress\Rally\Domain\TeamMatch\RubberDiscipline;
use DimiPress\Rally\Domain\TeamMatch\TeamMatchRoster;
use DimiPress\Rally\Domain\TeamMatch\TeamMatchSide;
use DimiPress\Rally\Domain\TeamMatch\TeamSinglesLineup;
use DomainException;
use PHPUnit\Framework\TestCase;

final class FirstToFourTeamMatchTest extends TestCase
{
    public function testItUsesTheSpecifiedSinglesOrder(): void
    {
        $teamMatch = $this->teamMatch();

        $first = $teamMatch->nextRubber();

        self::assertNotNull($first);
        self::assertSame(1, $first->order);
        self::assertSame(RubberDiscipline::Singles, $first->discipline);
        self::assertSame(PlayerSlot::HomeA, $first->homeSlot);
        self::assertSame(PlayerSlot::AwayY, $first->awaySlot);

        $teamMatch->recordNextRubberWin(TeamMatchSide::Home);
        $second = $teamMatch->nextRubber();

        self::assertNotNull($second);
        self::assertSame(2, $second->order);
        self::assertSame(PlayerSlot::HomeB, $second->homeSlot);
        self::assertSame(PlayerSlot::AwayX, $second->awaySlot);
    }

    public function testItEndsImmediatelyWhenAClubReachesFourWins(): void
    {
        $teamMatch = $this->teamMatch();

        foreach (range(1, 4) as $_) {
            $teamMatch->recordNextRubberWin(TeamMatchSide::Home);
        }

        self::assertTrue($teamMatch->isCompleted());
        self::assertSame(TeamMatchSide::Home, $teamMatch->winner());
        self::assertSame(4, $teamMatch->scoreFor(TeamMatchSide::Home));
        self::assertSame(0, $teamMatch->scoreFor(TeamMatchSide::Away));
        self::assertNull($teamMatch->nextRubber());
    }

    public function testItRequiresDoublesOnlyAtThreeAllAfterSixSingles(): void
    {
        $teamMatch = $this->teamMatch();

        $rubberWinners = [
            TeamMatchSide::Home,
            TeamMatchSide::Away,
            TeamMatchSide::Home,
            TeamMatchSide::Away,
            TeamMatchSide::Home,
            TeamMatchSide::Away,
        ];

        foreach ($rubberWinners as $winner) {
            $teamMatch->recordNextRubberWin($winner);
        }

        $doubles = $teamMatch->nextRubber();

        self::assertFalse($teamMatch->isCompleted());
        self::assertTrue($teamMatch->isDoublesRequired());
        self::assertNotNull($doubles);
        self::assertSame(7, $doubles->order);
        self::assertSame(RubberDiscipline::Doubles, $doubles->discipline);
    }

    public function testItRejectsAResultAfterTheTeamMatchHasEnded(): void
    {
        $teamMatch = $this->teamMatch();

        foreach (range(1, 4) as $_) {
            $teamMatch->recordNextRubberWin(TeamMatchSide::Home);
        }

        $this->expectException(DomainException::class);

        $teamMatch->recordNextRubberWin(TeamMatchSide::Away);
    }

    private function teamMatch(): FirstToFourTeamMatch
    {
        $homeRoster = new TeamMatchRoster();
        $awayRoster = new TeamMatchRoster();

        foreach ([1, 2, 3] as $id) {
            $homeRoster->register(PlayerId::fromInt($id));
        }

        foreach ([4, 5, 6] as $id) {
            $awayRoster->register(PlayerId::fromInt($id));
        }

        return new FirstToFourTeamMatch(
            TeamSinglesLineup::home(
                $homeRoster,
                PlayerId::fromInt(1),
                PlayerId::fromInt(2),
                PlayerId::fromInt(3),
            ),
            TeamSinglesLineup::away(
                $awayRoster,
                PlayerId::fromInt(4),
                PlayerId::fromInt(5),
                PlayerId::fromInt(6),
            ),
        );
    }
}
