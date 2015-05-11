<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');
KSSystem::import('models.form.ksform');

abstract class JModelKSAdmin extends JModelAdmin {
    
    var $total = null;
    var $form = null;
    var $context = null;
    var $params = null;
    
    public $extensions_name = null;
    protected $table_prefix = null;
    
    private $ext_name_com = null;
    private $ext_prefix = null;
    
    public function __construct($config = array()) {
        parent::__construct($config);
        
        global $ext_name_com, $ext_prefix;
        $this->ext_name_com = $ext_name_com;
        $this->ext_prefix = $ext_prefix;

        $this->context = $this->ext_name_com;
        $this->context .= '.'.$this->getName();
        $this->context .= ($layout = JRequest::getVar('layout', 'default'))?'.'.$layout:'';
        $this->params = JComponentHelper::getParams($this->ext_name_com);
    }
    
    protected function populateState() {
        // Initialise variables.
        $table = $this->getTable();
        $key = $table->getKeyName();
        // Get the pk of the record from the request.
        $pk = JRequest::getInt($key);
        
        JFactory::getApplication()->setUserState($this->context . '.id', $pk);
        $this->setState($this->getName() . '.id', $pk);
        // Load the parameters.
        $value = JComponentHelper::getParams($this->ext_name_com);
        $this->setState('params', $value);
    }
    
    public function onExecuteBefore($function = null, $vars = array()) {
        $model = & $this;
        array_unshift($vars, $model);
        
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onBeforeExecute' . strtoupper($this->ext_prefix) . $this->getName() . $function, $vars);
    }
    
    public function onExecuteAfter($function = null, $vars = array()) {
        $model = & $this;
        array_unshift($vars, $model);
        
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onAfterExecute' . strtoupper($this->ext_prefix) . $this->getName() . $function, $vars);
    }
    
    public function getForm($data = array() , $loadData = true, $control = 'jform') {
        
        JKSForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        JKSForm::addFieldPath(JPATH_COMPONENT . '/models/fields');
        
        if (!$this->form) {
            $this->form = $this->getName();
        }
        
        $form = JKSForm::getInstance($this->ext_name_com . '.' . $this->form, $this->form, array(
            'control' => $control,
            'load_data' => $loadData
        ));
        
        if (empty($form)) 
        return false;
        
        
        return $form;
    }
    
    public function getTable($type = '', $prefix = null, $config = array()) {
        
        if (empty($type)) {
            $type = $this->getName();
        }
        
        if (empty($prefix)) {
            $this->table_prefix = str_replace('com_', '', $this->ext_name_com);
            $this->table_prefix = ucfirst($this->table_prefix) . 'Table';
        }
        $this->name = $type;
        
        return JTable::getInstance($type, $this->table_prefix, $config);
    }
    
    public function setName($name) {
        if (!empty($name)) {
            $this->name = $name;
        }
    }
}
