<?php

declare(strict_types=1);

namespace DimiPress\Rally\Domain\Player;

use InvalidArgumentException;

final readonly class PlayerId
{
    private function __construct(
        public int $value,
    ) {
    }

    public static function fromInt(int $value): self
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('A player ID must be positive.');
        }

        return new self($value);
    }
}
