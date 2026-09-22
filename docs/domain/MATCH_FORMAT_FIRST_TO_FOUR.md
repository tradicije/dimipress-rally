# Match format: First to four team-match wins

## Scope

This is Rally's first formally specified team-match format. It is a configuration of `MatchFormat`, not a universal assumption for every competition.

## Team match completion

A team match is won by the first team to win four individual matches. The team match ends immediately when either side reaches four wins. Unplayed remaining rubbers are recorded as not played, not as losses or zero-score results.

The doubles rubber is therefore played only when the score is 3:3 after six singles rubbers.

## Starting lineups and rubber order

The home team assigns players to slots `A`, `B`, and `C`. The away team assigns players to slots `Y`, `X`, and `Z`.

| Order | Home slot | Away slot | Discipline |
| --- | --- | --- | --- |
| 1 | A | Y | Singles |
| 2 | B | X | Singles |
| 3 | C | Z | Singles |
| 4 | A | X | Singles |
| 5 | C | Y | Singles |
| 6 | B | Z | Singles |
| 7 | — | — | Doubles |

The doubles rubber has a fixed seventh position and is never moved earlier in the order.

## Individual match and set scoring

Each individual match is best of five sets: the first player or pair to win three sets wins the rubber.

A set is normally won at 11 points. At 10:10, play continues until one side leads by two points; valid examples include 12:10 and 13:11.

## Match roster, substitutions, and doubles

`TeamMatchRoster` contains every player registered for this particular team match. The maximum roster size is deliberately not fixed by this format; a competition regulation may impose one later.

There is one substitution window only: after rubber 3 and before rubber 4. Any number of slot substitutions may be made in that window.

All substitutions for both teams are submitted together as one operation. Once applied, the window is closed, even before rubber 4 starts. No substitution is required if neither team chooses to use the window.

A replacement takes over the replaced player's slot for all remaining scheduled singles rubbers. For example, a player replacing `A` after rubber 3 plays rubber 4 as `A` against `X`.

Any player in `TeamMatchRoster` may play doubles, including a player who was not selected for singles and a player substituted out of a singles slot. Doubles eligibility is based on the roster, not the active singles lineup.

## Explicitly deferred rules

The following outcomes are intentionally out of scope for the first implementation and must not be improvised in result-entry code:

- retirement or injury during an individual match;
- walkover, forfeiture, or no-show;
- disciplinary or administrative result changes;
- the score and statistical consequences of any of the above.

They will be specified in a later regulation and implemented as explicit result states.
