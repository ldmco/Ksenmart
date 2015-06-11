<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

use Joomla\Registry\Registry;

if (!class_exists('KMPlugin')) {
	require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}

class plgKMPluginsModules extends KMPlugin {

	public $pages = array(1 => 'catalog',2 => 'product',3 => 'cart',4 => 'profile');
	
	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
	}
	
	public static function __callStatic($name,array $func_params)
    {
		$params = self::getParams();
		$layouts  = $params->get('layouts', array());		
		
		foreach($layouts as $layout){
			$func = 'on'.$layout->event.'DisplayKSM'.$layout->layout;
			if ($name == $func){
				$view = $func_params[0];
				$tpl = &$func_params[1];
				$html = &$func_params[2];
				
				$html .= KSSystem::loadModules($layout->position);
			}
		}
       
		return;
    }

	function getParams(){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('params')
			->from('#__extensions')
			->where('enabled = 1')
			->where('type =' . $db->quote('plugin'))
			->where('folder =' . $db->quote('kmplugins'))
			->where('element =' . $db->quote('modules'));
		$db_params = $db->setQuery($query)->loadResult();
		
		$params = new Registry;
		if (!empty($db_params))
		{
			$params->loadString($db_params);
		}	

		return $params;
	}
	
	function onBeforeStartComponent(){
		$params = self::getParams();
		$layouts  = $params->get('layouts', array());	
		
		$dispatcher = JDispatcher::getInstance();
		foreach($layouts as $layout){
			$func = 'on'.$layout->event.'DisplayKSM'.$layout->layout;
			$dispatcher->register($func, 'plgKMPluginsModules::'.$func);
		}
		
		return;
	}

	public function onAfterDispatch(){
		if (JFactory::getApplication()->isAdmin())
		{
			return true;
		}
		
		$input = JFactory::getApplication()->input;
		$extension = $input->get('option', '', 'cmd');
		$view = $input->get('view', '', 'cmd');
		$categories = $input->get('categories', array(), 'array');
		$category_id = count($categories) == 1 ? array_shift($categories) : null;

		if ($extension !== 'com_ksenmart')
		{
			return true;
		}

		if (JFactory::getDocument()->getType() !== 'html' || $input->get('tmpl', '', 'cmd') === 'ksenmart')
		{
			return true;
		}

		$doc      = JFactory::getDocument();
		$renderer = $doc->loadRenderer('module');
		$modules  = $this->params->get('modules', new stdClass);
		
		foreach($modules as $position => $mods){
			$attribs  = array(
				'name' => $position
			);
			$buf = $doc->getBuffer('modules', $position, $attribs);
			foreach(JModuleHelper::getModules($position) as $mod)
			{
				foreach($mods as $mod_id => $mod_params){
					$registry   = new JRegistry;
					$mod_params = $registry->loadObject($mod_params);
					$categories = $mod_params->get('categories', array());
					$pages      = $mod_params->get('pages', array());

					if ($mod->id == $mod_id){
						if ($view == 'catalog' && !empty($category_id)){
							if (in_array(-1, $categories) || (!in_array($category_id, $categories) && !in_array(0, $categories))){
								$moduleHtml = $renderer->render($mod, $attribs, null);
								$buf = str_replace($moduleHtml, '', $buf);
							}
						} else {
							$page_id = 0;
							foreach($this->pages as $key => $page)
								if ($page == $view)
									$page_id = $key;
							if (in_array(-1, $pages) || (!in_array($page_id, $pages) && !in_array(0, $pages))){
								$moduleHtml = $renderer->render($mod, $attribs, null);
								$buf = str_replace($moduleHtml, '', $buf);
							}
						}
					}
				}
			}
			$doc->setBuffer($buf, 'modules', $position);
		}
		return true;
	}
	
}