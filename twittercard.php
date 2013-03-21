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
	
	function hookHeader($params)
	{
		return $this->display(__FILE__, 'twittercardHeader.tpl');
	}
}

