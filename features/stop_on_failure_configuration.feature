Feature: Stop on failure via configuration
  In order to stop further execution of steps when first step fails
  As a feature developer
  I need to have a configuration.stop_on_failure sets to true

  Background:
    Given a file named "features/bootstrap/FeatureContext.php" with:
      """
      <?php

      use Behat\Behat\Context\Context;

      class FeatureContext implements Context
      {
          /**
           * @Given /^I have (?:a|another) step that passes?$/
           * @Then /^I should have a scenario that passed$/
           */
          public function passing() {
          }

          /**
           * @Given /^I have (?:a|another) step that fails?$/
           * @Then /^I should have a scenario that failed$/
           */
          public function failing() {
              throw new Exception("step failed as supposed");
          }

      }
      """
    And a file named "features/failing.feature" with:
      """
      Feature: Failing Feature
        In order to test the stop-on-failure feature
        As a behat developer
        I need to have a feature that fails

        Background:
          Given I have a step that passes

        Scenario: 1st Passing
          When I have a step that passes
          Then I should have a scenario that passed

        Scenario: 1st Failing
          When I have a step that passes
           And I have another step that fails
          Then I should have a scenario that failed

        Scenario: 2st Failing
          When I have a step that fails
          Then I should have a scenario that failed

        Scenario: 2nd Passing
          When I have a step that passes
           And I have another step that passes
          Then I should have a scenario that passed
      """

  Scenario: with stop_on_failure set to false
   Given a file named "behat.yml" with:
      """
      default:
        configuration:
          stop_on_failure: false
      """
    When I run "behat --no-colors --format-settings='{\"paths\": false}' features/failing.feature"
    Then it should fail with:
      """
      --- Failed scenarios:

          features/failing.feature:13
          features/failing.feature:18

      4 scenarios (2 passed, 2 failed)
      14 steps (10 passed, 2 failed, 2 skipped)
      """

  Scenario: with stop_on_failure default value (false)
   Given a file named "behat.yml" with:
      """
      default: ~
      """
    When I run "behat --no-colors --format-settings='{\"paths\": false}' features/failing.feature"
    Then it should fail with:
      """
      --- Failed scenarios:

          features/failing.feature:13
          features/failing.feature:18

      4 scenarios (2 passed, 2 failed)
      14 steps (10 passed, 2 failed, 2 skipped)
      """

  Scenario: with stop_on_failure set to true
   Given a file named "behat.yml" with:
      """
      default:
        configuration:
          stop_on_failure: true
      """
    When I run "behat --no-colors --format-settings='{\"paths\": false}' features/failing.feature"
    Then it should fail with:
      """
      --- Failed scenarios:

          features/failing.feature:13

      2 scenarios (1 passed, 1 failed)
      7 steps (5 passed, 1 failed, 1 skipped)
      """