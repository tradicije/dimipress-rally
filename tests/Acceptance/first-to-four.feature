Feature: First-to-four team match format
  A competition administrator records a team match using Rally's initial format.

  Background:
    Given a home team has starting singles slots A, B, and C
    And an away team has starting singles slots Y, X, and Z
    And the rubber sequence is A-Y, B-X, C-Z, A-X, C-Y, B-Z, then doubles

  Scenario: End the team match immediately at four wins
    Given the home team wins rubbers 1, 2, 3, and 4
    When rubber 4 is confirmed
    Then the team match score is 4:0
    And the team match status is completed
    And rubbers 5, 6, and 7 are not played

  Scenario: Play doubles only at three all
    Given each team has won three of the first six rubbers
    When rubber 6 is confirmed
    Then the team match score is 3:3
    And doubles rubber 7 is required

  Scenario: Replacement inherits a singles slot
    Given player Home Reserve replaces player A after rubber 3
    When rubber 4 is generated
    Then Home Reserve plays in home slot A against away slot X

  Scenario: A substituted-out player is eligible for doubles
    Given player A is substituted out after rubber 3
    And player A is in the team-match roster
    When the doubles lineup is selected
    Then player A is an eligible doubles player

  Scenario Outline: Validate a completed set
    When a set ends <home_points>:<away_points>
    Then the set is valid

    Examples:
      | home_points | away_points |
      | 11          | 9           |
      | 12          | 10          |
      | 11          | 13          |

  Scenario: Award league points independently of the team-match score
    Given a confirmed team match ends 4:3 for the home team
    When stage standings are projected
    Then the home team receives 2 standings points
    And the away team receives 1 standings point
