# ADR 0004: Design for replicated data and authority recovery

## Status

Accepted.

## Context

Rally may power public table-tennis data that should remain available if the
Stoni.rs service is unavailable or discontinued. Clubs, schools, federations,
organizations, and volunteers may host copies. Copying a database directory is
not sufficient: nodes need to identify complete and trusted data, know which
records are authoritative, and recover control without relying on one server.

## Decision

Rally will be designed so confirmed competition data can be exported, verified,
replicated, and restored independently of a particular WordPress installation
or database schema.

The initial operating model is a single authoritative publisher with
read-only replicas. Stoni.rs is the initial publisher. Replicas may verify and
serve published data, and may submit proposed changes, but they do not make
those changes authoritative by virtue of hosting a copy.

Rally will distinguish these capabilities:

- **Archive mirror:** stores and serves verified exports. It may use Rally or
  another compatible implementation; installing the WordPress plugin is not a
  requirement for retaining an archive.
- **Rally node:** runs Rally and imports, verifies, stores, and serves data
  according to the shared replication format and protocol.
- **Authorized publisher/editor:** has cryptographic authority to publish or
  approve specified changes. Hosting a node or having WordPress administrator
  access does not grant this authority automatically.

Shared identity for clubs, teams, players, competitions, and other entities
must use stable IDs independent of a site's post ID, name, or slug. Replicated
records must carry enough provenance to identify their origin and history.
The public data format and protocol must be versioned and documented, and must
not require direct access to another node's database.

Confirmed source records are replicated as the canonical data. Standings,
rankings, and statistics remain rebuildable projections; a node can rebuild
them from verified source records rather than trusting a copied aggregate.
Replication must support an initial snapshot and subsequent changes, and must
make incomplete, stale, or invalid data detectable. Deletions and corrections
must preserve enough history to replicate and audit them.

Published changes must be verifiable using signatures from trusted publisher
keys. Trust metadata must support key rotation and revocation. Recovery or
transfer of publisher authority must be possible without the Stoni.rs server,
using a documented maintainer succession process and a configurable signature
threshold. A user ID alone is not proof of authority, and a private signing
key must not be copied to every node. The concrete key format, threshold, and
recovery procedure require a separate implementation design.

The initial design does not allow independent replicas to publish competing
canonical histories. Multi-writer federation and conflict resolution require a
separate decision based on demonstrated use cases.

Only data approved for public distribution belongs in replication exports.
Private contact details and other non-public personal data must be excluded
unless a separately documented policy explicitly permits their distribution.

## Consequences

- Core IDs, provenance, change history, exports, and projections must not
  assume a single permanent Stoni.rs database.
- A fully functional Rally node requires Rally or a compatible implementation;
  an archive mirror can preserve data without running the plugin.
- Initial replication is simpler to reason about because replicas are
  read-only for canonical records.
- Publisher keys and succession rules become part of the project's trust and
  operational model and must be documented before production replication.
- This decision defines constraints for future work; it does not require
  implementing replication before the first competition workflow is usable.
