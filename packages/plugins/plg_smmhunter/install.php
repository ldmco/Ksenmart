<?php 
defined('_JEXEC') or die;

class plgSystemSmmhunterInstallerScript 
{

    public function postflight($type, $parent)
	{
		if ($type != 'install' && $type != 'update')
		{
			return;
		}
		
        $app = JFactory::getApplication();
		$db = JFactory::getDBO();
        $path_from = JPATH_ROOT.'/plugins/system/smmhunter/install';
        $path_to= JPATH_ROOT.'/administrator/components/com_ksenmart';
        
        if (!JFile::copy($path_from.'/controllers/smmhunter.php', $path_to.'/controllers/smmhunter.php')) 
		{
            $app->enqueueMessage('Couldnt move file');
        }
		
        if (!JFile::copy($path_from.'/models/smmhunter.php', $path_to.'/models/smmhunter.php')) 
		{
            $app->enqueueMessage('Couldnt move file');
        }

        if (!JFile::copy($path_from.'/forms/smmhunter.xml', $path_to.'/models/forms/smmhunter.xml')) 
		{
            $app->enqueueMessage('Couldnt move file');
        }

        if (!JFolder::copy($path_from.'/views/smmhunter', $path_to.'/views/smmhunter')) 
		{
            $app->enqueueMessage('Couldnt move file');
        }	

        if (!JFile::copy($path_from.'/images/smmhunter.png', JPATH_ROOT.'/media/com_ksenmart/images/icons/smmhunter.png')) 
		{
            $app->enqueueMessage('Couldnt move file');
        }		
		
		$query = $db->getQuery(true);
		$query
			->update('#__extensions')
			->set('enabled = '.$db->quote('1'))
			->where('element = '.$db->quote('smmhunter'))
			->where('type = '.$db->quote('plugin'))
			->where('folder = '.$db->quote('system'))
		;
		$db->setQuery($query);
		$db->query();
		
		$values = array(
			'`extension`' => $db->quote('com_ksenmart'),
			'`parent_id`' => 0,
			'`group`' => 4,
			'`class`' => $db->quote('main'),
			'`href`' => $db->quote('index.php?option=com_ksenmart&view=smmhunter'),
			'`image`' => $db->quote('smmhunter.png'),
			'`name`' => $db->quote('smmhunter'),
			'`view`' => $db->quote('smmhunter')
		);
		$query = $db->getQuery(true);
		$query
			->insert('#__ksen_widgets')
			->columns(array_keys($values))
			->values(implode(',', $values))
		;
		$db->setQuery($query);
		$db->query();

		$query = 'alter table `#__ksenmart_orders` add `vk_user_id` varchar( 256 ) not null';
		$db->setQuery($query);
		$db->query();		
		
		$query = $db->getQuery(true);
		$query
			->select('user_id, config')
			->from('#__ksen_widgets_users_config')
			->where('extension = '.$db->quote('com_ksenmart'))
			->where('widget_type = '.$db->quote('all'))
		;
		$db->setQuery($query);
		$configs = $db->loadObjectList();
		
		foreach($configs as $config)
		{
			$config->config = json_decode($config->config, true);
			$last_group = array_pop($config->config);
			$last_group['smmhunter'] = 'sub';
			$config->config[] = $last_group;
			$config->config = json_encode($config->config);
			
			$query = $db->getQuery(true);
			$query
				->update('#__ksen_widgets_users_config')
				->set('config = '.$db->quote($config->config))
				->where('extension = '.$db->quote('com_ksenmart'))
				->where('widget_type = '.$db->quote('all'))
				->where('user_id = '.$config->user_id)
			;
			$db->setQuery($query);
			$db->query();			
		}		
        
        JFolder::delete($path_from);
    }
	
    public function uninstall($parent) 
	{
        $app = JFactory::getApplication();
		$db = JFactory::getDBO();
        $path = JPATH_ROOT.'/administrator/components/com_ksenmart';		
		
        if (!JFile::delete($path.'/controllers/smmhunter.php')) 
		{
            $app->enqueueMessage('Couldnt delete file');
        }		
		
        if (!JFile::delete($path.'/models/smmhunter.php')) 
		{
            $app->enqueueMessage('Couldnt delete file');
        }	

        if (!JFile::delete($path.'/models/forms/smmhunter.xml')) 
		{
            $app->enqueueMessage('Couldnt delete file');
        }	

        if (!JFolder::delete($path.'/views/smmhunter')) 
		{
            $app->enqueueMessage('Couldnt delete folder');
        }	

        if (!JFile::delete(JPATH_ROOT.'/media/com_ksenmart/images/icons/smmhunter.png')) 
		{
            $app->enqueueMessage('Couldnt delete file');
        }		

		$query = $db->getQuery(true);
		$query
			->select('user_id, config')
			->from('#__ksen_widgets_users_config')
			->where('extension = '.$db->quote('com_ksenmart'))
			->where('widget_type = '.$db->quote('all'))
		;
		$db->setQuery($query);
		$configs = $db->loadObjectList();
		
		foreach($configs as $config)
		{
			$config->config = json_decode($config->config, true);
			foreach($config->config as $key => $group)
			{
				unset($config->config[$key]['smmhunter']);
				if (!count($config->config[$key]))
				{
					unset($config->config[$key]);
				}
			}
			$config->config = json_encode($config->config);
			
			$query = $db->getQuery(true);
			$query
				->update('#__ksen_widgets_users_config')
				->set('config = '.$db->quote($config->config))
				->where('extension = '.$db->quote('com_ksenmart'))
				->where('widget_type = '.$db->quote('all'))
				->where('user_id = '.$config->user_id)
			;
			$db->setQuery($query);
			$db->query();			
		}			
	}
	
}