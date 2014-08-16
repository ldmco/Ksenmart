<?php defined('JPATH_PLATFORM') or die;

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
}