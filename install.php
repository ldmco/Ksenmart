<?php
defined('_JEXEC') or die('Restricted access');

class com_ksenmartInstallerScript
{

	function install($parent) 
	{
	}
	
	function update($parent) 
	{
	}	
	
	function preflight($type, $parent) 
	{
	}	

	function postflight($type,$parent){
		jimport('joomla.installer.helper');
		$path = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_ksenmart'.DS.'install';
		$db=JFactory::getDBO();
		$query=$db->getQuery(true);
		if(!JFile::move($path  .DS. 'libraries-joomla-application-component' .DS. 'modelkmadmin.php' ,JPATH_ROOT .DS. 'libraries'.DS.'joomla'.DS.'application'.DS.'component'.DS.'modelkmadmin.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt move file');
		}	
		if(!JFile::move($path  .DS. 'libraries-joomla-application-component' .DS. 'modelkmlist.php' ,JPATH_ROOT .DS. 'libraries'.DS.'joomla'.DS.'application'.DS.'component'.DS.'modelkmlist.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt move file');
		}
		if(!JFile::move($path  .DS. 'libraries-joomla-application-component' .DS. 'viewkm.php' ,JPATH_ROOT .DS. 'libraries'.DS.'joomla'.DS.'application'.DS.'component'.DS.'viewkm.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt move file');
		}
		if(!JFile::move($path  .DS. 'libraries-joomla-application-component' .DS. 'viewkmadmin.php' ,JPATH_ROOT .DS. 'libraries'.DS.'joomla'.DS.'application'.DS.'component'.DS.'viewkmadmin.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt move file');
		}	
		if(!JFile::move($path  .DS. 'libraries-joomla-form' .DS. 'kmform.php' ,JPATH_ROOT .DS. 'libraries'.DS.'joomla'.DS.'form'.DS.'kmform.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt move file');
		}		
		if(!JFile::move($path  .DS. 'libraries-joomla-form-fields' .DS. 'kmcategories.php' ,JPATH_ROOT .DS. 'libraries'.DS.'joomla'.DS.'form'.DS.'fields'.DS.'kmcategories.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt move install file');
		}
		if(!JFile::move($path  .DS. 'libraries-joomla-form-fields' .DS. 'kmadminviews.php' ,JPATH_ROOT .DS. 'libraries'.DS.'joomla'.DS.'form'.DS.'fields'.DS.'kmadminviews.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt move install file');
		}		
		if(!JFile::move($path  .DS. 'libraries-joomla-html-html' .DS. 'kmcategories.php' ,JPATH_ROOT .DS. 'libraries'.DS.'joomla'.DS.'html'.DS.'html'.DS.'kmcategories.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt move file');
		}	
		if(!JFile::move($path  .DS. 'administrator-templates-system' .DS. 'ksenmart.php' ,JPATH_ROOT .DS. 'administrator'.DS.'templates'.DS.'system'.DS.'ksenmart.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt move file');
		}	
		if(!JFile::move($path  .DS. 'administrator-templates-system' .DS. 'ksenmart-full.php' ,JPATH_ROOT .DS. 'administrator'.DS.'templates'.DS.'system'.DS.'ksenmart-full.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt move file');
		}		
		if(!JFile::move($path  .DS. 'templates-system' .DS. 'ksenmart.php' ,JPATH_ROOT .DS. 'templates'.DS.'system'.DS.'ksenmart.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt move file');
		}	
		if(!JFile::move($path  .DS. 'modules-mod_login' .DS. 'ksenmart.php' ,JPATH_ROOT .DS. 'modules'.DS.'mod_login'.DS.'tmpl'.DS.'ksenmart.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt move file');
		}	
		if(!JFile::move($path  .DS. 'modules-mod_banners' .DS. 'ksenmart.php' ,JPATH_ROOT .DS. 'modules'.DS.'mod_banners'.DS.'tmpl'.DS.'ksenmart.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt move file');
		}			
		JFolder::create(JPATH_ROOT.DS.'images'.DS.'ksenmart');
		JFolder::copy($path  .DS.'images-ksenmart', JPATH_ROOT.DS.'images'.DS.'ksenmart', null, 1);	
		JFolder::create(JPATH_ROOT.DS.'media'.DS.'ksenmart');
		JFolder::copy($path  .DS.'media-ksenmart', JPATH_ROOT.DS.'media'.DS.'ksenmart', null, 1);			

		self::KMinstallModule('Фильтры','mod_km_filter',8,'left','{"layout":"_:default","moduleclass_sfx":"filter","cache":"1","cache_time":"900","cachemode":"static"}');
		self::KMinstallModule('Контакты','mod_km_contacts',1,'head_block_2','{"Itemid":"","layout":"_:default","moduleclass_sfx":"","cache":"1"}');
		self::KMinstallModule('Простой поиск','mod_km_simple_search',1,'content_top','{"layout":"_:default","count_result":"5","count_relevants":"3","count_categories":"1","count_manufactured":"1"}');
		self::KMinstallModule('Отзывы','mod_km_shop_reviews',9,'left','{"count_review":"10","count_symbol":"200","layout":"_:default","moduleclass_sfx":"","cache":"1"}');
		self::KMinstallModule('Профиль','mod_km_profile_info',11,'left','{"layout":"_:default"}',0,0);
		self::KMinstallModule('Подписка','mod_km_subscribe',1,'content_bottom','{"layout":"_:default"}');
		self::KMinstallModule('Категории','mod_km_categories',4,'left','{"layout":"_:dropdown","moduleclass_sfx":"","cache":"1","cache_time":"900","cachemode":"static"}');
		self::KMinstallModule('Миникорзина','mod_km_minicart',1,'head_block_3','{"layout":"_:default"}');
		self::KMinstallModule('Доставка','mod_km_shipping',10,'left','{"layout":"_:default"}');
		self::KMinstallModule('Список продуктов','mod_km_products_list',3,'content_top','{"layout":"_:default","col":"10","type":"id"}');
		
		self::KMinstallModule('Форма входа','mod_login',1,'head_block_2','{"pretext":"","posttext":"","login":"","logout":"","greeting":"1","name":"0","usesecure":"0","layout":"_:ksenmart","moduleclass_sfx":"","cache":"0"}');
		self::KMinstallModule('Хлебные крошки','mod_breadcrumbs',2,'content_top','{"showHere":"0","showHome":"1","homeText":"","showLast":"1","separator":"\u2192","layout":"_:default","moduleclass_sfx":"","cache":"1","cache_time":"900","cachemode":"itemid"}');
		
		self::KMinstallModule('mod_km_account_info','mod_km_account_info',1,'km-top-right','{"views":["panel"],"moduleclass_sfx":"","cache":"0"}',1);
		self::KMinstallModule('mod_km_mainmenu','mod_km_mainmenu',1,'km-top-bottom','{"views":["*"],"layout":"_:default","moduleclass_sfx":"","cache":"0"}',1);
		self::KMinstallModule('mod_km_panelhelper','mod_km_panelhelper',1,'km-panel-right','{"views":["panel"],"layout":"_:default","moduleclass_sfx":"","cache":"0"}',1);
		self::KMinstallModule('mod_km_countries','mod_km_countries',1,'km-list-left','{"views":["countries"],"layout":"_:default","moduleclass_sfx":"","cache":"0"}',1);
		self::KMinstallModule('mod_km_allsettings_groups','mod_km_allsettings_groups',1,'km-list-left','{"views":["allsettings"],"layout":"_:default","moduleclass_sfx":"","cache":"0"}',1);
		self::KMinstallModule('mod_km_reports','mod_km_reports',1,'km-list-left','{"views":["reports"],"layout":"_:default","moduleclass_sfx":"","cache":"0"}',1);
		self::KMinstallModule('mod_km_search','mod_km_search',1,'km-list-left','{"views":["orders","catalog","users"],"layout":"_:default","moduleclass_sfx":"","cache":"0"}',1);
		self::KMinstallModule('mod_km_categories','mod_km_categories',1,'km-list-left','{"views":["catalog","properties"],"layout":"_:default","moduleclass_sfx":"","cache":"0"}',1);
		self::KMinstallModule('mod_km_manufacturers','mod_km_manufacturers',1,'km-list-left','{"views":["catalog"],"layout":"_:default","moduleclass_sfx":"","cache":"0"}',1);
		self::KMinstallModule('mod_km_shipping_methods','mod_km_shipping_methods',1,'km-list-left','{"views":["shippings"],"layout":"_:default","moduleclass_sfx":"","cache":"0"}',1);
		self::KMinstallModule('mod_km_payment_types','mod_km_payment_types',1,'km-list-left','{"views":["payments"],"moduleclass_sfx":"","cache":"0"}',1);
		self::KMinstallModule('mod_km_seo_types','mod_km_seo_types',1,'km-list-left','{"views":["seo"],"layout":"_:default","moduleclass_sfx":"","cache":"0"}}',1);
		self::KMinstallModule('mod_km_order_statuses','mod_km_order_statuses',1,'km-list-left','{"views":["orders"],"layout":"_:default","moduleclass_sfx":"","cache":"0"}',1);
		self::KMinstallModule('mod_km_usergroups','mod_km_usergroups',1,'km-list-left','{"views":["users"],"layout":"_:default","moduleclass_sfx":"","cache":"0"}',1);
		self::KMinstallModule('mod_km_userfields','mod_km_userfields',1,'km-list-left','{"views":["users"],"layout":"_:default","moduleclass_sfx":"","cache":"0"}',1);
		self::KMinstallModule('mod_km_commentrates','mod_km_commentrates',1,'km-list-left','{"views":["comments"],"layout":"_:default","moduleclass_sfx":"","cache":"0"}',1);
		self::KMinstallModule('mod_km_currencies_rates','mod_km_currencies_rates',1,'km-list-left','{"views":["currencies"],"layout":"_:default","moduleclass_sfx":"","cache":"0"}',1);
		self::KMinstallModule('mod_km_discount_types','mod_km_discount_types',1,'km-list-left','{"views":["discounts"],"layout":"_:default","moduleclass_sfx":"","cache":"0"}',1);
		self::KMinstallModule('mod_km_exportimport_types','mod_km_exportimport_types',1,'km-list-left','{"views":["exportimport"],"layout":"_:default","moduleclass_sfx":"","cache":"0"}',1);
		self::KMinstallModule('mod_km_path','mod_km_path',1,'km-top-left','{"views":["*"],"layout":"_:default","moduleclass_sfx":"","cache":"0"}',1);

		JFolder::create(JPATH_ROOT.DS.'plugins'.DS.'kmdiscount');
		JFolder::create(JPATH_ROOT.DS.'plugins'.DS.'kmdiscountactions');
		JFolder::create(JPATH_ROOT.DS.'plugins'.DS.'kmpayment');
		JFolder::create(JPATH_ROOT.DS.'plugins'.DS.'kmdiscount');
		JFolder::create(JPATH_ROOT.DS.'plugins'.DS.'kmshipping');
		self::KMinstallPlugin('countdown','kmdiscount','KSM_DISCOUNT_COUNTDOWN',1,'{}');
		self::KMinstallPlugin('coupons','kmdiscount','KSM_DISCOUNT_COUPONS',1,'{}');
		self::KMinstallPlugin('cumulative','kmdiscount','KSM_DISCOUNT_CUMULATIVE',1,'{}');
		self::KMinstallPlugin('fixed','kmdiscount','KSM_DISCOUNT_FIXED',1,'{}');
		self::KMinstallPlugin('onorder','kmdiscount','KSM_DISCOUNT_ONORDER',1,'{}');
		self::KMinstallPlugin('sitevisits','kmdiscountactions','KSM_DISCOUNTACTIONS_SITEVISITS',1,'{}');
		self::KMinstallPlugin('time','kmdiscountactions','KSM_DISCOUNTACTIONS_TIME',1,'{}');
		self::KMinstallPlugin('viewpages','kmdiscountactions','KSM_DISCOUNTACTIONS_VIEWPAGES',1,'{}');
		self::KMinstallPlugin('viewproducts','kmdiscountactions','KSM_DISCOUNTACTIONS_VIEWPRODUCTS',1,'{}');
		self::KMinstallPlugin('discountdisplay','kmplugins','KSM_PLUGINS_DISCOUNTDISPLAY',1,'{}');
		self::KMinstallPlugin('dobeforecache','kmplugins','KSM_PLUGINS_DOBEFORECACHE',2,'{}');
		self::KMinstallPlugin('couriermoscow','kmshipping','KSM_SHIPPING_COURIERMOSCOW',1,'{}');
		self::KMinstallPlugin('fixedregions','kmshipping','KSM_SHIPPING_FIXEDREGIONS',1,'{}');
		self::KMinstallPlugin('courier','kmpayment','KSM_PAYMENT_COURIER',1,'{}');
		self::KMinstallPlugin('receipt','kmpayment','KSM_PAYMENT_RECEIPT',1,'{}');
		self::KMinstallPlugin('robokassa','kmpayment','KSM_PAYMENT_ROBOKASSA',1,'{}');
		
		$db=JFactory::getDBO();
		$query=$db->getQuery(true);
		$params='{"shop_name":"","shop_email":"","shop_phone":"","modules_styles":"1","watermark":"0","watermark_image":"","watermark_type":"0","watermark_valign":"middle","watermark_halign":"center","displace":"1","valign":"middle","halign":"center","background_type":"color","background_file":"","background_color":"ffffff","show_products_from_subcategories":"1","show_out_stock":"1","order_process":"0","show_comment_form":"1","site_product_limit":"15","site_use_pagination":"1","parent_products_template":"list","include_jquery":"1","include_css":"1","only_auth_buy":"0","use_stock":"0","catalog_mode":"0","path_separator":" - ","thumb_width":"200","thumb_height":"200","middle_width":"350","middle_height":"350","mini_thumb_width":"110","mini_thumb_height":"110","count_symbol":"400","printforms_companyname":"","printforms_companyaddress":"","printforms_companyphone":"","printforms_nds":"","printforms_ceo_name":"","printforms_buh_name":"","printforms_bank_account_number":"","printforms_inn":"","printforms_kpp":"","printforms_bankname":"","printforms_bank_kor_number":"","printforms_bik":"","printforms_ip_name":"","printforms_ip_registration":"","printforms_company_logo":"","printforms_congritulation_message_template":"<p>\u0412\u0430\u0448\u0435\u043c\u0443 \u0437\u0430\u043a\u0430\u0437\u0443 \u043f\u0440\u0438\u0441\u0432\u043e\u0435\u043d \u043d\u043e\u043c\u0435\u0440 %s<\/p><p>\u041d\u0430\u0448\u0438 \u043c\u0435\u043d\u0435\u0434\u0436\u0435\u0440\u044b \u0441\u0432\u044f\u0436\u0443\u0442\u0441\u044f \u0441 \u0432\u0430\u043c\u0438 \u0432 \u0442\u0435\u0447\u0435\u043d\u0438\u0438 2 \u0447\u0430\u0441\u043e\u0432.<\/p><p>\u0422\u0430\u043a \u0436\u0435 \u0432\u044b \u043c\u043e\u0436\u0435\u0442\u0435 \u0443\u0437\u043d\u0430\u0442\u044c \u043e \u0441\u0442\u0430\u0442\u0443\u0441\u0435 \u0437\u0430\u043a\u0430\u0437\u0430 \u043f\u043e \u0442\u0435\u043b\u0435\u0444\u043e\u043d\u0443: 8 800 2000 600<\/p>"}';
		$query->select('*')->from('#__extensions')->where('name="ksenmart"');
		$db->setQuery($query);
		$extension=$db->loadObject();
		$table = JTable::getInstance ('extension');
		$table->load($extension->extension_id);
		$table->params=$params;
		$table->store();
	
		JFolder::delete($path);
		$parent->getParent()->setRedirectURL('index.php?option=com_ksenmart');
	}

	function uninstall($parent) {
		jimport('joomla.installer.helper');
		if(file_exists(JPATH_ROOT .DS. 'libraries'.DS.'joomla'.DS.'form'.DS.'fields'.DS.'kmcategories.php') && !JFile::delete(JPATH_ROOT .DS. 'libraries'.DS.'joomla'.DS.'form'.DS.'fields'.DS.'kmcategories.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt delete file');
		}
		if(file_exists(JPATH_ROOT .DS. 'libraries'.DS.'joomla'.DS.'form'.DS.'fields'.DS.'kmadminviews.php') && !JFile::delete(JPATH_ROOT .DS. 'libraries'.DS.'joomla'.DS.'form'.DS.'fields'.DS.'kmadminviews.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt delete file');
		}		
		if(file_exists(JPATH_ROOT .DS. 'libraries'.DS.'joomla'.DS.'html'.DS.'html'.DS.'kmcategories.php') && !JFile::delete(JPATH_ROOT .DS. 'libraries'.DS.'joomla'.DS.'html'.DS.'html'.DS.'kmcategories.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt delete file');
		}	
		if(file_exists(JPATH_ROOT .DS. 'administrator'.DS.'templates'.DS.'system'.DS.'ksenmart.php') && !JFile::delete(JPATH_ROOT .DS. 'administrator'.DS.'templates'.DS.'system'.DS.'ksenmart.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt delete file');
		}	
		if(file_exists(JPATH_ROOT .DS. 'administrator'.DS.'templates'.DS.'system'.DS.'ksenmart-full.php') && !JFile::delete(JPATH_ROOT .DS. 'administrator'.DS.'templates'.DS.'system'.DS.'ksenmart-full.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt delete file');
		}		
		if(file_exists(JPATH_ROOT .DS.'templates'.DS.'system'.DS.'ksenmart.php') && !JFile::delete(JPATH_ROOT .DS.'templates'.DS.'system'.DS.'ksenmart.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt delete file');
		}	
		if(file_exists(JPATH_ROOT .DS.'modules'.DS.'mod_login'.DS.'tmpl'.DS.'ksenmart.php') && !JFile::delete(JPATH_ROOT .DS.'modules'.DS.'mod_login'.DS.'tmpl'.DS.'ksenmart.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt delete file');
		}	
		if(file_exists(JPATH_ROOT .DS.'modules'.DS.'mod_banners'.DS.'tmpl'.DS.'ksenmart.php') && !JFile::delete(JPATH_ROOT .DS.'modules'.DS.'mod_banners'.DS.'tmpl'.DS.'ksenmart.php')){
			$app = JFactory::getApplication();
			$app -> enqueueMessage('Couldnt delete file');
		}	
		if(file_exists(JPATH_ROOT.DS.'images'.DS.'ksenmart'))
			JFolder::delete(JPATH_ROOT.DS.'images'.DS.'ksenmart');
		if(file_exists(JPATH_ROOT.DS.'media'.DS.'ksenmart'))
			JFolder::delete(JPATH_ROOT.DS.'media'.DS.'ksenmart');
	}

	function KMinstallModule($title,$module,$ordering,$position='position-4',$params='{}',$client_id=0,$published=1) {
		$path = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_ksenmart'.DS.'install';
		$db=JFactory::getDBO();
		$access=1;
		
		$values=array($db->quote($title),$db->quote($module),$ordering,$db->quote($position),$client_id,$published,$access,$db->quote($params));
		$query=$db->getQuery(true);
		$query->insert('#__modules')->columns('title,module,ordering,position,client_id,published,access,params')
		->values(implode(',', $values));				
		$db->setQuery ($query);
		if (!$db->query ()) {
			$app = JFactory::getApplication ();
			$app->enqueueMessage ($db->getErrorMsg ());
		}
		$lastUsedId = $db->insertid();

		$query=$db->getQuery(true);
		$query->select('moduleid')->from('#__modules_menu')->where('moduleid='.$lastUsedId);
		$db->setQuery ($query);
		$moduleid = $db->loadResult ();

		if (empty($moduleid)) {
			$query=$db->getQuery(true);
			$query->insert('#__modules_menu')->columns('moduleid')->values($lastUsedId);		
			$db->setQuery ($query);
			$db->query ();
		}

		if (version_compare (JVERSION, '1.6.0', 'ge')) {

			$query=$db->getQuery(true);
			$query->select('extension_id')->from('#__extensions')->where('element='.$db->quote($module))->where('client_id='.$client_id);
			$db->setQuery ($query);
			$ext_id = $db->loadResult ();

			if (empty($ext_id)) {
				$params='{}';			
				if ($client_id==0)
				{
					$src = JPATH_ROOT.DS.'modules'.DS.$module;
					JFolder::copy($path  .DS.'modules'.DS.$module, JPATH_ROOT.DS.'modules'.DS.$module, null, 1);
					copy($path  .DS.'modules'.DS.'language'.DS.'ru-RU.'.$module.'.ini',JPATH_ROOT.DS.'language'.DS.'ru-RU'.DS.'ru-RU.'.$module.'.ini');
					copy($path  .DS.'modules'.DS.'language'.DS.'ru-RU.'.$module.'.sys.ini',JPATH_ROOT.DS.'language'.DS.'ru-RU'.DS.'ru-RU.'.$module.'.sys.ini');
				}
				elseif ($client_id==1)	
				{
					$src = JPATH_ROOT.DS.'administrator'.DS.'modules'.DS.$module;
					JFolder::copy($path  .DS.'administrator-modules'.DS.$module, JPATH_ROOT.DS.'administrator'.DS.'modules'.DS.$module, null, 1);
					copy($path  .DS.'administrator-modules'.DS.'language'.DS.'ru-RU.'.$module.'.ini',JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'ru-RU'.DS.'ru-RU.'.$module.'.ini');
					copy($path  .DS.'administrator-modules'.DS.'language'.DS.'ru-RU.'.$module.'.sys.ini',JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'ru-RU'.DS.'ru-RU.'.$module.'.sys.ini');
				}			
				if (version_compare (JVERSION, '1.6.0', 'ge')) {
					$manifest_cache = json_encode (JApplicationHelper::parseXMLInstallFile ($src . DS . $module . '.xml'));
				}
				$values=array($db->quote($title),$db->quote('module'),$db->quote($module),$db->quote(''),$client_id,1,$access,0,$db->quote($manifest_cache),$db->quote($params),$ordering);
				$query=$db->getQuery(true);
				$query->insert('#__extensions')->columns('name,type,element,folder,client_id,enabled,access,protected,manifest_cache,params,ordering')
				->values(implode(',', $values));				
				$db->setQuery ($query);
				if (!$db->query ()) {
					$app = JFactory::getApplication ();
					$app->enqueueMessage ($db->getErrorMsg ());
				}																
			}
		}
	}
	
	function KMinstallPlugin($plugin,$folder,$title,$ordering,$params) {
		$path = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_ksenmart'.DS.'install';
		$db=JFactory::getDBO();
		$access=1;
		$src = JPATH_ROOT . DS . 'plugins' . DS . $folder . DS . $plugin;
		if (version_compare (JVERSION, '1.6.0', 'ge')) {
			$query=$db->getQuery(true);
			$query->select('extension_id')->from('#__extensions')->where('element='.$db->quote($plugin));
			$db->setQuery ($query);
			$ext_id = $db->loadResult ();

			if (empty($ext_id)) {
				JFolder::copy($path  .DS.'plugins'.DS.$plugin, JPATH_ROOT.DS.'plugins'.DS.$folder.DS.$plugin, null, 1);
				copy($path.DS.'plugins'.DS.'language'.DS.'ru-RU.plg_'.$folder.'_'.$plugin.'.sys.ini',JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'ru-RU'.DS.'ru-RU.plg_'.$folder.'_'.$plugin.'.sys.ini');
				if (version_compare (JVERSION, '1.6.0', 'ge')) {
					$manifest_cache = json_encode (JApplicationHelper::parseXMLInstallFile ($src . DS . $plugin . '.xml'));
				}
				$values=array($db->quote($title),$db->quote('plugin'),$db->quote($plugin),$db->quote($folder),0,1,$access,0,$db->quote($manifest_cache),$db->quote($params),$ordering);
				$query=$db->getQuery(true);
				$query->insert('#__extensions')->columns('name,type,element,folder,client_id,enabled,access,protected,manifest_cache,params,ordering')
				->values(implode(',', $values));				
				$db->setQuery ($query);
				if (!$db->query ()) {
					$app = JFactory::getApplication ();
					$app->enqueueMessage ($db->getErrorMsg ());
				}				
			}
		}
	}
	
}	