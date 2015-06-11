<?php 

/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class plgKMExportimportExport_csvInstallerScript {

    public function update($parent) {
        if(version_compare($parent->get('manifest')->version, '1.0.1', '==')){
            if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

            $db = $parent->get('db');
            $table = JTable::getInstance('extension');
            if($table->load(array('element' => 'export_csv', 'folder' => 'kmexportimport'))){
                $table->save(array(
                    'enabled' => 1
                ));
            }
        }
    }
}