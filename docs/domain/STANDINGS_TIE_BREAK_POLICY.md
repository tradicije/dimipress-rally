# Initial standings tie-break policy

## In-season ranking

While a season is active, teams tied on standings points are ordered by the individual-match differential across confirmed team matches in the stage: individual matches won minus individual matches lost. For example, a 4:2 team-match win contributes `+2`, while a 2:4 loss contributes `-2`. This follows LibreTT's existing `meckol` calculation.

This value is a difference, not a division-based ratio. The projection must preserve the underlying wins and losses so that the policy remains rebuildable. Any subsequent tie-break criteria remain to be specified.

## Final ranking

After a season is completed, teams tied on standings points are ordered first by head-to-head result.

The exact mini-table rules for three or more tied teams, as well as subsequent criteria after head-to-head, remain explicitly open. They must be specified before final season placement is implemented.

## State boundary

The stage or season state determines which ranking policy is used. A public standings query must read the already selected policy result and must not infer this itself.
