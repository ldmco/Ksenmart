<?php
defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldKMAdminViews extends JFormFieldList
{
	public $type = 'KMAdminViews';

	function getOptions()
	{
		$lang = JFactory::getLanguage();
		$lang->load('com_ksenmart.sys',JPATH_ADMINISTRATOR.'/components/com_ksenmart', null, false, false);	
		$items=self::getViews();
		return $items;
	}
	
	function getViews()
	{
		$items=array();
		$items[] = JHtml::_('select.option', '*', JText::_('KSM_ALL_VIEWS'));
		$db=JFactory::getDBO();
		$query=$db->getQuery(true);
		$query->select('*')->from('#__ksenmart_components')->where('published=1')->order('ordering');
		$db->setQuery($query);
		$views=$db->loadObjectList();
		$views=scandir(JPATH_ROOT.'/administrator/components/com_ksenmart/views/');
		foreach($views as $view)
			if ($view!='.' && $view!='..' && is_dir(JPATH_ROOT.'/administrator/components/com_ksenmart/views/'.$view))
				$items[] = JHtml::_('select.option', $view, JText::_($view));
		return $items;	
	}
}
