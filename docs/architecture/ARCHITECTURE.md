# Architecture

## Dependencies

```text
WordPress adapters → Application → Domain
Infrastructure adapters → Application → Domain
```

`Domain` depends on no outer layer. `Application` knows interfaces (ports); concrete SQL and WordPress implementations remain at the system boundary.

## Source structure

```text
src/
├── Domain/
│   ├── Competition/       stages, advancement, regulations
│   ├── Club/  Team/  Player/  Registration/
│   ├── TeamMatch/         team fixture
│   ├── IndividualMatch/   singles or doubles rubber
│   ├── Standings/  Statistics/
├── Application/
│   ├── Command/  Query/  DTO/  Projection/  Port/
├── Infrastructure/
│   ├── Persistence/  Migration/  Projection/  Cache/  ImportExport/
├── WordPress/
│   ├── Admin/  Rest/  Blocks/  Shortcodes/  Templates/  Plugin.php
└── Shared/
```

## Public rendering path

```text
Block / REST / shortcode → Application query → read-model repository → ViewModel → WordPress renderer → HTML or JSON
```

Neither presentation nor a display query determines a score, rank, or advancement decision.

## Database

All Rally tables use `{$wpdb->prefix}dpr_`; the WordPress prefix is never hard-coded. Planned groups are identities (`dpr_clubs`, `dpr_teams`, `dpr_players`), competition (`dpr_competitions`, `dpr_seasons`, `dpr_stages`), results (`dpr_team_matches`, `dpr_individual_matches`, `dpr_sets`), and projections (`dpr_stage_standings` and statistics). The exact schema is introduced through versioned migrations after the first use case is confirmed.

## Distribution and continuity

Rally is designed to preserve and distribute public competition data beyond a
single Stoni.rs installation. The initial model has one authoritative
publisher, Stoni.rs, and read-only replicas. A replica may serve verified data
or submit proposed corrections, but only an authorized publisher/editor can
make a change canonical. WordPress administrator access and node ownership are
separate from data-publishing authority.

An archive mirror may retain verified exports without running WordPress or
Rally. A full Rally node, or a compatible implementation, is needed to import,
verify, query, and serve data through the Rally node protocol. The public
versioned export and replication protocol must remain independent of the
database schema and must not require direct database access between nodes.

Replicate canonical source records with stable global IDs and provenance.
Treat standings, rankings, and statistics as rebuildable projections. A node
must be able to detect incomplete, stale, or invalid data and rebuild
projections from verified records. Preserve corrections and deletions in the
replicated history rather than silently removing records.

Verify published changes using trusted signing keys. Key rotation, revocation,
and publisher succession must work without the Stoni.rs server; recovery uses
a documented maintainer process and a configurable signature threshold.
Private keys remain with their maintainers and are not copied to each node.
The exact protocol, key format, and recovery procedure remain future
implementation work. Independent replicas do not publish competing canonical
histories in the initial design. See [ADR 0004](../adr/0004-distributed-data-and-continuity.md).

Only approved public data is replicated. Private contact details and other
non-public personal data are excluded unless an explicit policy permits them.
