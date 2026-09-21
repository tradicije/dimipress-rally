<?php

declare(strict_types=1);

namespace DimiPress\Rally\Domain\TeamMatch;

final readonly class RubberDefinition
{
    public function __construct(
        public int $order,
        public RubberDiscipline $discipline,
        public ?PlayerSlot $homeSlot = null,
        public ?PlayerSlot $awaySlot = null,
    ) {
    }
}
