<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');
class JFormFieldKSAdminViews extends JFormFieldList {
	
	public $type 		= 'KSAdminViews';
	private $extension 	= null;
	
	public function getOptions() {
		$this->extension  	= !empty($this->element['extension'])?$this->element['extension']:null;
		$lang 				= JFactory::getLanguage();
		$lang->load('com_' . $this->extension . '.sys', JPATH_ADMINISTRATOR . '/components/com_' . $this->extension, null, false, false);
		$items 				= self::getViews();
		
		return $items;
	}
	
	private function getViews() {
		$items = array();
		$path  = JPATH_ROOT . '/administrator/components/com_' . $this->extension . '/views/';
		if(file_exists($path)){
			$items[] = JHtml::_('select.option', '*', JText::_('JALL'));
			$views 	 = scandir($path);
			
			foreach ($views as $view){
				if ($view != '.' && $view != '..' && is_dir($path)){
					$items[] = JHtml::_('select.option', $view, JText::_($view));
				}
			}
		}
		return $items;
	}
}