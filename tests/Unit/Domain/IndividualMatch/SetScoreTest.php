<?php

declare(strict_types=1);

namespace DimiPress\Rally\Tests\Unit\Domain\IndividualMatch;

use DimiPress\Rally\Domain\IndividualMatch\MatchSide;
use DimiPress\Rally\Domain\IndividualMatch\SetScore;
use DomainException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SetScoreTest extends TestCase
{
    #[DataProvider('validScores')]
    public function testItAcceptsACompletedSetScore(
        int $homePoints,
        int $awayPoints,
        MatchSide $winner,
    ): void {
        $score = SetScore::from($homePoints, $awayPoints);

        self::assertSame($homePoints, $score->homePoints);
        self::assertSame($awayPoints, $score->awayPoints);
        self::assertSame($winner, $score->winner());
    }

    #[DataProvider('invalidScores')]
    public function testItRejectsAnInvalidCompletedSetScore(
        int $homePoints,
        int $awayPoints,
    ): void {
        $this->expectException(DomainException::class);

        SetScore::from($homePoints, $awayPoints);
    }

    /**
     * @return array<string, array{int, int, MatchSide}>
     */
    public static function validScores(): array
    {
        return [
            'regular home win' => [11, 9, MatchSide::Home],
            'deuce home win' => [12, 10, MatchSide::Home],
            'deuce away win' => [11, 13, MatchSide::Away],
        ];
    }

    /**
     * @return array<string, array{int, int}>
     */
    public static function invalidScores(): array
    {
        return [
            'negative home points' => [-1, 11],
            'tied score' => [10, 10],
            'win before eleven' => [10, 8],
            'one-point margin at eleven' => [11, 10],
            'one-point margin in deuce' => [14, 13],
            'play continued after an ordinary finish' => [12, 8],
            'play continued after a deuce finish' => [13, 9],
            'three-point margin after deuce' => [13, 10],
        ];
    }
}
