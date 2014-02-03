<?php defined('_JEXEC') or die;

class ModKMMainmenuHelper {

    public static function getCurrentWidget() {
        $jinput = JFactory::getApplication()->input;
        $view = $jinput->get('view', 'panel', 'string');
        if($view == 'panel') return false;
        if($view == 'account'){
            $view = $jinput->get('layout', null, 'string'); 
        }
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__ksenmart_widgets')->where('name='.$db->quote($view));
        $db->setQuery($query);
        $current_widget = $db->loadObject();
        return $current_widget;
    }

    public static function getParentWidget($current_widget) {
        if(!$current_widget) return false;
        if($current_widget->parent_id == 0) return $current_widget;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__ksenmart_widgets')->where('id=' . $current_widget->parent_id);
        $db->setQuery($query);
        $parent_widget = $db->loadObject();
        return $parent_widget;
    }

    public static function getChildWidgets($parent_widget) {
        if(!$parent_widget) return false;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__ksenmart_widgets')->where('parent_id=' . $parent_widget->id);
        $db->setQuery($query);
        $child_widgets = $db->loadObjectList();
        return $child_widgets;
    }

    public static function getCurrentWidgetType($parent_widget) {
		$app = JFactory::getApplication();
        $current_widget_type=$app->getUserStateFromRequest('com_ksenmart.panel.default.widget_type', 'widget_type','all');
        return $current_widget_type;
    }

    public static function getWidgetTypes() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__ksenmart_widgets_types')->where('published=1');
        $db->setQuery($query);
        $widget_types = $db->loadObjectList();
        return $widget_types;
    }

}