<?php defined('_JEXEC') or die;

if (!class_exists('KsenmartTable')) {
    require JPATH_COMPONENT_ADMINISTRATOR . DS . 'tables' . DS . 'ksenmart.php';
}

class KsenmartTableProducts extends KsenmartTable {
    
    public function __construct(&$_db) {
        parent::__construct('#__ksenmart_products', 'id', $_db);
        $this->_observers = new JObserverUpdater($this);
        JObserverMapper::attachAllObservers($this);
        JObserverMapper::addObserverClassToClass('JTableObserverTags', 'KsenmartTableProducts', array('typeAlias' => 'com_ksenmart.product'));
    }
}