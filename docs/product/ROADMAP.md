# Roadmap

## Phase 0: foundations

- Confirm the glossary and layer boundaries.
- Preserve the distributed-data constraints in ADR 0004 when designing IDs, provenance, result history, and exports.
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
- Versioned public data export, signature verification, and read-only replica synchronization.
- Document publisher-key rotation, revocation, and succession before enabling production replicas.
- WP-CLI, observability, cache, and projection rebuilds.
- Multisite and pilot-installation integration tests.

Full multi-writer federation is outside this roadmap until concrete use cases and a conflict-resolution policy are approved.

Every phase requires concrete business examples and tests before scope expands.

Retirements, walkovers, forfeits, no-shows, and administrative result changes are explicitly deferred from Phase 1. They require a separately specified result-state model before implementation.
