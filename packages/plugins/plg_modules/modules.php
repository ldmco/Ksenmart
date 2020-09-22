<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

if (!class_exists('KMPlugin'))
{
	require(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}

class plgKMPluginsModules extends KMPlugin
{

	public $pages = array(1 => 'catalog', 2 => 'product', 3 => 'cart', 4 => 'profile');

	function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}

	public static function __callStatic($name, array $func_params)
	{
		$params  = self::getParams();
		$layouts = $params->get('layouts', array());

		foreach ($layouts as $layout)
		{
			$func = 'on' . $layout->event . 'DisplayKSM' . $layout->layout;
			if ($name == $func)
			{
				$view = $func_params[0];
				$tpl  = &$func_params[1];
				$html = &$func_params[2];

				$html .= KSSystem::loadModules($layout->position);
			}
		}

		return;
	}

	function getParams()
	{
		return $this->params;
	}

	function onBeforeStartComponent()
	{
		$layouts = $this->params->get('layouts', array());

		$dispatcher = JEventDispatcher::getInstance();
		foreach ($layouts as $layout)
		{
			$func = 'on' . $layout->event . 'DisplayKSM' . $layout->layout;
			$dispatcher->register($func, 'plgKMPluginsModules::' . $func);
		}

		return;
	}

	public function onAfterModuleList(&$allmodules = array())
	{
		if (JFactory::getApplication()->isAdmin())
		{
			return true;
		}
		$input       = JFactory::getApplication()->input;
		$extension   = $input->get('option', '', 'cmd');
		$id          = $input->get('id', '', 'INT');
		$view        = $input->get('view', '', 'cmd');
		$categories  = $input->get('categories', array(), 'array');
		$category_id = count($categories) == 1 ? array_shift($categories) : null;

		if ($extension !== 'com_ksenmart')
		{
			return true;
		}

		if (JFactory::getDocument()->getType() !== 'html' || $input->get('tmpl', '', 'cmd') === 'ksenmart')
		{
			return true;
		}

		$modules = $this->params->get('modules', new stdClass);

		if ($view == 'product' && !empty($id))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('id')->from('#__ksenmart_products_categories')->where('product_id=' . (int) $id);
			$db->setQuery($query);
			$pcategories = $db->loadColumn();
		}
		foreach ($modules as $position => $mods)
		{
			foreach ($allmodules as $key_module => $allmodule)
			{
				foreach ($mods as $mod_id => $mod_params)
				{
					if ($allmodule->id != $mod_id) continue;

					$registry   = new JRegistry;
					$mod_params = $registry->loadObject($mod_params);
					$categories = $mod_params->get('categories', array());
					$pages      = $mod_params->get('pages', array());
					$flag       = true;

					if ($view == 'product' && !empty($id) && (count($categories) && !in_array(0, $categories)))
					{
						$flag = false;

						if (count($pcategories))
						{
							foreach ($pcategories as $category)
							{
								if (in_array($category, $categories))
								{
									$flag = true;
								}
							}
						}
					}

					if ($view == 'catalog' && !empty($category_id))
					{
						if (in_array(-1, $categories) || (!in_array($category_id, $categories) && !in_array(0, $categories)))
						{
							unset($allmodules[$key_module]);
						}
					}
					else
					{
						if ($view == 'catalog' && empty($category_id) && in_array(-2, $categories)) {
							unset($allmodules[$key_module]);
							continue;
						}
						$page_id = 0;
						foreach ($this->pages as $key => $page)
							if ($page == $view)
								$page_id = $key;
						if ((in_array(-1, $pages) || (!in_array($page_id, $pages) && !in_array(0, $pages))) || !$flag)
						{
							unset($allmodules[$key_module]);
						}
					}
				}
			}
		}

		return true;
	}

}