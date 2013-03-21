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
		return (parent::install() && $this->registerHook('header'));
	}

	public function uninstall()
	{
	 	if (
			!parent::uninstall() OR
			!$this->unregisterHook('header')
		)
	 		return false;
	 	return true;
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
			'product' => $product
			));

		return $this->display(__FILE__, 'head.tpl');
	}
}

