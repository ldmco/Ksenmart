<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class KSInstaller {
	
	public $installer = null;
	
	function __construct($installer) {
		$this->installer = $installer;
	}	
	
	public function installModule($title, $module, $position, $params, $page = 'all'){
		if (!self::checkModule($module, $position)){
			return false;
		}
		
		$db = JFactory::getDBO();
		$values = array(
			'title' => $db->quote($title),
			'module' => $db->quote($module),
			'ordering' => 1,
			'position' => $db->quote($position),
			'client_id' => 0,
			'published' => 1,
			'access' => 1,
			'params' => $db->quote($params)
		);
		$query=$db->getQuery(true);
		$query->insert('#__modules')->columns(implode(',', array_keys($values)))->values(implode(',', $values));				
		$db->setQuery($query);
		$db->query();
		$moduleid = $db->insertid();

		if ($page == 'main')
			$menuid = self::getMainItemid();
		elseif ($page == 'catalog')
			$menuid = self::getCatalogItemid();
		else 
			$menuid = 0;
		$values = array(
			'moduleid' => $moduleid,
			'menuid' => $menuid
		);
		$query=$db->getQuery(true);
		$query->insert('#__modules_menu')->columns(implode(',', array_keys($values)))->values(implode(',', $values));		
		$db->setQuery($query);
		$db->query();

		return true;
	}
	
	public function installPseudoHTMLModule($title, $module, $position, $page = 'all'){
		if (!self::checkModule($module, $position)){
			return false;
		}		
		if (!self::checkModule('mod_custom', $position)){
			return false;
		}	
		self::installHTMLModule($title, $position, $page);
		
		return true;
	}
	
	public function installHTMLModule($title, $position, $page = 'all'){
		if (!self::checkModule('mod_custom', $position)){
			return false;
		}
		$contentFile = $this->installer->getPath('extension_root') . '/install/mod_custom/' . $position . '.html';
		if (!$content = file_get_contents($contentFile)){
			return false;
		}
		
		$db = JFactory::getDBO();
		$params = '{}';
		$values = array(
			'title' => $db->quote($title),
			'module' => $db->quote('mod_custom'),
			'ordering' => 1,
			'position' => $db->quote($position),
			'content' => $db->quote($content),
			'client_id' => 0,
			'published' => 1,
			'access' => 1,
			'params' => $db->quote($params)
		);
		$query=$db->getQuery(true);
		$query->insert('#__modules')->columns(implode(',', array_keys($values)))->values(implode(',', $values));				
		$db->setQuery($query);
		$db->query();
		$moduleid = $db->insertid();

		if ($page == 'main')
			$menuid = self::getMainItemid();
		elseif ($page == 'catalog')
			$menuid = self::getCatalogItemid();
		else 
			$menuid = 0;
		$values = array(
			'moduleid' => $moduleid,
			'menuid' => $menuid
		);
		$query=$db->getQuery(true);
		$query->insert('#__modules_menu')->columns(implode(',', array_keys($values)))->values(implode(',', $values));		
		$db->setQuery($query);
		$db->query();

		return true;
	}	
	
	public function checkModule($module, $position){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('count(*)')->from('#__modules')->where('module = '.$db->quote($module))->where('position = '.$db->quote($position))->where('client_id = 0')->where('published != -2');
		$db->setQuery($query);
		$count = $db->loadResult();
		if ($count > 0){
			return false;
		}
			
		return true;
	}
	
	function getMainItemid(){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id')->from('#__menu')->where('home = 1');
		$db->setQuery($query);
		$Itemid = (int)$db->loadResult();
			
		return $Itemid;		
	}
	
	function getCatalogItemid(){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id')->from('#__menu')->where('link LIKE ' . $db->quote('index.php?option=com_ksenmart&view=catalog&layout=catalog'))->where('published=1');
		$db->setQuery($query);
		$Itemid = (int)$db->loadResult();
			
		return $Itemid;		
	}	
	
	public function uninstallModule($module, $position){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id')->from('#__modules')->where('module = '.$db->quote($module))->where('position = '.$db->quote($position))->where('client_id = 0');
		$db->setQuery($query);
		$moduleids = $db->loadColumn();
		foreach($moduleids as $moduleid){
			$query = $db->getQuery(true);
			$query->delete('#__modules')->where('id = '.$db->quote($moduleid));
			$db->setQuery($query);
			$db->query();
			$query = $db->getQuery(true);
			$query->delete('#__modules_menu')->where('moduleid = '.$db->quote($moduleid));
			$db->setQuery($query);
			$db->query();			
		}

		return true;
	}	
    
}