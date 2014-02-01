<?php defined('_JEXEC') or die;

class KMHelper {

    public static function loadHelpers($folder = '') {
        $path = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_ksenmart'.DS.'helpers'.DS.$folder;
        $helpers = scandir($path);
        foreach ($helpers as $helper){
            if ($helper != '.' && $helper != '..' && is_file($path . DS . $helper)) {
            	require_once $path . DS . $helper;
            }
        }
    }
}