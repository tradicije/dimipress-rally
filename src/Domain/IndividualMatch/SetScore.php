<?php

declare(strict_types=1);

namespace DimiPress\Rally\Domain\IndividualMatch;

use DomainException;

final readonly class SetScore
{
    private const MINIMUM_WINNING_POINTS = 11;

    private const MINIMUM_WINNING_MARGIN = 2;

    private function __construct(
        public int $homePoints,
        public int $awayPoints,
    ) {
    }

    public static function from(int $homePoints, int $awayPoints): self
    {
        if ($homePoints < 0 || $awayPoints < 0) {
            throw new DomainException('A set score cannot contain negative points.');
        }

        if ($homePoints === $awayPoints) {
            throw new DomainException('A completed set cannot end in a tie.');
        }

        $winningPoints = max($homePoints, $awayPoints);
        $margin = abs($homePoints - $awayPoints);

        if (
            ($winningPoints === self::MINIMUM_WINNING_POINTS && $margin < self::MINIMUM_WINNING_MARGIN)
            || ($winningPoints > self::MINIMUM_WINNING_POINTS && $margin !== self::MINIMUM_WINNING_MARGIN)
            || $winningPoints < self::MINIMUM_WINNING_POINTS
        ) {
            throw new DomainException('A completed set must end when a side first reaches 11 with a two-point lead.');
        }

        return new self($homePoints, $awayPoints);
    }

    public function winner(): MatchSide
    {
        return $this->homePoints > $this->awayPoints
            ? MatchSide::Home
            : MatchSide::Away;
    }
}
