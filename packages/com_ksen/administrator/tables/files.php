<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if(!class_exists('KsenTable')) {
    require KSC_ADMIN_PATH_CORE_TABLES . 'ksentable.php';
}

class KsenTableFiles extends KsenTable {
    public function __construct(&$_db) {
        parent::__construct('#__ksen_files', 'id', $_db);
    }
}