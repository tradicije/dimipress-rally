# Contributing to DimiPress Rally

Thank you for contributing. Rally is designed as a reusable WordPress competition engine, not as a site-specific implementation.

## Before you start

Read the [architecture](docs/architecture/ARCHITECTURE.md), [domain glossary](docs/domain/UBIQUITOUS_LANGUAGE.md), and repository rules in [AGENTS.md](AGENTS.md). Propose an architecture decision record (ADR) before changing a long-term boundary, data model, or public contract.

## Development expectations

- Target PHP 8.2 or later and follow PSR-12.
- Keep domain and application code independent of WordPress and SQL.
- Add focused tests for every business rule and regression.
- Use custom `dpr_` tables for competition data.
- Keep public blocks, REST endpoints, and shortcodes free of business calculations.
- Keep all repository-facing documentation and code comments in English.

## Pull requests

Keep each change small and coherent. Explain the use case, the domain rule it changes, migration impact, and tests performed. Do not mix refactoring with unrelated behavior changes.

## Local checks

The initial quality toolchain will be introduced in Roadmap Phase 0. Until then, run Composer's autoload validation when Composer is available and manually review layer-boundary compliance.
