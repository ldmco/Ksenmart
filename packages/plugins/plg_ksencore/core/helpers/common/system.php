<?php
/**
 * @copyright   Copyright (C) 2016. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

define('YA_MARKET_API_PATCH', 'https://api.partner.market.yandex.ru/v2');

/**
 * KSSystem
 *
 * @package
 * @version 2.1.2
 * @since   version 2.0.0
 * @access  public
 */
class KSSystem
{

	private static $_user = [];
	private static $_load_plugins = false;
	private static $_load_modules = [];
	private static $_Itemid = [];
	private static $_template_controller = null;
	private static $ext_name = null;
	private static $ext_name_com = null;
	private static $_tables_row = [];
	private static $_seo_config = [];

	/**
	 * KSSystem::loadPlugins()
	 * @since version 2.0.0
	 */
	public static function loadPlugins()
	{
		if (self::$_load_plugins) return;
		self::$_load_plugins = true;

		$jinput = JFactory::getApplication()->input;
		$plugin = $jinput->get('plugin', null, 'string');

		$plugins = array(
			'kmdiscount',
			'kmdiscountactions',
			'kmpayment',
			'kmplugins',
			'kmshipping'
		);

		if ($jinput->get('view', null, 'string') == 'exportimport' || $jinput->get('export', 0, 'string') !== 0 || ($jinput->get('view', null, 'string') == 'catalog' && !empty($plugin)))
		{
			$plugins[] = 'kmexportimport';
		}

		foreach ($plugins as $plugin)
		{
			JPluginHelper::importPlugin($plugin);
		}
	}

	/**
	 * KSSystem::getKSVersion()
	 *
	 * @since version 2.0.0
	 * @return null
	 */
	public static function getKSVersion()
	{

		if (empty(self::$ext_name_com))
		{
			self::setGlobalVar('ext_name_com');
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('manifest_cache')->from('#__extensions')->where('element=' . $db->quote(self::$ext_name_com));
		$db->setQuery($query, 0, 1);
		$km_extension = $db->loadObject();

		if (!empty($km_extension))
		{
			$params = json_decode($km_extension->manifest_cache);

			return $params->version;
		}

		return null;
	}

	/**
	 * @param string $component
	 * @param        $type
	 *
	 * @return null
	 *
	 * @since version 4.0.0
	 */
	public static function getSeoConfig($component = 'com_ksenmart')
	{
		if (!isset(self::$_seo_config[$component]))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*')->from('#__ksen_seo_config')->where('extension=' . $db->quote($component));
			$db->setQuery($query);
			$cache                         = JFactory::getCache('com_ksenmart.seoconfig', 'callback');
			self::$_seo_config[$component] = $cache->get(array($db, 'loadObjectList'));
		}
		if (empty(self::$_seo_config[$component])) return null;

		return self::$_seo_config[$component];
	}


	/**
	 * KSSystem::loadModules()
	 *
	 * @param string $position  Имя позиции
	 * @param array $params     Параметры позиции
	 *
	 * @return string           Подгружает модули из указанной позиции
	 *
	 * @since version 2.0.0
	 */
	public static function loadModules($position, $params = array())
	{
		if (!isset(self::$_load_modules[$position]))
		{
			$document = JFactory::getDocument();
			if ($document->getType() == 'raw') $document->setType('html');
			$renderer = $document->loadRenderer('modules');

			self::$_load_modules[$position] = $renderer->render($position, $params, null);
		}

		return self::$_load_modules[$position];
	}


	/**
	 * KSSystem::loadModule()
	 *
	 * @param       $name
	 * @param array $params
	 *
	 * @return string
	 *
	 * @since version 2.0.0
	 */
	public static function loadModule($name, $params = array())
	{
		$document = JFactory::getDocument();
		$module   = JModuleHelper::getModule($name);
		$renderer = $document->loadRenderer('module');


		return $renderer->render($module, $params, null);
	}


	/**
	 * KSSystem::loadModuleFiles()
	 *
	 * @param $module_name
	 *
	 * @since version 2.0.0
	 */
	public static function loadModuleFiles($module_name)
	{
		$document = JFactory::getDocument();
		$jinput   = JFactory::getApplication()->input;
		$view     = $jinput->get('view', 'panel', 'string');
		$layout   = $jinput->get('layout', null, 'string');

		if (file_exists(JPATH_ADMINISTRATOR . '/modules/' . $module_name . '/css/' . $view . '.css'))
		{
			$document->addStyleSheet(JUri::base() . 'modules/' . $module_name . '/css/' . $view . '.css');
		}

		if (file_exists(JPATH_ADMINISTRATOR . '/modules/' . $module_name . '/css/default.css'))
		{
			$document->addStyleSheet(JUri::base() . 'modules/' . $module_name . '/css/default.css');
		}

		if (!empty($layout) && file_exists(JPATH_ADMINISTRATOR . '/modules/' . $module_name . '/js/' . $view . '-' . $layout . '.js'))
		{
			$document->addScript(JUri::base() . 'modules/' . $module_name . '/js/' . $view . '-' . $layout . '.js');
		}
		elseif (file_exists(JPATH_ADMINISTRATOR . '/modules/' . $module_name . '/js/' . $view . '.js'))
		{
			$document->addScript(JUri::base() . 'modules/' . $module_name . '/js/' . $view . '.js');
		}

		if (file_exists(JPATH_ADMINISTRATOR . '/modules/' . $module_name . '/js/default.js'))
		{
			$document->addScript(JUri::base() . 'modules/' . $module_name . '/js/default.js');
		}
	}

	/**
	 * KSSystem::getModuleLayout()
	 *
	 * @param $module_name
	 *
	 * @return mixed|string
	 *
	 * @since version 2.0.0
	 */
	public static function getModuleLayout($module_name)
	{
		$jinput = JFactory::getApplication()->input;
		$view   = $jinput->get('view', 'panel', 'string');
		$layout = $jinput->get('layout', null, 'string');

		if (!empty($layout) && file_exists(JPATH_ADMINISTRATOR . '/modules/' . $module_name . '/tmpl/' . $view . '-' . $layout . '.php'))
		{


			return $view . '-' . $layout;
		}
		elseif (file_exists(JPATH_ADMINISTRATOR . '/modules/' . $module_name . '/tmpl/' . $view . '.php'))
		{


			return $view;
		}
		else
		{


			return 'default';
		}
	}


	/**
	 * KSSystem::wrapFormField()
	 *
	 * @param null $wrap
	 * @param      $element
	 * @param      $html
	 *
	 * @return string
	 *
	 * @since version 2.0.0
	 */
	public static function wrapFormField($wrap = null, $element, $html)
	{
		if (empty($wrap))
		{


			return $html;
		}
		if (!file_exists(JPATH_COMPONENT_ADMINISTRATOR . '/models/wraps/' . $wrap . '.php'))
		{


			return $html;
		}
		ob_start();
		require JPATH_COMPONENT_ADMINISTRATOR . '/models/wraps/' . $wrap . '.php';
		$html = ob_get_contents();
		ob_end_clean();


		return $html;
	}

	/**
	 * @param string $type
	 * @param string $name
	 *
	 * @return bool
	 *
	 * @since version 4.0.0
	 */
	public static function checkExtension($type = '', $name = '')
	{
		if (empty($type) || empty($name)) return false;

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__ksenmart_info')
			->where('package=' . $db->q('ksenmart'));
		$db->setQuery($query);
		$info = $db->loadObject();
		if (empty($info) || empty($info->key))
		{
			if ($curl = curl_init())
			{
				curl_setopt($curl, CURLOPT_URL, 'http://billing.ksenmart.ru/api/ping/?d=' . $_SERVER['SERVER_NAME']);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
				$response = curl_exec($curl);
				curl_close($curl);
				$response = json_decode($response);
				if (!empty($response->status) && $response->status->text = 'Ok')
				{
					$values               = array();
					$values['package']    = $db->q('ksenmart');
					$values['key']        = $db->q($response->shop->key);
					$values['created']    = (int) $response->shop->created_at;
					$values['status']     = 0;
					$values['last_check'] = 0;
					$info                 = new stdClass();
					$info->status         = 0;
				}

				$query = $db->getQuery(true);
				$query->insert('#__ksenmart_info')->columns($db->qn(array_keys($values)))->values(implode(',', $values));
				$db->setQuery($query);
				$db->execute();
			}
		}
		$cur_time = time();
		if (!empty($info->token) && $cur_time > strtotime($info->last_check) + 5 * 60)
		{
			if ($curl = curl_init())
			{
				curl_setopt($curl, CURLOPT_URL, 'http://billing.ksenmart.ru/api/check/?t=' . $info->token . '&k=' . $info->key);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
				$response = curl_exec($curl);
				curl_close($curl);
				$response = json_decode($response);
			}
			else
			{
				$info->status = 0;
			}
		}
		else
		{
			$info->status = 0;
		}

		/*$query = $db->getQuery(true);
		$query->update('#__ksenmart_info')
			->set('last_check=NOW()')
			->set('status=1')
			->where('id=1');
		$db->setQuery($query);
		$db->execute();*/

		if (!$info->status)
		{
			$query = $db->getQuery(true);
			$query->select('disabled')->from('#__ksen_billing_data')->where('type=' . $db->q($type))->where('extension=' . $db->q($name));
			$db->setQuery($query);
			$result = $db->loadResult();
			if ($result === null)
			{
				$values = array(
					'extension' => $db->q($name),
					'type'      => $db->q($type),
					'disabled'  => 0
				);
				$query  = $db->getQuery(true);
				$query->insert('#__ksen_billing_data')->columns(implode(',', array_keys($values)))->values(implode(',', $values));
				$db->setQuery($query);
				$db->execute();

				$result = false;
			}
		}
		else
		{
			return false;
		}

		return (bool) $result;
	}

	/**
	 * @param bool $admin
	 *
	 *
	 * @since version 2.0.0
	 */
	public static function loadJSLanguage($admin = true)
	{
		if (empty(self::$ext_name_com))
		{
			self::setGlobalVar('ext_name_com');
		}

		$path = $admin ? JPATH_ADMINISTRATOR . DS . 'components' . DS . self::$ext_name_com : JPATH_ROOT . DS . 'components' . DS . self::$ext_name_com;
		$lang = JFactory::getLanguage();
		$lang->load(self::$ext_name_com . '.js', $path, null, false, false);

		$lang     = $lang->getTag();
		$filename = $path . DS . 'language' . DS . $lang . DS . $lang . '.' . self::$ext_name_com . '.js.ini';
		$version  = phpversion();

		$php_errormsg = null;
		$track_errors = ini_get('track_errors');
		ini_set('track_errors', true);

		if ($version >= '5.3.1')
		{
			$contents = file_get_contents($filename);
			$contents = str_replace('_QQ_', '"\""', $contents);
			$strings  = @parse_ini_string($contents);
		}
		else
		{
			$strings = @parse_ini_file($filename);

			if ($version == '5.3.0' && is_array($strings))
			{


				foreach ($strings as $key => $string)
				{
					$strings[$key] = str_replace('_QQ_', '"', $string);
				}
			}
		}

		ini_set('track_errors', $track_errors);

		if (!is_array($strings))
		{
			$strings = array();
		}


		foreach ($strings as $key => $string)
		{
			JText::script($key);
		}
	}

	/**
	 * @param null $id
	 * @param null $table
	 *
	 * @return bool|object
	 *
	 * @since version 2.0.0
	 */
	public static function loadDbItem($id = null, $table = null)
	{
		if (!$table)
		{
			return false;
		}
		if (empty(self::$ext_name))
		{
			self::setGlobalVar('ext_name');
		}
		if (empty(self::$ext_name_com))
		{
			self::setGlobalVar('ext_name_com');
		}
		JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . self::$ext_name_com . DS . 'tables');
		$table = JTable::getInstance($table, self::$ext_name . 'Table', array());

		if ($id > 0)
		{
			$return = $table->load($id);
			if ($return === false && $table->getError())
			{
				return false;
			}
		}

		$properties = $table->getProperties(1);
		$item       = \Joomla\Utilities\ArrayHelper::toObject($properties, 'JObject');

		return $item;
	}

	/**
	 * @param $address_struct
	 *
	 * @return string
	 *
	 * @since version 2.0.0
	 */
	public static function formatAddress($address_struct)
	{
		$address = '';
		if (!empty($address_struct))
		{

			if (!empty($address_struct->city))
			{
				$address .= 'г. ' . $address_struct->city;
				if (!empty($address_struct->street))
				{
					$address .= ', ';
				}
			}
			if (!empty($address_struct->street))
			{
				$address .= 'ул. ' . $address_struct->street;
				if (!empty($address_struct->house))
				{
					$address .= ', ';
				}
			}
			if (!empty($address_struct->house))
			{
				$address .= 'д. ' . $address_struct->house;
				if (!empty($address_struct->flat))
				{
					$address .= ', ';
				}
			}
			if (!empty($address_struct->flat))
			{
				$address .= 'кв. ' . $address_struct->flat;
			}


			return $address;
		}


		return $address;
	}

	/**
	 * @param int $cat_id
	 *
	 * @return mixed
	 *
	 * @since version
	 */
	public static function getShopItemid($cat_id = 0)
	{
		if (empty(self::$_Itemid))
		{
			$menu      = JFactory::getApplication()->getMenu();
			$menuitems = $menu->getItems(array('component'), array('com_ksenmart'));

			foreach ($menuitems as $menuitem)
			{
                if ($menuitem->query['view'] == 'catalog' && $menuitem->query['layout'] == 'catalog'
                    && empty($menuitem->query['new']) && empty($menuitem->query['promotion']))
                {
                    self::$_Itemid[0] = $menuitem->id;
                }

                if ($menuitem->query['view'] == 'catalog'
                    && $menuitem->query['layout'] == 'category'
                    && !empty($menuitem->query['categories'])
                    && empty($menuitem->query['properties']))
                {
                    self::$_Itemid[$menuitem->query['categories'][0]] = $menuitem->id;
                }
			}
		}
		if (empty(self::$_Itemid[$cat_id])) $cat_id = 0;

		return isset(self::$_Itemid[$cat_id]) ? self::$_Itemid[$cat_id] : 0;
	}

	/**
	 * @param $uid
	 *
	 * @return bool
	 *
	 * @since version 2.0.0
	 */
	public static function issetReview($uid)
	{
		if (!empty($uid))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('user_id');
			$query->from('#__ksenmart_comments');
			$query->where('user_id=' . $uid);
			$query->where("type='shop_review'");
			$db->setQuery($query);
			$result = $db->execute();

			if ($db->getNumRows($result) >= 1)
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * @param       $name
	 * @param null  $ext_name
	 * @param array $config
	 *
	 * @return bool|JModelLegacy
	 *
	 * @since version 2.0.0
	 */
	public static function getModel($name, $ext_name = null, $config = array())
	{

		if (empty(self::$ext_name))
		{
			self::setGlobalVar('ext_name');
		}
		if (empty(self::$ext_name_com))
		{
			self::setGlobalVar('ext_name_com');
		}
		if (!empty($ext_name))
		{
			$ext_name_com = 'com_' . $ext_name;
		}
		else
		{
			$ext_name     = self::$ext_name;
			$ext_name_com = self::$ext_name_com;
		}

		jimport('joomla.application.component.model');

		$modelFile      = JPATH_SITE . DS . 'components' . DS . $ext_name_com . DS . 'models' . DS . $name . '.php';
		$adminModelFile = JPATH_ADMINISTRATOR . DS . 'components' . DS . $ext_name_com . DS . 'models' . DS . $name . '.php';

		if (file_exists($modelFile))
		{
			require_once($modelFile);
		}
		elseif (file_exists($adminModelFile))
		{
			require_once($adminModelFile);
		}
		else
		{
			JModelLegacy::addIncludePath(JPATH_SITE . DS . 'components' . DS . $ext_name_com . DS . 'models');
		}
		//Get the right model prefix, e.g. UserModel for com_user
		$model_name = ucfirst($ext_name) . 'Model';
		$model      = JModelLegacy::getInstance($name, $model_name, $config);

		return $model;
	}

	/**
	 * @param $var_name
	 *
	 * @return bool
	 *
	 * @since version 2.0.0
	 */
	private static function setGlobalVar($var_name)
	{
		if (!empty($var_name))
		{
			global ${$var_name};
			self::${$var_name} = ${$var_name};

			return self::${$var_name};
		}

		return false;
	}

	/**
	 * @param       $name
	 * @param null  $ext_name
	 * @param array $config
	 *
	 * @return mixed
	 *
	 * @since version 2.0.0
	 */
	public static function getController($name, $ext_name = null, $config = array())
	{

		if (empty(self::$ext_name))
		{
			self::setGlobalVar('ext_name');
		}
		if (empty(self::$ext_name_com))
		{
			self::setGlobalVar('ext_name_com');
		}
		if (!empty($ext_name))
		{
			$ext_name_com = 'com_' . $ext_name;
			if (JFactory::getApplication()->isAdmin())
				$maincontrollerFile = JPATH_ADMINISTRATOR . DS . 'components' . DS . $ext_name_com . DS . 'controller.php';
			else
				$maincontrollerFile = JPATH_SITE . DS . 'components' . DS . $ext_name_com . DS . 'controller.php';

			if (file_exists($maincontrollerFile))
			{
				require_once($maincontrollerFile);
			}
		}
		else
		{
			$ext_name     = self::$ext_name;
			$ext_name_com = self::$ext_name_com;
		}

		jimport('joomla.application.component.controller');

		$controllerFile      = JPATH_SITE . DS . 'components' . DS . $ext_name_com . DS . 'controllers' . DS . $name . '.php';
		$adminControllerFile = JPATH_ADMINISTRATOR . DS . 'components' . DS . $ext_name_com . DS . 'controllers' . DS . $name . '.php';

		if (file_exists($controllerFile))
		{
			require_once($controllerFile);
		}
		elseif (file_exists($adminControllerFile))
		{
			require_once($adminControllerFile);
		}
		else
		{
			JControllerLegacy::addIncludePath(JPATH_SITE . DS . 'components' . DS . $ext_name_com . DS . 'controllers');
		}

		$controller_name = ucfirst($ext_name) . 'Controller';
		$controller_name = $controller_name . $name;

		$controller = new $controller_name();


		return $controller;
	}

	/**
	 * @param array $ids
	 * @param       $table
	 * @param array $fields
	 * @param bool  $published
	 * @param bool  $implode_keys
	 * @param bool  $single
	 *
	 * @return mixed|stdClass
	 *
	 * @since version 2.0.0
	 */
	public static function getTableByIds(array $ids, $table, array $fields, $published = true, $implode_keys = false, $single = false)
	{
		if (!empty($ids) && is_array($ids) && count($ids))
		{
			$keys = $implode_keys ? KSSystem::key_implode('_', $ids) : implode(', ', $ids);
			$key = implode('_', array($keys , $table, implode('_', $fields), $published, $implode_keys, $single));
			if (!empty(self::$_tables_row[$key]))
			{
				return self::$_tables_row[$key];
			}
			if (empty(self::$ext_name))
			{
				self::setGlobalVar('ext_name');
			}
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select($fields)->from($db->qn('#__' . self::$ext_name . '_' . $table, 't'));

			if ($implode_keys)
			{
				$query->where('(t.id IN (' . KSSystem::key_implode(', ', $ids) . '))');
			}
			else
			{
				$query->where('(t.id IN (' . implode(', ', $ids) . '))');
			}
			if ($published)
			{
				$query->where('t.published=1');
			}
			$db->setQuery($query);
			if ($single)
			{
				$object = $db->loadObject();
			}
			else
			{
				$object = $db->loadObjectList();
			}
			if (!empty($object))
			{
				self::$_tables_row[$key] = $object;

				return $object;
			}
		}

		return new stdClass;
	}

	/**
	 * @param $separator
	 * @param $array
	 *
	 * @return string
	 *
	 * @since version 2.0.0
	 */
	public static function key_implode($separator, $array)
	{
		$keys = array_keys($array);

		return implode($separator, $keys);
	}

	/**
	 * @param        $part
	 * @param string $type
	 *
	 * @return bool|mixed
	 *
	 * @since version 2.0.0
	 */
	public static function getSeoTitlesConfig($part, $type = 'title')
	{
		if (empty(self::$ext_name_com))
		{
			self::setGlobalVar('ext_name_com');
		}

		if (!empty($part))
		{
			$configs = self::getSeoConfig(self::$ext_name_com);
			foreach ($configs as $config)
			{
				if ($config->type == $type && $config->part == $part) return json_decode($config->config);
			}
		}

		return false;
	}

	/**
	 * @param $date
	 *
	 * @return string
	 *
	 * @since version 2.0.0
	 */
	public static function formatCommentDate($date)
	{
		$date = explode(' ', $date);
		$date = explode('-', $date[0]);
		$mon  = '';

		switch ($date[1])
		{
			case '01':
				$mon = 'января';
				break;
			case '02':
				$mon = 'февраля';
				break;
			case '03':
				$mon = 'марта';
				break;
			case '04':
				$mon = 'апреля';
				break;
			case '05':
				$mon = 'мая';
				break;
			case '06':
				$mon = 'июня';
				break;
			case '07':
				$mon = 'июля';
				break;
			case '08':
				$mon = 'августа';
				break;
			case '09':
				$mon = 'сентября';
				break;
			case '10':
				$mon = 'октября';
				break;
			case '11':
				$mon = 'ноября';
				break;
			case '12':
				$mon = 'декабря';
				break;
		}
		$str_date = $date[2] . ' ' . $mon . ' ' . $date[0];

		return $str_date;
	}

	/**
	 * @param array  $vars
	 * @param string $view
	 * @param string $layout
	 * @param string $tmpl
	 *
	 * @return string
	 *
	 * @since version 2.0.0
	 */
	public static function loadTemplate(array $vars, $viewName = 'catalog', $layout = 'default', $tmpl = 'item')
	{

		$option = JFactory::getApplication()->input->get('option', null, 'string');

		if (!is_object(self::$_template_controller))
		{
			if ($option != 'com_ksenmart')
			{
				self::$_template_controller = self::getController($viewName);
			}
			else
			{
				self::$_template_controller = JControllerLegacy::getInstance('KsenMartController' . ucfirst($viewName));
			}
		}
		$view           = self::$_template_controller->getView($viewName, 'html');
		$model          = self::$_template_controller->getModel($viewName);
		$current_layout = $view->getLayout();

		$view->setLayout($layout);
		if (empty($layout))
		{
			$view->setLayout($tmpl);
		}

		$view->setModel($model, true);

		foreach ($vars as $name => $var)
		{
			$view->{$name} = $var;
		}

		$view->addTemplatePath(JPATH_THEMES . '/' . JFactory::getApplication()->getTemplate() . '/html/' . self::$ext_name_com . '/' . $view->getName());
		$html = $view->loadTemplate($tmpl);
		$view->setLayout($current_layout);

		return $html;
	}

	/**
	 * @param $plugin_name
	 * @param $plugin_type
	 * @param $view
	 * @param $template_name
	 *
	 * @return string
	 *
	 * @since version 2.0.0
	 */
	public static function loadPluginTemplate($plugin_name, $plugin_type, $view, $template_name)
	{
		$html = '';

		$template_file  = $template_name . '.php';
		$template_paths = array(
			JPATH_THEMES . '/' . JFactory::getApplication()->getTemplate() . '/html/plg_' . $plugin_name . '/' . $template_file,
			JPATH_ROOT . '/plugins/' . $plugin_type . '/' . $plugin_name . '/tmpl/' . $template_file
		);

		foreach ($template_paths as $template_path)
		{
			if (file_exists($template_path))
			{
				ob_start();
				require $template_path;
				$html = ob_get_contents();
				ob_end_clean();

				break;
			}
		}

		return $html;
	}

	/**
	 * @param array $css
	 *
	 *
	 * @since version 2.0.0
	 */
	public static function addCSS(array $css)
	{
		$document = JFactory::getDocument();

		foreach ($css as $style)
		{
			$document->addStyleSheet(KSC_ADMIN_URL_CORE_ASSETS_CSS . $style . '.css');
		}
	}

	/**
	 * @param array $js
	 *
	 *
	 * @since version 2.0.0
	 */
	public static function addJS(array $js)
	{
		$document = JFactory::getDocument();


		foreach ($js as $script)
		{
			$document->addScript(KSC_ADMIN_URL_CORE_ASSETS_JS . $script . '.js');
		}
	}

	/**
	 * @param $path
	 *
	 * @return bool
	 *
	 * @since version 2.0.0
	 */
	public static function import($path)
	{
		if (!empty($path))
		{
			return JLoader::import($path, JPATH_ROOT . '/plugins/system/ksencore/core');
		}

		return false;
	}
}
