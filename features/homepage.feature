Feature: homepage
  In order to access the site
  As a website user
  I need to be able to reach the homepage

  Scenario: Browsing to the homepage
    Given I am on homepage
    Then the response status code should be 200
