# Changelog

All notable changes to DimiPress Rally are documented in this file.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Team-match roster, singles-lineup, doubles-lineup, and substitution-window domain models.
- `FirstToFourTeamMatch` domain aggregate for the initial fixed rubber order, early completion, and conditional doubles rubber.
- `IndividualMatch` domain aggregate for best-of-five-set singles and doubles rubbers.
- `SetScore` domain value object and PHPUnit coverage for standard and deuce set scoring.
- Initial PSR-4 plugin skeleton with a WordPress bootstrap adapter.
- Composer development toolchain: PHPUnit, PHPStan, and PHPCS.
- Domain architecture, glossary, roadmap, ADRs, contribution guide, security policy, and AGPL-3.0-or-later license.
- First-to-four team-match specification and acceptance scenarios.
- Initial 2/1 standings-points policy and in-season/final-season tie-break policy.

### Changed

- Corrected completed-set validation to reject scores reached after the set should already have ended.
- Defined the in-season individual-match differential consistently with LibreTT's `meckol` calculation.
- Aligned Composer package metadata with the project's AGPL-3.0-or-later license.
- Ignored generated dependency and test-cache directories for future Git publishing.
- Made the team-match aggregate own both singles lineups and enforce its one-time substitution window.
- Defined Gutenberg blocks as the primary public presentation API; shortcodes are compatibility adapters only.
- Defined custom `{$wpdb->prefix}dpr_*` tables as the persistence model for competition data.
