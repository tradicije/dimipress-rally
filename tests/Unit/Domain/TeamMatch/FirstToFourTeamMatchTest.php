<?php

declare(strict_types=1);

namespace DimiPress\Rally\Tests\Unit\Domain\TeamMatch;

use DimiPress\Rally\Domain\TeamMatch\FirstToFourTeamMatch;
use DimiPress\Rally\Domain\TeamMatch\PlayerSlot;
use DimiPress\Rally\Domain\TeamMatch\RubberDiscipline;
use DimiPress\Rally\Domain\TeamMatch\TeamMatchSide;
use DomainException;
use PHPUnit\Framework\TestCase;

final class FirstToFourTeamMatchTest extends TestCase
{
    public function testItUsesTheSpecifiedSinglesOrder(): void
    {
        $teamMatch = new FirstToFourTeamMatch();

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
        $teamMatch = new FirstToFourTeamMatch();

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
        $teamMatch = new FirstToFourTeamMatch();

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
        $teamMatch = new FirstToFourTeamMatch();

        foreach (range(1, 4) as $_) {
            $teamMatch->recordNextRubberWin(TeamMatchSide::Home);
        }

        $this->expectException(DomainException::class);

        $teamMatch->recordNextRubberWin(TeamMatchSide::Away);
    }
}
