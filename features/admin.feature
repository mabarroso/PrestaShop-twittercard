Feature: Admin
  In order to manage module
  As a Shop administrator
  I need to be able to manage module

  Background:
    Given I am on admin login
    When I am logged in
    Then I should see "Welcome"
    And module must be uninstalled

  Scenario: Install module
    Given I am on admin homepage
    When I go to admin modules page
    And looking for module
    And click install
    Then I should see "Module(s) installed successfully"

  Scenario: Uninstall module
    Given I am on admin homepage
    When I go to admin modules page
    And looking for module
    And click install
    And looking for module
    And click uninstall
    Then I should see "Module(s) uninstalled successfully"

  Scenario: Configure module
    Given I am on admin homepage
    When I go to admin modules page
    And looking for module
    And click install
    And looking for module
    And I follow "Configure"
    And I set configuration
    Then I should see "Settings updated successfully"
