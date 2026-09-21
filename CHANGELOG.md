# Changelog

All notable changes to DimiPress Rally are documented in this file.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- `IndividualMatch` domain aggregate for best-of-five-set singles and doubles rubbers.
- `SetScore` domain value object and PHPUnit coverage for standard and deuce set scoring.
- Initial PSR-4 plugin skeleton with a WordPress bootstrap adapter.
- Composer development toolchain: PHPUnit, PHPStan, and PHPCS.
- Domain architecture, glossary, roadmap, ADRs, contribution guide, security policy, and AGPL-3.0-or-later license.
- First-to-four team-match specification and acceptance scenarios.
- Initial 2/1 standings-points policy and in-season/final-season tie-break policy.

### Changed

- Defined Gutenberg blocks as the primary public presentation API; shortcodes are compatibility adapters only.
- Defined custom `{$wpdb->prefix}dpr_*` tables as the persistence model for competition data.
