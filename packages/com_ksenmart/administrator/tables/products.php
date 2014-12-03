<?php defined('_JEXEC') or die;

KSSystem::import('tables.ksentable');
class KsenmartTableProducts extends KsenTable {
    
    public function __construct(&$_db) {
        parent::__construct('#__ksenmart_products', 'id', $_db);
        $this->_observers = new JObserverUpdater($this);
        JObserverMapper::attachAllObservers($this);
        JObserverMapper::addObserverClassToClass('JTableObserverTags', 'KsenmartTableProducts', array('typeAlias' => 'com_ksenmart.product'));
    }
}