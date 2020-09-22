<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class ModKSMainMenuHelper
{

	public static function isPanel()
	{
		$jinput    = JFactory::getApplication()->input;
		$view      = $jinput->get('view', 'panel', 'string');	
		
		return $view == 'panel' ? true : false;
	}
	
	public static function getCurrentWidget()
	{
		$extension = self::getExtension();
		$jinput    = JFactory::getApplication()->input;
		$view      = $jinput->get('view', 'panel', 'string');
		if ($view == 'panel')
			return false;
		if ($view == 'account')
		{
			$view = $jinput->get('layout', null, 'string');
		}
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksen_widgets')->where('extension=' . $db->quote($extension))->where('name=' . $db->quote($view));
		$db->setQuery($query);
		$current_widget = $db->loadObject();

		return $current_widget;
	}
	
	public static function getWidgets()
	{
		$db    = JFactory::getDBO();
		$extension = self::getExtension();
		
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksen_widgets')->where('extension=' . $db->quote($extension));
		$db->setQuery($query);
		$widgets = $db->loadObjectList();

		return $widgets;
	}	

	public static function getWidgetExtensions()
	{
		$db        = JFactory::getDBO();
		$query     = $db->getQuery(true);
		$query->select('extension')->from('#__ksen_widgets')->group('extension');
		$db->setQuery($query);
		$widget_extensions = $db->loadObjectList();
		
		foreach($widget_extensions as &$widget_extension)
		{
			$widget_extension->name = str_replace('com_', '', $widget_extension->extension);
		}

		return $widget_extensions;
	}

	public static function getExtension()
	{
		$app       = JFactory::getApplication();
		$is_panel  = self::isPanel();
		
		if ($is_panel)
		{
			$extension = $app->input->get('extension', null);
		}
		else
		{
			global $ext_name_com;
			$extension = $app->getUserStateFromRequest('com_ksen.extension', 'extension', $ext_name_com);
		}

		return $extension;
	}

	public static function getBilling()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__ksenmart_info')
			->where('package=' . $db->q('ksenmart'));
		$db->setQuery($query);
		$billing = $db->loadObject();

		return $billing;
	}

}
