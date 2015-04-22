<?php defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.folder');

class ksenmartcolorfulInstallerScript extends KSInstaller {
	
	function __construct($installer) {
		parent::__construct($installer);
	}	
	
	public function install(){
		$this->installModule('Форма входа (Ksenmart)', 'mod_login', 'ks-auth', '{}');
		$this->installModule('Хлебные крошки (Ksenmart)', 'mod_breadcrumbs', 'ks-breadcrumbs', '{}');
		$this->installModule('Миникорзина (Ksenmart)', 'mod_km_minicart', 'ks-minicart', '{}');		
		$this->installModule('Меню пользователя (Ksenmart)', 'mod_km_profile_info', 'ks-profile', '{}');		
		$this->installModule('Категории (Ksenmart)', 'mod_km_categories', 'ks-categories', '{}');		
		$this->installModule('Доставка (Ksenmart)', 'mod_km_shipping', 'ks-shipping-info', '{}');	
		$this->installModule('Фильтры (Ksenmart)', 'mod_km_filter', 'ks-filters', '{}', 'catalog');			
		$this->installModule('Отзывы (Ksenmart)', 'mod_km_shop_reviews', 'ks-reviews', '{}');		
		$this->installModule('Поиск по каталогу (Ksenmart)', 'mod_km_simple_search', 'ks-search', '{}');		
		$this->installModule('Список товаров (Ksenmart)', 'mod_km_products_list', 'ks-main-products-list', '{}', 'main');		
		$this->installPseudoHTMLModule('Главное меню (Ksenmart)', 'mod_menu', 'ks-menu');
		$this->installPseudoHTMLModule('Информационное меню (Ksenmart)', 'mod_menu', 'ks-info-menu');
		$this->installPseudoHTMLModule('Баннеры (Ksenmart)', 'mod_banners', 'ks-main-banners', 'main');
		$this->installHTMLModule('Инфо в шапке (Ksenmart Colorful)', 'ks-clrful-header-info');
		$this->installHTMLModule('Инфо в подвале 1 (Ksenmart Colorful)', 'ks-clrful-footer-info1');
		$this->installHTMLModule('Инфо в подвале 2 (Ksenmart Colorful)', 'ks-clrful-footer-info2');

		if (JFolder::exists($this->installer->getPath('extension_root') . '/install/'))
		{
			JFolder::delete($this->installer->getPath('extension_root') . '/install/');
		}
		
		return true;
	}
	
	public function uninstall(){
		$this->uninstallModule('mod_custom', 'ks-clrful-header-info');
		$this->uninstallModule('mod_custom', 'ks-clrful-footer-info1');
		$this->uninstallModule('mod_custom', 'ks-clrful-footer-info2');
		$this->uninstallModule('mod_custom', 'ks-clrful-footer-info3');
		$this->uninstallModule('mod_custom', 'ks-clrful-footer-info4');

		return true;		
	}

}