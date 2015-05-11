<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');
class KsenModelSettings extends JModelKSAdmin {
    
    protected function populateState($ordering = null, $direction = null) {
        $this->onExecuteBefore('populateState');
        
        $app = JFactory::getApplication();
        
        $extension = $app->getUserStateFromRequest('com_ksen.extension', 'extension', 'com_ksen');
        $this->setState('extension', $extension);
        
        $this->onExecuteAfter('populateState');
    }
    
    function getForm($data = array(), $loadData = true) {
        $extension = $this->getState('extension');
        
        $path = JPATH_ADMINISTRATOR . '/components/' . $extension;
        $forms = array();
        $user = JFactory::getUser();
        $views = scandir($path . '/views/');
        
        foreach ($views as $view) {
            if ($view != '.' && $view != '..' && is_dir($path . '/views/' . $view) && file_exists($path . '/views/' . $view . '/config.xml')) {
                $xml = file_get_contents($path . '/views/' . $view . '/config.xml');
                $form = $this->loadForm($extension . '.' . $view, $xml, array('control' => 'jform', 'load_data' => $loadData), false, '/config');
                if (!empty($form)) {
                    $forms[$view] = $form;
                }
            }
        }
        
        return $forms;
    }
    
    function getComponent() {
        $this->onExecuteBefore('getComponent');
        
        $extension = $this->getState('extension');
        $result = JComponentHelper::getComponent($extension);
        
        $this->onExecuteAfter('getComponent', array(&$result));
        
        return $result;
    }
    
    public function save($data) {
        $this->onExecuteBefore('save', array(&$data));
        
        $table = JTable::getInstance('extension');
        // Save the rules.
        if (isset($data['params']) && isset($data['params']['rules'])) {
            $rules = new JAccessRules($data['params']['rules']);
            $asset = JTable::getInstance('asset');
            
            if (!$asset->loadByName($data['option'])) {
                $root = JTable::getInstance('asset');
                $root->loadByName('root.1');
                $asset->name = $data['option'];
                $asset->title = $data['option'];
                $asset->setLocation($root->id, 'last-child');
            }
            $asset->rules = (string )$rules;
            
            if (!$asset->check() || !$asset->store()) {
                $this->setError($asset->getError());
                
                return false;
            }
            // We don't need this anymore
            unset($data['option']);
            unset($data['params']['rules']);
        }
        // Load the previous Data
        if (!$table->load($data['id'])) {
            $this->setError($table->getError());
            
            return false;
        }
        
        unset($data['id']);
        // Bind the data.
        if (!$table->bind($data)) {
            $this->setError($table->getError());
            
            return false;
        }
        // Check the data.
        if (!$table->check()) {
            $this->setError($table->getError());
            
            return false;
        }
        // Store the data.
        if (!$table->store()) {
            $this->setError($table->getError());
            
            return false;
        }
        // Clean the component cache.
        $this->cleanCache('_system');
        
        $this->onExecuteAfter('save', array(&$data));
        
        return true;
    }
}
