<?php defined('_JEXEC') or die;

if(!class_exists('KsenTable')) {
    require KSC_ADMIN_PATH_CORE_TABLES . 'ksentable.php';
}

class KsenTableFiles extends KsenTable {
    public function __construct(&$_db) {
        parent::__construct('#__ksen_files', 'id', $_db);
    }
}