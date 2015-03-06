<?php defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.folder');

class ksenmartinfantInstallerScript extends KSInstaller {
	
	function __construct($installer) {
		parent::__construct($installer);
	}	
	
	public function install(){
		$this->installModule('Форма входа (Ksenmart)', 'mod_login', 'ks-auth', '{}');
		$this->installModule('Хлебные крошки (Ksenmart)', 'mod_breadcrumbs', 'ks-breadcrumbs', '{}');
		$this->installModule('Фильтры (Ksenmart)', 'mod_km_filter', 'ks-filters', '{}', 'catalog');		
		$this->installModule('Миникорзина (Ksenmart)', 'mod_km_minicart', 'ks-minicart', '{}');		
		$this->installModule('Категории (Ksenmart)', 'mod_km_categories', 'ks-categories', '{}');		
		$this->installModule('Список товаров (Ksenmart)', 'mod_km_products_list', 'ks-main-products-list', '{}', 'main');	
		$this->installModule('Поиск по сайту (Ksenmart Infant)', 'mod_search', 'ks-inf-search', '{"label":"","width":"20","text":"","button":"1","button_pos":"right","imagebutton":"0","button_text":" ","opensearch":"1","opensearch_title":"","set_itemid":"0","layout":"_:default","moduleclass_sfx":"","cache":"1","cache_time":"900","cachemode":"itemid","module_tag":"div","bootstrap_size":"0","header_tag":"h3","header_class":"","style":"0","project":null}');	
		$this->installPseudoHTMLModule('Главное меню (Ksenmart)', 'mod_menu', 'ks-menu');
		$this->installPseudoHTMLModule('Баннеры (Ksenmart)', 'mod_banners', 'ks-main-banners', 'main');
		$this->installPseudoHTMLModule('Меню в подвале (Ksenmart)', 'mod_menu', 'ks-footer-menu');
		$this->installPseudoHTMLModule('Категории в подвале (Ksenmart)', 'mod_menu', 'ks-footer-categories');
		$this->installHTMLModule('Копирайт (Ksenmart)', 'ks-footer-copyright');
		$this->installHTMLModule('ЛДМ (Ksenmart)', 'ks-footer-ldm');
		$this->installHTMLModule('Соцсети в подвале (Ksenmart Infant)', 'ks-inf-footer-social');
		$this->installHTMLModule('Контакты в подвале (Ksenmart Infant)', 'ks-inf-footer-contacts');
		$this->installHTMLModule('Инфо на главной 1 (Ksenmart Infant)', 'ks-inf-main-info1', 'main');
		$this->installHTMLModule('Инфо на главной 2 (Ksenmart Infant)', 'ks-inf-main-info2', 'main');
		$this->installHTMLModule('Инфо на главной 3 (Ksenmart Infant)', 'ks-inf-main-info3', 'main');

		if (JFolder::exists($this->installer->getPath('extension_root') . '/install/'))
		{
			JFolder::delete($this->installer->getPath('extension_root') . '/install/');
		}
		
		return true;
	}
	
	public function uninstall(){
		$this->uninstallModule('mod_search', 'ks-inf-search');
		$this->uninstallModule('mod_custom', 'ks-inf-footer-social');
		$this->uninstallModule('mod_custom', 'ks-inf-footer-contacts');
		$this->uninstallModule('mod_custom', 'ks-inf-main-info1');
		$this->uninstallModule('mod_custom', 'ks-inf-main-info2');
		$this->uninstallModule('mod_custom', 'ks-inf-main-info3');

		return true;		
	}
	
}