<?php defined('_JEXEC') or die;

KSSystem::import('tables.ksentable');
class KsenmartTableProducts extends KsenTable {
    
    public function __construct(&$_db) {
        parent::__construct('#__ksenmart_products', 'id', $_db);
		JTableObserverTags::createObserver($this, array('typeAlias' => 'com_ksenmart.product'));
		JTableObserverContenthistory::createObserver($this, array('typeAlias' => 'com_ksenmart.product'));
    }
}