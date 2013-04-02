<?php

define('PRESTASHOP_ADMIN_PATH', '/prestashop/sandbox/adm/');
define('PRESTASHOP_ADMIN_USER', 'test@test.test');
define('PRESTASHOP_ADMIN_PASSWORD', 'testtest');

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Behat\MinkExtension\Context\MinkContext;

/**
 * Features context.
 */
class FeatureContext extends MinkContext
{
    /**
     * @When /^I go to the first product$/
     */
    public function iGoToTheFirstProduct()
    {
        $this->getSession()->getPage()->find('css', '#featured-products_block_center .first_item a')->click();
    }

    /**
     * @Then /^meta tags must be set$/
     */
    public function metaTagsMustBeSet()
    {
        $this->existsMeta('twitter:card') &&
        $this->existsMeta('twitter:site') &&
        $this->existsMeta('twitter:creator') &&
        $this->existsMeta('twitter:url') &&
        $this->existsMeta('twitter:title') &&
        $this->existsMeta('twitter:description') &&
        $this->existsMeta('twitter:image');
    }

    /**
     * @Given /^meta tags must be correct values$/
     */
    public function metaTagsMustBeCorrectValues()
    {
        $this->compareMeta('twitter:card', 'summary');
        //$this->compareMeta('twitter:site');
        //$this->compareMeta('twitter:creator');
        $this->compareMeta('twitter:url', $this->getSession()->getCurrentUrl());
        $this->compareMeta('twitter:title', $this->getSession()->getPage()->find('css', '#image-block img')->getAttribute('alt'));
        $this->compareMeta('twitter:description', $this->getSession()->getPage()->find('css', 'meta[name="description"]')->getAttribute('content'));
        $this->compareMeta('twitter:image', $this->getSession()->getPage()->find('css', '#image-block img')->getAttribute('src'));
    }

    /**
     * @Given /^I am on admin login$/
     */
    public function iAmOnAdminLogin()
    {
        $this->getSession()->visit(PRESTASHOP_ADMIN_PATH.'index.php?controller=AdminLogin');
    }

    /**
     * @When /^I am logged in$/
     */
    public function iAmLoggedIn()
    {
        $this->getSession()->getPage()->find('css', 'input[name="email"]')->setValue(PRESTASHOP_ADMIN_USER);
        $this->getSession()->getPage()->find('css', 'input[name="passwd"]')->setValue(PRESTASHOP_ADMIN_PASSWORD);
        $this->getSession()->getPage()->find('css', 'input[name="submitLogin"]')->click();
    }

    /**
     * @Given /^I am on admin homepage$/
     */
    public function iAmOnAdminHomepage()
    {
        $this->getSession()->visit(PRESTASHOP_ADMIN_PATH);
    }

    /**
     * @When /^I go to admin modules page$/
     */
    public function iGoToAdminModulesPage()
    {
        $link = $this->getSession()->getPage()->find('xpath', '//a[text()="modules"]');
        if (is_null($link)) {
            $link = $this->getSession()->getPage()->find('xpath', '//a[text()="módulos"]');
        }
        $link->click();
    }

    /**
     * @Given /^looking for module$/
     */
    public function lookingForModule()
    {
        $this->getSession()->getPage()->find('css', 'input[name="filtername"]')->setValue('twittercard');
        $this->getSession()->getPage()->find('css', '#filternameForm input[value="Buscar"]')->click();
        $this->assertPageContainsText('Twitter Card');
    }

    /**
     * @Given /^click install$/
     */
    public function clickInstall()
    {
        $flag = $this->getSession()->getPage()->find('css', '#anchorProducttooltip .non-install');
        if (is_null($flag)) {
          throw new Exception("Module already installed");
        }
        $this->getSession()->getPage()->find('css', '#list-action-button a')->click();

    }

    /**
     * @Given /^click uninstall$/
     */
    public function clickUninstall()
    {
        $flag = $this->getSession()->getPage()->find('css', '#anchorProducttooltip .non-install');
        if (!is_null($flag)) {
          throw new Exception("Module already uninstalled");
        }
        $this->getSession()->getPage()->find('css', '#list-action-button a')->click();
    }

    private function existsMeta($metaName) {
      if (!$this->getSession()->getPage()->find('css', 'meta[name="'.$metaName.'"]')) {
        throw new Exception("Meta tag '$metaName' not found in {$this->getSession()->getCurrentUrl()} page");
        return false;
      }
      return true;
    }

    private function compareMeta($metaName, $value) {
      if ($this->existsMeta($metaName)) {
        $metaValue = $this->getSession()->getPage()->find('css', 'meta[name="'.$metaName.'"]')->getAttribute('content');
        if ($metaValue != $value) {
          throw new Exception("Meta tag '$metaName' fails in {$this->getSession()->getCurrentUrl()} page. \nExpected:\n\t$value\nWas:\n\t$metaValue");
        }
      }
      return true;
    }
}