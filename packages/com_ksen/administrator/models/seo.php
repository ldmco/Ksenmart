<?php defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');
class KsenModelSeo extends JModelKSAdmin {
    
    protected function populateState($ordering = null, $direction = null) {
        $this->onExecuteBefore('populateState');
        
        $app = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_ksen');
        
        $extension = $app->getUserStateFromRequest('com_ksen.extension', 'extension', 'com_ksen');
        $this->setState('extension', $extension);
        
        $seo_type = $app->getUserStateFromRequest('com_ksen.seo.seo_type', 'seo_type', 'text');
        $this->setState('seo_type', $seo_type);
        $this->context.= '.' . $seo_type;
        
        $value = $app->getUserStateFromRequest($this->context . 'list.limit', 'limit', $params->get('admin_product_limit', 20), 0);
        $limit = $value;
        $this->setState('list.limit', $limit);
        
        $value = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0);
        $limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
        $this->setState('list.start', $limitstart);
        
        $this->onExecuteAfter('populateState');
    }
    
    function getUrlsConfigs() {
        $this->onExecuteBefore('getUrlsConfigs');
        
        $extension = $this->getState('extension');
        $query = $this->_db->getQuery(true);
        $query->select('*')->from('#__ksen_seo_config')->where('type="url"')->where('extension=' . $this->_db->quote($extension));
        $this->_db->setQuery($query);
        $config = $this->_db->loadObjectList('part');
        
        foreach ($config as & $c) $c->config = json_decode($c->config);
        
        $this->onExecuteAfter('getUrlsConfigs', array(&$config));
        
        return $config;
    }
    
    function saveUrlsConfigs($config) {
        $this->onExecuteBefore('saveUrlsConfigs', array(&$config));
        
        $extension = $this->getState('extension');
        foreach ($config as $key => $val) {
            $query = $this->_db->getQuery(true);
            $query->update('#__ksen_seo_config')->set('config=' . $this->_db->quote(json_encode($val)))->where('part=' . $this->_db->quote($key))->where('type="url"')->where('extension=' . $this->_db->quote($extension));
            $this->_db->setQuery($query);
            $this->_db->Query();
        }
        
        $this->onExecuteAfter('saveUrlsConfigs');
    }
    
    function getMetaConfigs() {
        $this->onExecuteBefore('getMetaConfigs');
        
        $extension = $this->getState('extension');
        $query = $this->_db->getQuery(true);
        $query->select('*')->from('#__ksen_seo_config')->where('type="meta"')->where('extension=' . $this->_db->quote($extension));
        $this->_db->setQuery($query);
        $config = $this->_db->loadObjectList('part');
        
        foreach ($config as & $c) $c->config = json_decode($c->config);
        
        $this->onExecuteAfter('getMetaConfigs', array(&$config));
        
        return $config;
    }
    
    function saveMetaConfigs($config) {
        $this->onExecuteBefore('saveMetaConfigs', array(&$config));
        
        $extension = $this->getState('extension');
        foreach ($config as $key => $val) {
            $query = $this->_db->getQuery(true);
            $query->select('config')->from('#__ksen_seo_config')->where('part=' . $this->_db->quote($key))->where('type="meta"');
            $this->_db->setQuery($query);
            $db_config = json_decode($this->_db->loadResult());
            $val['description']['types'] = $db_config->description->types;
            $val['keywords']['types'] = $db_config->keywords->types;
            $query = $this->_db->getQuery(true);
            $query->update('#__ksen_seo_config')->set('config=' . $this->_db->quote(json_encode($val)))->where('part=' . $this->_db->quote($key))->where('type="meta"')->where('extension=' . $this->_db->quote($extension));
            $this->_db->setQuery($query);
            $this->_db->Query();
        }
        
        $this->onExecuteAfter('saveMetaConfigs');
    }
    
    function getTitlesConfigs() {
        $this->onExecuteBefore('getTitlesConfigs');
        
        $extension = $this->getState('extension');
        $query = $this->_db->getQuery(true);
        $query->select('*')->from('#__ksen_seo_config')->where('type="title"')->where('extension=' . $this->_db->quote($extension));
        $this->_db->setQuery($query);
        $config = $this->_db->loadObjectList('part');
        
        foreach ($config as & $c) $c->config = json_decode($c->config);
        
        $this->onExecuteAfter('getTitlesConfigs', array(&$config));
        
        return $config;
    }
    
    function saveTitlesConfigs($config) {
        $this->onExecuteBefore('saveTitlesConfigs', array(&$config));
        
        $extension = $this->getState('extension');
        foreach ($config as $key => $val) {
            $query = $this->_db->getQuery(true);
            $query->update('#__ksen_seo_config')->set('config=' . $this->_db->quote(json_encode($val)))->where('part=' . $this->_db->quote($key))->where('type="title"')->where('extension=' . $this->_db->quote($extension));
            $this->_db->setQuery($query);
            $this->_db->Query();
        }
        
        $this->onExecuteAfter('saveTitlesConfigs');
    }
    
    function getSeoURLValue() {
        $this->onExecuteBefore('getSeoURLValue');
        
        $section = JRequest::getVar('section', null);
        $values = JRequest::getVar('values', array());
        $values[] = 'seo-user-value';
        $seourlvalue = new stdClass();
        $seourlvalue->values = $values;
        $seourlvalue->section = $section;
        
        $this->onExecuteAfter('getSeoURLValue', array(&$seourlvalue));
        
        return $seourlvalue;
    }
    
    function getSeoTitleValue() {
        $this->onExecuteBefore('getSeoTitleValue');
        
        $section = JRequest::getVar('section', null);
        $values = JRequest::getVar('values', array());
        $values[] = 'seo-user-value';
        if ($section == 'product') $values[] = 'seo-property';
        $query = $this->_db->getQuery(true);
        $query->select('id,title')->from('#__ksenmart_properties');
        $this->_db->setQuery($query);
        $properties = $this->_db->loadObjectList();
        $seotitlevalue = new stdClass();
        $seotitlevalue->values = $values;
        $seotitlevalue->section = $section;
        $seotitlevalue->properties = $properties;
        
        $this->onExecuteAfter('getSeoTitleValue', array(&$seotitlevalue));
        
        return $seotitlevalue;
    }
    
    function getListItems() {
        $this->onExecuteBefore('getListItems');
        
        $extension = $this->getState('extension');
        $countries = $this->getState('countries');
        $order_dir = $this->getState('order_dir');
        $order_type = $this->getState('order_type');
        $query = $this->_db->getQuery(true);
        $query->select('SQL_CALC_FOUND_ROWS *')->from('#__ksen_seo_texts')->order('id desc')->where('extension=' . $this->_db->quote($extension));
        $this->_db->setQuery($query, $this->getState('list.start'), $this->getState('list.limit'));
        $seo_texts = $this->_db->loadObjectList();
        $query = $this->_db->getQuery(true);
        $query->select('FOUND_ROWS()');
        $this->_db->setQuery($query);
        $this->total = $this->_db->loadResult();
        
        foreach ($seo_texts as & $seo_text) {
            $seo_text->params = json_decode($seo_text->params, true);
            $params = '';
            $ext_name = str_replace('com_', '', $seo_text->extension);
            
            foreach ($seo_text->params as $key => $val) {
                $table_name = '#__' . $ext_name . '_' . $key;
                $vals = array();
                foreach ($val as $v) {
                    $query = $this->_db->getQuery(true);
                    $query->select('title')->from($table_name)->where('id=' . $v);
                    $this->_db->setQuery($query);
                    $title = $this->_db->loadResult();
                    if (!empty($title)) $vals[] = $title;
                }
                if (count($vals)) $params.= JText::_('ks_seo_seotext_param_' . $key) . implode(',', $vals) . '<br>';
            }
            
            $seo_text->params = $params;
            $seo_text->text = mb_substr(strip_tags($seo_text->text), 0, 150);
        }
        
        $this->onExecuteAfter('getListItems', array(&$seo_texts));
        
        return $seo_texts;
    }
    
    function getTotal() {
        $this->onExecuteBefore('getTotal');
        
        $total = $this->total;
        
        $this->onExecuteAfter('getTotal', array(&$total));
        
        return $total;
    }
    
    function deleteListItems($ids) {
        $this->onExecuteBefore('deleteListItems', array(&$ids));
        
        $table = $this->getTable('seotexts');
        
        foreach ($ids as $id) $table->delete($id);
        
        $this->onExecuteAfter('deleteListItems', array(&$ids));
        
        return true;
    }
    
    function getSeoText() {
        $this->onExecuteBefore('getSeoText');
        
        $id = JRequest::getInt('id');
        $seotext = KSSystem::loadDbItem($id, 'seotexts');
        $seotext->params = json_decode($seotext->params, true);
        foreach ($seotext->params as $key => $val) {
            $seotext->{$key} = $val;
        }
        
        $this->onExecuteAfter('getSeoText', array(&$seotext));
        
        return $seotext;
    }
    
    function saveSeoText($data) {
        $this->onExecuteBefore('saveSeoText', array(&$data));
        
        $extension = $this->getState('extension');
        $data['extension'] = $extension;
        $params = array();
        $standarts = array('id', 'extension', 'text', 'metatitle', 'metadescription', 'metakeywords');
        
        foreach ($data as $key => $val) {
            if (!in_array($key, $standarts)) {
                $params[$key] = $val;
                unset($data[$key]);
            }
        }
        $data['params'] = json_encode($params);
        
        $table = $this->getTable('seotexts');
        if (!$table->bindCheckStore($data)) {
            $this->setError($table->getError());
            
            return false;
        }
        $id = $table->id;
        
        $on_close = 'window.parent.SeoTextsList.refreshList();';
        $return = array('id' => $id, 'on_close' => $on_close);
        
        $this->onExecuteBefore('saveSeoText', array(&$return));
        
        return $return;
    }
}
