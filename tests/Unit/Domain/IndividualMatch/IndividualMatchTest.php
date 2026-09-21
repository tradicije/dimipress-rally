<?php

declare(strict_types=1);

namespace DimiPress\Rally\Tests\Unit\Domain\IndividualMatch;

use DimiPress\Rally\Domain\IndividualMatch\IndividualMatch;
use DimiPress\Rally\Domain\IndividualMatch\MatchSide;
use DimiPress\Rally\Domain\IndividualMatch\SetScore;
use DomainException;
use PHPUnit\Framework\TestCase;

final class IndividualMatchTest extends TestCase
{
    public function testItCompletesWhenAPlayerWinsThreeSets(): void
    {
        $match = new IndividualMatch();

        $match->recordSet(SetScore::from(11, 8));
        $match->recordSet(SetScore::from(12, 10));

        self::assertFalse($match->isCompleted());

        $match->recordSet(SetScore::from(11, 6));

        self::assertTrue($match->isCompleted());
        self::assertSame(MatchSide::Home, $match->winner());
        self::assertSame(3, $match->setsWonBy(MatchSide::Home));
        self::assertSame(0, $match->setsWonBy(MatchSide::Away));
        self::assertCount(3, $match->sets());
    }

    public function testItAllowsFiveSetsWhenTheMatchIsDecidedInTheFinalSet(): void
    {
        $match = new IndividualMatch();

        $match->recordSet(SetScore::from(11, 9));
        $match->recordSet(SetScore::from(9, 11));
        $match->recordSet(SetScore::from(11, 7));
        $match->recordSet(SetScore::from(8, 11));
        $match->recordSet(SetScore::from(13, 11));

        self::assertTrue($match->isCompleted());
        self::assertSame(MatchSide::Home, $match->winner());
        self::assertSame(3, $match->setsWonBy(MatchSide::Home));
        self::assertSame(2, $match->setsWonBy(MatchSide::Away));
        self::assertCount(5, $match->sets());
    }

    public function testItRejectsASetAfterTheMatchHasCompleted(): void
    {
        $match = new IndividualMatch();

        $match->recordSet(SetScore::from(11, 0));
        $match->recordSet(SetScore::from(11, 0));
        $match->recordSet(SetScore::from(11, 0));

        $this->expectException(DomainException::class);

        $match->recordSet(SetScore::from(11, 0));
    }
}
