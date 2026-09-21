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
