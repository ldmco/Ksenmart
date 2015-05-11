<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JDispatcher::getInstance()->trigger('onLoadKsen', array('ksenmart.KSM', array('common'), array(), array('angularJS' => 0)));
class JFormFieldKSPluginsList extends JFormField {
    
    public $type = 'KSPluginsList';
    
    public function getInput() {
        $db = JFactory::getDBO();
        $lang = JFactory::getLanguage();
        $html = '';
        $attribs = '';
        $options = array(JHtml::_('select.option', 'all', JText::_('JALL')));
        $this->value = is_array($this->value) && count($this->value) ? $this->value : array('all');
        $query = $db->getQuery(true);
        $query->select('*')->from('#__extensions')->where('type=' . $db->quote('plugin'));
        if (isset($this->element['folder']) && !empty($this->element['folder'])) {
            $query->where('folder=' . $db->quote($this->element['folder']));
        }
        if (isset($this->element['multiple']) && !empty($this->element['multiple'])) {
            $attribs.= 'multiple="multiple"';
        }
        $db->setQuery($query);
        $plugins = $db->loadObjectList();
        foreach ($plugins as $plugin) {
            $lang->load('plg_' . $plugin->folder . '_' . $plugin->element . '.sys', JPATH_ADMINISTRATOR, null, false, false) || $lang->load('plg_' . $plugin->folder . '_' . $plugin->element . '.sys', JPATH_PLUGINS . DS . $plugin->folder . DS . $plugin->element, null, false, false) || $lang->load('plg_' . $plugin->folder . '_' . $plugin->element . '.sys', JPATH_ADMINISTRATOR, $lang->getDefault() , false, false) || $lang->load('plg_' . $plugin->folder . '_' . $plugin->element . '.sys', JPATH_PLUGINS . DS . $plugin->folder . DS . $plugin->element, $lang->getDefault() , false, false);
            $options[] = JHtml::_('select.option', $plugin->element, JText::_($plugin->name));
        }
        $html.= JHtml::_('select.genericlist', $options, $this->name, $attribs, 'value', 'text', $this->value) . '</th>';
        
        return $html;
    }
}