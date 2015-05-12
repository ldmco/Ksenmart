<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JDispatcher::getInstance()->trigger('onLoadKsen', array('ksenmart.KSM', array('common'), array(), array('angularJS' => 0)));
class JFormFieldKSModules extends JFormField {
    
    public $type = 'KSModules';
    public $pages = array(
        1 => 'catalog',
        2 => 'product',
        3 => 'cart',
        4 => 'profile'
    );
    
    public function getInput() {
        $db = JFactory::getDBO();
		$this->value = is_array($this->value) ? $this->value : array();
		
        $html = '<style>.form-horizontal .controls {margin:0px;}</style>';
        $html.= '<table class="table table-striped" id="articleList">';
        $html.= '	<thead>';
        $html.= '		<tr>';
        $html.= '			<th class="title">' . JText::_('ksm_plugin_modules_module_title') . '</th>';
        $html.= '			<th width="10%" class="nowrap hidden-phone">' . JText::_('ksm_plugin_modules_module_position') . '</th>';
        $html.= '			<th width="30%" class="nowrap hidden-phone">' . JText::_('ksm_plugin_modules_module_pages') . '</th>';
        $html.= '			<th width="30%" class="hidden-phone">' . JText::_('ksm_plugin_modules_module_categories') . '</th>';
        $html.= '		</tr>';
        $html.= '	</thead>';
        $query = $db->getQuery(true);
        $query->select('a.id, a.title, a.position, a.published, map.menuid')->from('#__modules AS a')->join('LEFT', sprintf('#__modules_menu AS map ON map.moduleid = a.id AND map.menuid IN (0, %1$d, -%1$d)', KSSystem::getShopItemid()))->select('(SELECT COUNT(*) FROM #__modules_menu WHERE moduleid = a.id AND menuid < 0) AS ' . $db->quoteName('except'));
        
        $query->select('ag.title AS access_title')->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access')->where('a.published >= 0')->where('a.client_id = 0')->where('a.title != '.$db->quote(''))->order('a.position, a.ordering');
        
        $db->setQuery($query);
        $modules = $db->loadObjectList();
        $page_options = array(
            JHtml::_('select.option', 0, JText::_('JALL')) ,
            JHtml::_('select.option', -1, JText::_('ksm_plugin_modules_noone'))
        );
        foreach ($this->pages as $key => $page) $page_options[] = JHtml::_('select.option', $key, JText::_('ksm_plugin_modules_page_' . $page));
        $cat_options = $this->getCatOptions();
        foreach ($modules as $module) {
            if (is_null($module->menuid) && (!$module->except || $module->menuid < 0)) continue;
            
            $selected_pages = isset($this->value[$module->position][$module->id]['pages']) ? $this->value[$module->position][$module->id]['pages'] : array(0);
            $selected_cats = isset($this->value[$module->position][$module->id]['categories']) ? $this->value[$module->position][$module->id]['categories'] : array(0);
            $html.= '<tr>';
            $html.= '	<td class="title">' . $module->title . '</th>';
            $html.= '	<td width="10%" class="nowrap hidden-phone">' . $module->position . '</th>';
            $html.= '	<td width="30%" class="nowrap hidden-phone">' . JHtml::_('select.genericlist', $page_options, $this->name . '[' . $module->position . '][' . $module->id . '][pages][]', 'multiple="multiple"', 'value', 'text', $selected_pages) . '</th>';
            $html.= '	<td width="30%" class="hidden-phone">' . JHtml::_('select.genericlist', $cat_options, $this->name . '[' . $module->position . '][' . $module->id . '][categories][]', 'multiple="multiple"', 'value', 'text', $selected_cats) . '</th>';
            $html.= '</tr>';
        }
        
        $html.= '</table>';
        
        return $html;
    }
    
    public function getLabel() {
        $html = '';
        
        return $html;
    }
    
    protected function getCatOptions() {
        $options = array(
            JHtml::_('select.option', 0, JText::_('JALL')) ,
            JHtml::_('select.option', -1, JText::_('ksm_plugin_modules_noone'))
        );
        
        $options = $this->getCategories(0, 0, $options);
        
        return $options;
    }
    
    private function getCategories($parent = 0, $level, $items) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__ksenmart_categories')->where('published=1')->where('parent_id=' . $db->Quote($parent))->order('ordering');
        
        $db->setQuery($query);
        $cats = $db->loadObjectList();
        
        foreach ($cats as $cat) {
            $cat->title = str_repeat('- ', $level) . $cat->title;
            $items[] = JHtml::_('select.option', $cat->id, $cat->title);
            $items = $this->getCategories($cat->id, $level + 1, $items);
        }
        return $items;
    }
}
