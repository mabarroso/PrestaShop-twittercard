Feature: Admin
  In order to manage module
  As a Shop administrator
  I need to be able to manage module

  Background:
    Given I am on admin login
    When I am logged in
    Then I should see "Welcome"

  Scenario: Install module
    Given I am on admin homepage
    When I go to admin modules page
    And looking for module
    And click install
    Then I should see "Módulo (s) instalado con éxito"

  Scenario: Uninstall module
    Given I am on admin homepage
    When I go to admin modules page
    And looking for module
    And click uninstall
    Then I should see "Módulo (s) desinstalado correctamente"

  Scenario: Configure module

