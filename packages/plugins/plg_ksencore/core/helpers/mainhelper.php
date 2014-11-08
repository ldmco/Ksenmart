<?php defined('_JEXEC') or die;

class KSMainhelper {

    private $ext_name_com   = null;
    private $ext_prefix     = null;
	private $helper_name    = null;
	
	
    public static function onExecuteBefore($function = null, $ext_prefix, $helper_name, $vars = array()) {

        JDispatcher::getInstance()->trigger('onBeforeExecuteHelper' . strtoupper($ext_prefix) . $helper_name . $function, $vars);
        
    }

    public static function onExecuteAfter($function = null, $ext_prefix, $helper_name, $vars = array()) {

        JDispatcher::getInstance()->trigger('onAfterExecuteHelper' . strtoupper($ext_prefix) . $helper_name . $function, $vars);
        
    }
   
}