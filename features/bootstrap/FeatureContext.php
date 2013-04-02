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
        $this->existsMeta('twitter:title') &&
        $this->existsMeta('twitter:description') &&
        $this->existsMeta('twitter:image');
    }

    /**
     * @Given /^meta tags must be corract values$/
     */
    public function metaTagsMustBeCorractValues()
    {
        $this->compareMeta('twitter:card', 'summary');
        //$this->compareMeta('twitter:site');
        //$this->compareMeta('twitter:creator');
        $this->compareMeta('twitter:url', $this->getSession()->getCurrentUrl());
        $this->compareMeta('twitter:title', $this->getSession()->getPage()->find('css', '#image-block img')->getAttribute('alt'));
        $this->compareMeta('twitter:description', $this->getSession()->getPage()->find('css', 'meta[name="description"]')->getAttribute('content'));
        $this->compareMeta('twitter:image', $this->getSession()->getPage()->find('css', '#image-block img')->getAttribute('src'));
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