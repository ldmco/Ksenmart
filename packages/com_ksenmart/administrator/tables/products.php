<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('tables.ksentable');
class KsenmartTableProducts extends KsenTable {
    
    public function __construct(&$_db) {
        parent::__construct('#__ksenmart_products', 'id', $_db);
		JTableObserverTags::createObserver($this, array('typeAlias' => 'com_ksenmart.product'));
		JTableObserverContenthistory::createObserver($this, array('typeAlias' => 'com_ksenmart.product'));
    }
}