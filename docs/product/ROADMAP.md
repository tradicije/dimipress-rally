# Roadmap

## Phase 0: foundations

- Confirm the glossary and layer boundaries.
- Establish supported PHP and WordPress versions, PHPUnit, PHPStan, coding standards, and CI.
- Implement the first migration framework.

## Phase 1: one team match

- Clubs, teams, players, and registrations.
- First-to-four team-match format, lineup validation, singles, doubles, sets, and confirmed results.
- Tests for match formats and score aggregation.

## Phase 2: league season

- Competition, season, league stage, and fixtures.
- Scoring and tie-break catalogue; `StageStandings` projection.
- Gutenberg blocks for standings, fixture lists, and fixture detail.

## Phase 3: statistics

- Player, team, and club projections; player ranking and participation threshold.
- Blocks for rankings and profiles.

## Phase 4: multi-stage engine

- Groups, playoffs, playouts, and play-ins.
- Stage transitions, carry-over results, advancement, administrative decisions, penalties, and exceptions with an audit trail.

## Phase 5: operational maturity

- Import/export and LibreTT migration.
- WP-CLI, observability, cache, and projection rebuilds.
- Multisite and pilot-installation integration tests.

Every phase requires concrete business examples and tests before scope expands.

Retirements, walkovers, forfeits, no-shows, and administrative result changes are explicitly deferred from Phase 1. They require a separately specified result-state model before implementation.
