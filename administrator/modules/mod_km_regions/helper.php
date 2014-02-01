<?php defined('_JEXEC') or die('Restricted access');

class ModKMRegionsHelper {

    public static function getRegions() {

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query
            ->select('
                pt.id,
                pt.name,
                pt.params
            ')
            ->from('#__ksenmart_payment_types AS pt')
        ;
        
        $db->setQuery($query);
        $payment_types = $db->loadObjectList();
        
        return $payment_types;
    }
}