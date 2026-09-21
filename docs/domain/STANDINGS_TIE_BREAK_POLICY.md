# Initial standings tie-break policy

## In-season ranking

While a season is active, teams tied on standings points are ordered by `match ratio`: the ratio of individual-match wins to individual-match losses across confirmed team matches in the stage.

The exact representation, comparison of zero-loss records, and any subsequent tie-break criteria are not yet specified. The projection must preserve the underlying wins and losses so that the policy remains rebuildable.

## Final ranking

After a season is completed, teams tied on standings points are ordered first by head-to-head result.

The exact mini-table rules for three or more tied teams, as well as subsequent criteria after head-to-head, remain explicitly open. They must be specified before final season placement is implemented.

## State boundary

The stage or season state determines which ranking policy is used. A public standings query must read the already selected policy result and must not infer this itself.
