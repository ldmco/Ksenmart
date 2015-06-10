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

    public static function loadLocalHelpers(array $folders, array $ignoreHelpers = array(), $ext_name_com = null, $prefix = null){
        if(empty($prefix)){
            global $ext_prefix;
            $prefix = $ext_prefix;
        }
        if(empty($ext_name_com)){
            global $ext_name_com;
            $ext_name_com = $ext_name_com;
        }
        $base = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . $ext_name_com . DS . 'helpers' . DS;
        
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

    private static function loadHelper($class, $path, $prefix = null){
        if(empty($prefix)){
            global $ext_prefix;
            $prefix = $ext_prefix;
        }
        $class = $prefix . ucfirst($class);

        JLoader::register($class, $path);
        JLoader::load($class);
    }
}