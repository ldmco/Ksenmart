<?php defined('JPATH_PLATFORM') or die;

jimport('joomla.application.component.view');
abstract class JViewKS extends JViewLegacy {

    private $ext_name_com   = null;
    private $ext_prefix     = null;
    private $ext_name       = null;

    public function __construct($config = array()) {

        global $ext_name_com, $ext_name, $ext_prefix;
        $this->ext_name_com = $ext_name_com;
        $this->ext_prefix   = $ext_prefix;
        $this->ext_name     = $ext_name;

        $config['base_path'] = JPATH_ROOT . DS . 'components' . DS . $this->ext_name_com;
        parent::__construct($config);

        $name = $this->getName();
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onBeforeView' . strtoupper($this->ext_prefix) . $name, array(&$this));
    }

    public function display($tpl = null) {
        $name = $this->getName();
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onAfterView' . strtoupper($this->ext_prefix) . $name, array(&$this));

        parent::display($tpl);
    }
    
    public function loadOtherTemplate($tpl = null, $layout, $view_name = null, array $vars = array()) {
        if(!empty($view_name)){
            $controller     = JControllerLegacy::getInstance(ucfirst($this->ext_name) . 'Controller' . ucfirst($view_name));
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
        $dispatcher->trigger('onBeforeDisplay' . strtoupper($this->ext_prefix) . $name . $function, array(&$this, &$tpl, &$html));
        
        if(!empty($layout)){
            $this->setLayout($layout);
        }
        
        foreach($vars as $name => $var){
            $this->assign($name, $var);
        }
		
        if($tpl != 'empty')
            $html .= parent::loadTemplate($tpl);

        $this->setLayout($current_layout);

        $dispatcher->trigger('onAfterDisplay' . strtoupper($this->ext_prefix) . $name . $function, array(&$this, &$tpl, &$html));

        return $html;
    }
}