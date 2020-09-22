<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class KSLoader {

    public static function loadCoreHelpers(array $folders, array $ignoreHelpers = array()) {
        self::loadHelpers($folders, KSC_ADMIN_PATH_CORE_HELPERS, $ignoreHelpers, 'KS');
    }

    public static function loadLocalHelpers(array $folders, array $ignoreHelpers = array(), $ext_name_component = null, $prefix = null){
        if(empty($prefix)){
            global $ext_prefix;
            $prefix = $ext_prefix;
        }
        if(empty($ext_name_component)){
            global $ext_name_com;
	        $ext_name_component = $ext_name_com;
        }
        $base = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . $ext_name_component . DS . 'helpers' . DS;
        
        self::loadHelpers($folders, $base, $ignoreHelpers, $prefix);
    }

    private static function loadHelpers(array $folders, $base, array $ignoreHelpers = array(), $prefix = null) {
        $ignoreHelpers[] = 'index';
        foreach($folders as $folder){

            $path       = $base . $folder;
            $helpers    = scandir($path);

            foreach ($helpers as $helper){
                list($tHelper) = explode('.', $helper);
                if(!in_array($tHelper, $ignoreHelpers)){
                    if ($helper != '.' && $helper != '..' && is_file($path . DS . $helper)) {
                        self::loadHelper($tHelper, $path . DS . $helper);
                    }
                }
            }
        }
    }

    public static function loadClass($class, $ext_name_component = null){

	    if(empty($ext_name_component)){
		    global $ext_name_com;
		    $ext_name_component = $ext_name_com;
	    }

	    $path = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . $ext_name_component . DS . 'classes' . DS . $class . '.php';

	    JLoader::register($class, $path);
	    JLoader::load($class);
    }

    private static function loadHelper($class, $path, $prefix = null){
        if(empty($prefix)){
            global $ext_prefix;
            $prefix = $ext_prefix;
        }
        $class = $prefix . ucfirst($class);

        JLoader::register($class, $path);
        JLoader::load($class);
    }

    private static function loadAnimate(){
    	$templateConfig = JPATH_THEMES . '/' . JFactory::getApplication()->getTemplate() . '/html/com_ksenmart/animateconfig.php';
	    if (JFile::exists($templateConfig)){
		    require_once($templateConfig);
	    } else {
		    require_once(KSC_ADMIN_PATH_CORE . 'animateconfig.php');
	    }
    }
}