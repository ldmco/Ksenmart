<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('controllers.ksencontroller');
class KsenController extends KsenControllerAdmin {
    public function __construct(array $config = array()) {
        parent::__construct($config);
        
        $app = JFactory::getApplication();
        
        $extension = $app->getUserStateFromRequest('com_ksen.extension', 'extension', 'com_ksen');
        $app->setUserState('extension', $extension);
        $lang = JFactory::getLanguage();
        
		$lang->load($extension . '.sys') || 
		$lang->load($extension . '.sys', JPATH_ADMINISTRATOR.'/components/'.$extension, null , false) || 
		$lang->load($extension . '.sys', JPATH_ADMINISTRATOR.'/components/'.$extension, $lang->getDefault() , false);
    }
    
    public function display($cachable = false, $urlparams = false) {
        $db = JFactory::getDBO();
        
        $query = $db->getQuery(true);
        $query->select('date')->from('#__ksen_ping');
        $db->setQuery($query, 0, 1);
        $date = $db->loadResult();
        $cur_date = date('Y-m-d');
        if (empty($date) || $date < $cur_date) {
            $query = $db->getQuery(true);
            $query->update('#__ksen_ping')->set('`date`=' . $db->quote($cur_date));
            $db->setQuery($query);
            $db->query();
            
            $query = $db->getQuery(true);
            $query->select('manifest_cache')->from('#__extensions')->where('name="ksenmart"');
            $db->setQuery($query);
            $manifest = $db->loadResult();
            $manifest = json_decode($manifest, true);
            $version = $manifest['version'];
            
            file_get_contents('http://update.ksenmart.ru/statistic/?domain=' . $_SERVER['HTTP_HOST'] . '&version=' . $version);
        }
        
        parent::display();
    }
}
