<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class KSCoreHelper {
    
    public static function onExecuteBefore($vars = array()) {
        $trace = debug_backtrace(false);
        JDispatcher::getInstance()->trigger('onBeforeExecuteHelper' . get_called_class() . ucfirst($trace[1]['function']), $vars);
    }
    
    public static function onExecuteAfter($vars = array()) {
        $trace = debug_backtrace(false);
        JDispatcher::getInstance()->trigger('onAfterExecuteHelper' . get_called_class() . ucfirst($trace[1]['function']), $vars);
    }
}
