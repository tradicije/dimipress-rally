# ADR 0002: Gutenberg blocks are the primary public presentation API

## Status

Accepted.

## Decision

Rally presents public data primarily through dynamic Gutenberg blocks. Shortcodes are optional, thin compatibility adapters.

## Consequences

Every block uses an application query and ViewModel, and a server-side renderer produces output. Business calculations are prohibited in blocks, shortcodes, REST controllers, and templates.
