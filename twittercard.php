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
		<div style="position:absolute;top:300px;right:130px;background:#7B0099;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;color:#ffffff;width:170px;height:150px;border:2px solid #7B0099;padding:15px;">
		<p style="padding-bottom:25px;text-align:center;">I spend a lot of time making and improving this plugin, any donation would be very helpful for me, thank you very much :)</p>
		<form id="paypalform" action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="NMR62HAEAHCRL"><input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypalobjects.com/pt_BR/i/scr/pixel.gif" width="1" height="1"></form>
		</div>

		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">';

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
			<center><input type="submit" name="submitFace" value="'.$this->l('Save').'" class="button" /></center>
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

