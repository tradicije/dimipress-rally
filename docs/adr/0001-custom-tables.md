# ADR 0001: Use custom tables for competition data

## Status

Accepted.

## Decision

Rally uses normalized custom tables with the `dpr_` suffix. WordPress posts, CPTs, and post meta are not used for competition identities, results, or aggregates.

## Consequences

The system gains indexes and queries suited to large result volumes and explicit migration control. WordPress remains an adapter for users, administration, REST, and presentation.
