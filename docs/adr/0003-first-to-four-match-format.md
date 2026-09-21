# ADR 0003: First-to-four is the initial team-match format

## Status

Accepted.

## Decision

The first Rally vertical slice supports one explicit `MatchFormat`: three starting singles slots per team, six ordered singles rubbers, a fixed seventh doubles rubber, and a team match that ends when either team reaches four rubber wins.

Substitutions are allowed only after the third rubber and before the fourth. A replacement inherits the replaced player's slot. Doubles selection is made from the team-match roster, including substituted-out players.

## Consequences

The domain must model the roster, starting lineup, slot substitutions, and doubles lineup separately. Retirement, walkover, forfeiture, no-show, and administrative outcomes are deferred; no generic fallback behavior is allowed.

See [the complete format specification](../domain/MATCH_FORMAT_FIRST_TO_FOUR.md).
