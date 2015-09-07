<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');
class KsenMartModelSmmhunter extends JModelKSAdmin
{

    protected function populateState() 
	{
        $this->onExecuteBefore('populateState');

        $app = JFactory::getApplication();

        $this->onExecuteAfter('populateState');
    }
	
	public function getPlgParams()
	{
		$plg_params = new JRegistry();
		$params = new stdClass();
		
		$plugin = JPluginHelper::getPlugin('system', 'smmhunter');
		if ($plugin && isset($plugin->params)) 
		{
			$plg_params->loadString($plugin->params);
		}

		$params->login = $plg_params->get('login', '');
		$params->password = $plg_params->get('password', '');
		$params->smmhunter_code = $plg_params->get('smmhunter_code', '');

		return $params;
	}

}
