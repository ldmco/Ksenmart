<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
abstract class KMPlugin extends JPlugin {
    
    public function __construct(&$subject, $config) {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }
    
    public function loadLanguage($extension = '', $basePath = JPATH_ADMINISTRATOR){
        $lang = JFactory::getLanguage();
        $lang->load('plg_' . $this->_type . '_' . $this->_name . '.sys', JPATH_ADMINISTRATOR, null, false, false) || $lang->load('plg_' . $this->_type . '_' . $this->_name . '.sys', JPATH_PLUGINS . DS . $this->_type . DS . $this->_name, null, false, false) || $lang->load('plg_' . $this->_type . '_' . $this->_name . '.sys', JPATH_ADMINISTRATOR, $lang->getDefault() , false, false) || $lang->load('plg_' . $this->_type . '_' . $this->_name . '.sys', JPATH_PLUGINS . DS . $this->_type . DS . $this->_name, $lang->getDefault() , false, false);
    }
    
    public static function getDefaultCurrencyCode() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('code')->from('#__ksenmart_currencies')->where('`default`=1');
        $db->setQuery($query);
        $currency_code = $db->loadResult();
        if (empty($currency_code)) 
        return JText::_('ksm_discount_fixed_type_currency');
        
        return $currency_code;
    }
	
	function checkRegion($regions,$region_id)
	{
		$regions=json_decode($regions,true);
		if (!is_array($regions) || !count($regions))
			return true;
		
		foreach($regions as $country)
			if (in_array($region_id,$country))
				return true;
					
		return false;
	}	
	
}
