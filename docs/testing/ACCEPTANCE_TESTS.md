# Acceptance test specifications

The `.feature` files in `tests/Acceptance` are executable-style business specifications. They are intentionally independent of WordPress and persistence.

Before a use case is implemented, its relevant scenarios must be translated into PHPUnit unit tests. A scenario is not complete until a passing automated test covers it.

The initial specification is [First-to-four team match format](../../tests/Acceptance/first-to-four.feature).
