<?php defined('_JEXEC') or die;

KSSystem::import('controllers.ksencontroller');
class KsenController extends KsenControllerAdmin {
	public function __construct(array $config = array()){
		parent::__construct($config);

        $app = JFactory::getApplication();

        $extension = $app->getUserStateFromRequest('com_ksen.extension', 'extension','com_ksen');
        $app->setUserState('extension', $extension);
		$lang = JFactory::getLanguage();

		$lang->load($extension . '.sys') || 
		$lang->load($extension . '.sys', JPATH_ADMINISTRATOR.'/components/'.$extension, null , false) || 
		$lang->load($extension . '.sys', JPATH_ADMINISTRATOR.'/components/'.$extension, $lang->getDefault() , false);
	}
}