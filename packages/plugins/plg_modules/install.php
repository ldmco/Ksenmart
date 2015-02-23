<?php defined('_JEXEC') or die;

class plgKMPluginsModulesInstallerScript {
    
    public function update($parent) {
        if(version_compare($parent->get('manifest')->version, '1.0.0', '==')){
            if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

            $db = $parent->get('db');
            $table = JTable::getInstance('extension');
            if($table->load(array('element' => 'modules', 'type' => 'plugin', 'folder' => 'kmplugins'))){
                $table->save(array(
                    'enabled' => 1
                ));
            }
        }
    }
}