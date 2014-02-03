<?php defined('JPATH_PLATFORM') or die;
jimport('joomla.application.component.modellist');

abstract class JModelKMList extends JModelList {

    protected $params = null;

    public function __construct($config = array()) {
        parent::__construct($config);

        $this->context .= ($this->getName() && $layout = JRequest::getVar('layout', 'default')) ? '.' . $layout : '';
        $this->params = JComponentHelper::getParams('com_ksenmart');
    }

    public function onExecuteBefore($function = null, $vars = array()) {
        $model = &$this;
        array_unshift($vars, $model);
        JDispatcher::getInstance()->trigger('onBeforeExecute' . $this->getName() . $function, $vars);
        
        return $this;
    }

    public function onExecuteAfter($function = null, $vars = array()) {
        $model = &$this;
        array_unshift($vars, $model);
        JDispatcher::getInstance()->trigger('onAfterExecute' . $this->getName() . $function, $vars);
        
        return $this;
    }
}