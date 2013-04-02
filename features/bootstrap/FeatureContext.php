<?php

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
        $this->existsMeta('twitter:description') &&
        $this->existsMeta('twitter:image');
    }

    private function existsMeta($metaName) {
      if (!$this->getSession()->getPage()->find('css', 'meta[name="'.$metaName.'"]')) {
        throw new Exception("Meta tag '$metaName' not found in {$this->getSession()->getCurrentUrl()} page");
        return false;
      }
      return true;
    }
}