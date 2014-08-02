<?php defined('_JEXEC') or die('Restricted access');

class com_ksenmartInstallerScript {
	
	public function install($parent) {
		file_get_contents('http://update.ksenmart.ru/statistic/?domain=' . $_SERVER['HTTP_HOST']);
	}

	function update($parent) {}
	
	function preflight($type, $parent) {}
	
	function postflight($type, $parent) {
		jimport('joomla.installer.helper');

		if(!defined('DIRECTORY_SEPARATOR')) define('DIRECTORY_SEPARATOR', DIRECTORY_SEPARATOR);

		$path = JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_ksenmart' . DIRECTORY_SEPARATOR . 'install';
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		if (!JFile::move($path . DIRECTORY_SEPARATOR . 'administrator-templates-system' . DIRECTORY_SEPARATOR . 'ksenmart.php', JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart.php')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage('Couldnt move file');
		}
		if (!JFile::move($path . DIRECTORY_SEPARATOR . 'administrator-templates-system' . DIRECTORY_SEPARATOR . 'ksenmart-full.php', JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart-full.php')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage('Couldnt move file');
		}
		if (!JFile::move($path . DIRECTORY_SEPARATOR . 'templates-system' . DIRECTORY_SEPARATOR . 'ksenmart.php', JPATH_ROOT . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart.php')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage('Couldnt move file');
		}
		JFolder::create(JPATH_ROOT . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ksenmart');
		JFolder::copy($path . DIRECTORY_SEPARATOR . 'images-ksenmart', JPATH_ROOT . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ksenmart', null, 1);
	
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$params = '{"shop_name":"","shop_email":"","shop_phone":"","modules_styles":"1","watermark":"0","watermark_image":"","watermark_type":"0","watermark_valign":"middle","watermark_halign":"center","displace":"1","valign":"middle","halign":"center","background_type":"color","background_file":"","background_color":"ffffff","show_products_from_subcategories":"1","show_out_stock":"1","order_process":"0","show_comment_form":"1","site_product_limit":"15","site_use_pagination":"1","parent_products_template":"list","include_jquery":"1","include_css":"1","only_auth_buy":"0","use_stock":"0","catalog_mode":"0","path_separator":" - ","thumb_width":"200","thumb_height":"200","middle_width":"350","middle_height":"350","mini_thumb_width":"110","mini_thumb_height":"110","count_symbol":"400","printforms_companyname":"","printforms_companyaddress":"","printforms_companyphone":"","printforms_nds":"","printforms_ceo_name":"","printforms_buh_name":"","printforms_bank_account_number":"","printforms_inn":"","printforms_kpp":"","printforms_bankname":"","printforms_bank_kor_number":"","printforms_bik":"","printforms_ip_name":"","printforms_ip_registration":"","printforms_company_logo":"","printforms_congritulation_message_template":"<p>\u0412\u0430\u0448\u0435\u043c\u0443 \u0437\u0430\u043a\u0430\u0437\u0443 \u043f\u0440\u0438\u0441\u0432\u043e\u0435\u043d \u043d\u043e\u043c\u0435\u0440 %s<\/p><p>\u041d\u0430\u0448\u0438 \u043c\u0435\u043d\u0435\u0434\u0436\u0435\u0440\u044b \u0441\u0432\u044f\u0436\u0443\u0442\u0441\u044f \u0441 \u0432\u0430\u043c\u0438 \u0432 \u0442\u0435\u0447\u0435\u043d\u0438\u0438 2 \u0447\u0430\u0441\u043e\u0432.<\/p><p>\u0422\u0430\u043a \u0436\u0435 \u0432\u044b \u043c\u043e\u0436\u0435\u0442\u0435 \u0443\u0437\u043d\u0430\u0442\u044c \u043e \u0441\u0442\u0430\u0442\u0443\u0441\u0435 \u0437\u0430\u043a\u0430\u0437\u0430 \u043f\u043e \u0442\u0435\u043b\u0435\u0444\u043e\u043d\u0443: 8 800 2000 600<\/p>"}';
		$query->select('*')->from('#__extensions')->where('name="ksenmart"');
		$db->setQuery($query);
		$extension = $db->loadObject();
		$table = JTable::getInstance('extension');
		$table->load($extension->extension_id);
		$table->params = $params;
		$table->store();
		
		JFolder::delete($path);
	}
	
	function uninstall($parent) {
		jimport('joomla.installer.helper');
		if (file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart.php') && !JFile::delete(JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart.php')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage('Couldnt delete file');
		}
		if (file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart-full.php') && !JFile::delete(JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart-full.php')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage('Couldnt delete file');
		}
		if (file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart.php') && !JFile::delete(JPATH_ROOT . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart.php')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage('Couldnt delete file');
		}
	}
}
