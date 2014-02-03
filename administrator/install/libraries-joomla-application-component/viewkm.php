<?php defined('JPATH_PLATFORM') or die;

jimport('joomla.application.component.view');

abstract class JViewKM extends JView {

    public function __construct($config = array()) {
        $config['base_path'] = JPATH_ROOT . DS . 'components' . DS . 'com_ksenmart';
        parent::__construct($config);

        $name = $this->getName();
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onBeforeView' . $name, array(&$this));
    }

    public function display($tpl = null) {
        $name = $this->getName();
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onAfterView' . $name, array(&$this));

        parent::display($tpl);
    }
    
    public function loadOtherTemplate($tpl = null, $layout, $view_name = null, array $vars = array()) {
        if(!empty($view_name)){
            $controller     = JController::getInstance('KsenMartController' . ucfirst($view_name));
            $view           = $controller->getView($view_name, 'html');
            $model          = $controller->getModel($view_name);
            $current_layout = $this->getLayout();
            
            $view->setModel($model, true);
            $view->setLayout($layout);
            
            foreach($vars as $name => $var){
                $view->assign($name, $var);
            }
            
            $html = $view->loadTemplate($tpl);
            
            $view->setLayout($current_layout);
        }else{
            $html = parent::loadTemplate($tpl);
        }

        return $html;
    }

    public function loadTemplate($tpl = null, $layout = null, array $vars = array()) {
        $name           = $this->getName();
        $current_layout = $this->getLayout();
        $html           = '';

        $function   = isset($tpl) ? $current_layout . '_' . $tpl : $current_layout;
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onBeforeDisplay' . $name . $function, array(&$this, &$tpl, &$html));
        
        if(!empty($layout)){
            $this->setLayout($layout);
        }
        
        foreach($vars as $name => $var){
            $this->assign($name, $var);
        }
		
        if($tpl != 'empty')
            $html .= parent::loadTemplate($tpl);

        $this->setLayout($current_layout);

        $dispatcher->trigger('onAfterDisplay' . $name . $function, array(&$this, &$tpl, &$html));

        return $html;
    }
}