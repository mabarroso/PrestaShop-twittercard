Feature: homepage
  In order to access the site
  As a Twitter bot
  I need to be able to read meta tags from product page

  Scenario: Browsing to the homepage
    Given I am on homepage
    When I go to the first product
    Then meta tags must be set

