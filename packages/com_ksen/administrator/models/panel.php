<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');
class KsenModelPanel extends JModelKSAdmin {
    
    protected function populateState($ordering = null, $direction = null) {
        $this->onExecuteBefore('populateState');
        
        $app = JFactory::getApplication();
        
        $extension = $app->getUserStateFromRequest('com_ksen.extension', 'extension', 'com_ksen');
        $this->setState('extension', $extension);
        $widget_type = $app->getUserStateFromRequest($this->context . '.widget_type', 'widget_type', 'all');
        $this->setState('widget_type', $widget_type);
        
        $this->onExecuteAfter('populateState');
    }
    
    function getWidgets() {
        $this->onExecuteBefore('getWidgets');
        
        $extension = $this->getState('extension');
        $widget_type = $this->getState('widget_type');
        $query = $this->_db->getQuery(true);
        $query->select('kw.*')->from('#__ksen_widgets as kw')->where('kw.extension=' . $this->_db->quote($extension));
        if ($widget_type != 'all') {
            $query->leftjoin('#__ksen_widgets_types_values as kwtv on kwtv.widget_id=kw.id');
            $query->innerjoin('#__ksen_widgets_types as kwt on kwtv.type_id=kwt.id and kwt.name=' . $this->_db->quote($widget_type));
        }
        $this->_db->setQuery($query);
        $widgets = $this->_db->loadObjectList('name');
        
        $widgets_groups = array();
        $config = $this->getWidgetsConfig();
        foreach ($widgets as & $widget) {
            $widget->image = JURI::root() . '/media/' . $extension . '/images/icons/' . $widget->image;
            $widget->info = '';
            if (file_exists(JPATH_ADMINISTRATOR . '/components/' . $extension . '/views/' . $widget->view . '/widget_info_' . $widget->name . '.php')) {
                ob_start();
                require JPATH_ADMINISTRATOR . '/components/' . $extension . '/views/' . $widget->view . '/widget_info_' . $widget->name . '.php';
                $widget->info = ob_get_contents();
                ob_end_clean();
            }
        }
        unset($widget);
        
        if (empty($config)) {
            foreach ($widgets as $widget) {
                if (!isset($widgets_groups[$widget->group])) $widgets_groups[$widget->group] = array();
                $widgets_groups[$widget->group][$widget->name] = $widget;
                $widgets_groups[$widget->group][$widget->name]->class = str_replace(',', ' ', $widget->class);
            }
        } else {
            foreach ($config as $group_id => $config_widgets) {
                $widgets_groups[$group_id] = array();
                foreach ($config_widgets as $config_widget_name => $config_widget_size) {
                    $widgets_groups[$group_id][$config_widget_name] = $widgets[$config_widget_name];
                    $widgets_groups[$group_id][$config_widget_name]->class = str_replace(',', ' ', $config_widget_size);
                }
            }
        }
        
        $this->onExecuteAfter('getWidgets', array(&$widgets_groups));
        return $widgets_groups;
    }
    
    function getWidgetsConfig() {
        $this->onExecuteBefore('getWidgetsConfig');
        
        $extension = $this->getState('extension');
        $widget_type = $this->getState('widget_type');
        $user_id = JFactory::getUser()->id;
        $query = $this->_db->getQuery(true);
        $query->select('config')->from('#__ksen_widgets_users_config')->where('extension=' . $this->_db->quote($extension))->where('widget_type=' . $this->_db->quote($widget_type))->where('user_id=' . $user_id);
        $this->_db->setQuery($query);
        $config = $this->_db->loadResult();
        if (empty($config)) $config = null;
        else $config = json_decode($config, true);
        
        $this->onExecuteAfter('getWidgetsConfig', array(&$config));
        return $config;
    }
    
    function saveWidgetsConfig($user_id, $config) {
        $this->onExecuteBefore('saveWidgetsConfig', array(&$user_id, &$config));
        
        $extension = $this->getState('extension');
        $widget_type = $this->getState('widget_type');
        $table = $this->getTable('widgetsusersconfig');
        
        $config = json_encode($config);
        
        $query = $this->_db->getQuery(true);
        $query->select('count(user_id)')->from('#__ksen_widgets_users_config')->where('extension=' . $this->_db->quote($extension))->where('widget_type=' . $this->_db->quote($widget_type))->where('user_id=' . $user_id);
        $this->_db->setQuery($query);
        $count = $this->_db->loadResult();
        
        if (empty($count)) {
            $data = array('user_id' => $user_id, 'extension' => $this->_db->quote($extension), 'widget_type' => $this->_db->quote($widget_type), 'config' => $this->_db->quote($config));
            $query = $this->_db->getQuery(true);
            $query->insert('#__ksen_widgets_users_config')->values(implode(',', $data));
            $this->_db->setQuery($query);
            $this->_db->query();
        } else {
            $query = $this->_db->getQuery(true);
            $query->update('#__ksen_widgets_users_config')->set('config=' . $this->_db->quote($config))->where('extension=' . $this->_db->quote($extension))->where('widget_type=' . $this->_db->quote($widget_type))->where('user_id=' . $user_id);
            $this->_db->setQuery($query);
            $this->_db->query();
        }
        
        $this->onExecuteAfter('saveWidgetsConfig', array(&$user_id, &$config));
        return true;
    }
}
