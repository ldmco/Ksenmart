<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
abstract class JModelKSList extends JModelList {

    protected $params = null;

    private $ext_name_com   = null;
    private $ext_prefix     = null;

    public function __construct($config = array()) {
        parent::__construct($config);

        global $ext_name_com, $ext_prefix;
        $this->ext_name_com = $ext_name_com;
        $this->ext_prefix   = $ext_prefix;

        $this->context .= ($this->getName() && $layout = JRequest::getVar('layout', 'default')) ? '.' . $layout : '';
        $this->params = JComponentHelper::getParams($this->ext_name_com);
    }

    public function onExecuteBefore($function = null, $vars = array()) {
        $model = &$this;
        array_unshift($vars, $model);
        JDispatcher::getInstance()->trigger('onBeforeExecute' . strtoupper($this->ext_prefix) . $this->getName() . $function, $vars);
        
        return $this;
    }

    public function onExecuteAfter($function = null, $vars = array()) {
        $model = &$this;
        array_unshift($vars, $model);
        JDispatcher::getInstance()->trigger('onAfterExecute' . strtoupper($this->ext_prefix) . $this->getName() . $function, $vars);
        
        return $this;
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
	
}