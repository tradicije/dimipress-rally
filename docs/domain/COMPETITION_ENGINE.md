# Competition engine

## Separate rules

`MatchFormat` describes a table-tennis team match: rubber sequence, singles/doubles, sets-to-win, and early-finish conditions. `CompetitionRegulations` describe a season flow: stages, scoring, ranking criteria, transitions, promotion, relegation, and play-ins.

The same league stage may use different team-match formats, and the same match format may be used by a league and a cup.

## Stages and advancement

The initial catalogue is `RegularLeagueStage`, `GroupStage`, `PlayoffStage`, `PlayoutStage`, and `PlayInStage`. `AdvancementPolicy` defines a stage transition: automatic qualifiers, play-in pairs, and carry-over results. An administrative decision is stored separately from the engine's automatic proposal.

## Versions and projections

A season uses a `CompetitionFormatVersion`. Once result entry starts, its regulations are locked; a future change creates a new version and never overwrites historical calculations.

A confirmed individual match changes its team-match score. A confirmed team match triggers projectors for stage standings and player, team, and club statistics. A bulk import may mark a stage for a complete rebuild. Rankings and standings always read a projection.
