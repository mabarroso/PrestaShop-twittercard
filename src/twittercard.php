<?php
/*
*  @author mabarroso <http://github.com/mabarroso/>
*  @copyright  2013 mabarroso
*  @license    Released under the MIT license: http://www.opensource.org/licenses/MIT
*/

if (!defined('_PS_VERSION_'))
	exit;

class Twittercard extends Module
{
	private $_postErrors = array();

	public function __construct()
	{
		$this->name = 'twittercard';
		$this->tab = 'front_office_features';
		$this->version = 0.1;
		$this->author = 'mabarroso';
		$this->need_instance = 0;
		$this->module_key = '9650005d954151b29b7f3028948a71fb';

		$this->_directory = dirname(__FILE__).'/../../';
		parent::__construct();

		$this->displayName = $this->l('Twitter Card for products');
		$this->description = $this->l('Generate the products twitter card header metas');
	}

	function install()
	{
	 	if (
			!parent::install() OR
			!$this->registerHook('header') OR
			!Configuration::updateValue('TWITTER_CARD_SITE', '@mabarroso') OR
			!Configuration::updateValue('TWITTER_CARD_CREATOR', '@mabarroso')
		)
	 		return false;
	 	return true;
	}

	public function uninstall()
	{
	 	if (
			!parent::uninstall() OR
			!$this->unregisterHook('header') OR
			!Configuration::deleteByName('TWITTER_CARD_SITE') OR
			!Configuration::deleteByName('TWITTER_CARD_CREATOR')
		)
	 		return false;
	 	return true;
	}

	public function getContent()
	{
		$this->_html = '';
		if (Tools::isSubmit('submitFace'))
		{
			Configuration::updateValue('TWITTER_CARD_SITE', Tools::getValue('site'));
			Configuration::updateValue('TWITTER_CARD_CREATOR', Tools::getValue('creator'));
			$this->_html .= $this->displayConfirmation($this->l('Settings updated successfully'));
		}

		$this->_html .= '
		<div style="position:absolute;top:300px;right:130px;background:#DB00F9;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;color:#000000;width:170px;height:150px;border:2px solid #7B0099;padding:15px;">
		<p style="padding-bottom:25px;text-align:center;color:#000000;">I spend a lot of time making and improving this plugin, any donation would be very helpful for me, thank you very much :)</p>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----"><input type="image" src="https://www.paypalobjects.com/en_US/ES/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1"></form>
		</div>';

		$this->_html .= '
		<div id="twittercardcfg">
		<form action="'.$_SERVER['REQUEST_URI'].'" method="get">';

		$this->_html .='
		<fieldset>
			<div class="margin-form" style="padding:0 0 1em 100px;">
				<label style="width:162px;text-align:left;">'.$this->l('twitter:site').'</label>
				<input type="text" name="site" id="site" value="'.(Configuration::get('TWITTER_CARD_SITE')).'" /><br/>
				<small style="padding-left:164px;padding-top:10px;display:block;font-size:11px;">@username for the website used in the card footer</small>
			</div>
			<div class="margin-form" style="padding:0 0 1em 100px;">
				<label style="width:162px;text-align:left;">'.$this->l('twitter:creator').'</label>
				<input type="text" name="creator" id="creator" value="'.(Configuration::get('TWITTER_CARD_CREATOR')).'" /><br/>
				<small style="padding-left:164px;padding-top:10px;display:block;font-size:11px;">@username for the content creator / author</small>
			</div>
			<div style="text-align:center;"><input type="submit" name="submitFace" value="'.$this->l('Save').'" class="button" /></div>
		</fieldset><br/>
		</form>
		</div>';

		$this->_html  .='
		<fieldset>
		  <div class="margin-form" style="padding:0 0 1em 100px;">
		    You can test your configuration in <a href="https://dev.twitter.com/docs/cards/preview">https://dev.twitter.com/docs/cards/preview</a>
			</div>
		  <div class="margin-form" style="padding:0 0 1em 100px;">
		    You must request you participation in Twitter Cards (this take several days by  approval Twitter Team) in <a href="https://dev.twitter.com/form/participate-twitter-cards">https://dev.twitter.com/form/participate-twitter-cards</a>
			</div>
		  <div class="margin-form" style="padding:0 0 1em 100px;">
		    You could read the official Twitter documentation in <a href="https://dev.twitter.com/docs/cards">https://dev.twitter.com/docs/cards</a>
			</div>
		</fieldset><br/>';

		return $this->_html;
	}

	function hookHeader($params)
	{
		global $smarty;

		$id_product = (int)Tools::getValue('id_product');
		$id_lang = (int)$params['cookie']->id_lang;

		$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
			SELECT p.*, pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, p.`ean13`,  p.`upc`,
				i.`id_image`, il.`legend`, t.`rate`
			FROM `'._DB_PREFIX_.'product` p
			LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)($id_lang).')
			LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
			LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)($id_lang).')
			LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (p.`id_tax_rules_group` = tr.`id_tax_rules_group`
													   AND tr.`id_country` = '.(int)Country::getDefaultCountryId().'
													   AND tr.`id_state` = 0)
			LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
			WHERE p.id_product = '.(int)$id_product);

		$product = Product::getProductProperties($id_lang, $row);

		// Instantiate the class to get the product description
		$description = new Product($id_product, true, $id_lang);

		$smarty->assign(array(
			'description_short' => strip_tags($description->description_short),
			'description' => strip_tags($description->description),
			'product' => $product,
			'site' => Configuration::get('TWITTER_CARD_SITE'),
			'creator' => Configuration::get('TWITTER_CARD_CREATOR')
			));

		return $this->display(__FILE__, 'head.tpl');
	}
}

