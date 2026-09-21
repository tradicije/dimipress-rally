# DimiPress Rally development rules

This repository is a WordPress plugin, but its business engine is not WordPress-dependent.

## Layer boundaries

- `src/Domain`: entities, value objects, rules, and events. No WordPress, SQL, or HTTP.
- `src/Application`: commands, queries, DTOs, and use-case orchestration through ports/interfaces.
- `src/Infrastructure`: SQL repositories, migrations, cache, projectors, and infrastructure adapters.
- `src/WordPress`: bootstrap, hooks, admin, REST, blocks, shortcode compatibility, and templates.
- `src/Shared`: genuinely shared small types only; never a dumping ground.

## Data and WordPress rules

- Competition data uses custom tables: `{$wpdb->prefix}dpr_*`.
- Do not use CPTs or post meta for clubs, teams, players, results, standings, or statistics.
- Do not calculate standings, rankings, or aggregates in blocks, shortcodes, REST controllers, or templates.
- Confirmed results are the source of truth; projections must be rebuildable.
- Gutenberg blocks are the primary public API. Shortcodes only delegate to the same query and renderer.
- Never introduce STKB-specific names, URLs, regulations, seed data, or theme assumptions into the core.

## Working agreement

1. Record an ADR before an implementation that changes future architecture.
2. Write a unit test for a business rule before implementing its use case.
3. Do not add a generic rules DSL without a concrete regulation and tests that justify it.
4. Preserve history: rules used by a started season or stage must not be retroactively changed.
