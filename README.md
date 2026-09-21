# DimiPress Rally

An extensible WordPress engine for table-tennis competitions, team fixtures, individual rubbers, standings, and statistics.

Rally is a generic product. STKB.rs is a pilot installation, not part of the domain model, database schema, or public API.

## Core principles

- Dedicated normalized tables with the `dpr_` suffix; never WordPress CPTs or post meta for competition data.
- PHP 8.2+, Composer, and PSR-4: `DimiPress\\Rally\\` maps to `src/`.
- `Domain` and `Application` must not call WordPress or SQL.
- Confirmed results are the source of truth; standings and statistics are rebuildable projections.
- Gutenberg blocks are the primary public presentation API. Shortcodes are compatibility adapters only.

## Documentation

- [Architecture](docs/architecture/ARCHITECTURE.md)
- [Domain glossary](docs/domain/UBIQUITOUS_LANGUAGE.md)
- [Competition engine](docs/domain/COMPETITION_ENGINE.md)
- [First-to-four match format](docs/domain/MATCH_FORMAT_FIRST_TO_FOUR.md)
- [Initial standings policy](docs/domain/STANDINGS_POLICY_TWO_ONE.md)
- [Roadmap](docs/product/ROADMAP.md)
- [Architecture decisions](docs/adr/)

## Development

This is a project skeleton. Before implementing a feature, define its use case, business rules, and test cases.

See [CONTRIBUTING.md](CONTRIBUTING.md) for local development and contribution guidance. Rally is licensed under the [GNU AGPL v3.0 or later](LICENSE).
