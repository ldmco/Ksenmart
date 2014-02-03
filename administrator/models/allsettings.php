<?php defined( '_JEXEC' ) or die;

jimport('joomla.application.component.modelkmadmin');

class KsenMartModelAllSettings extends JModelKMAdmin {

	function populateState()
	{
	}

	function getForm($data = array(), $loadData = true)
	{
		$forms=array();
		$user=JFactory::getUser();
		$views=scandir(JPATH_COMPONENT.'/views/');
		foreach($views as $view)
		{
			if ($view!='.' && $view!='..' && is_dir(JPATH_COMPONENT.'/views/'.$view) && file_exists(JPATH_COMPONENT.'/views/'.$view.'/config.xml'))
			{
				//JForm::addFormPath(JPATH_COMPONENT.'/views/'.$view);
				$xml=file_get_contents(JPATH_COMPONENT.'/views/'.$view.'/config.xml');
				$form = $this->loadForm('com_ksenmart.'.$view,$xml,array('control' => 'jform', 'load_data' => $loadData),false,'/config');
				if (!empty($form)) {
					$forms[$view]=$form;
				}
			}	
		}
		return $forms;
	}
	
	function getComponent()
	{
	    $this->onExecuteBefore('getComponent');
        
		$result = JComponentHelper::getComponent('com_ksenmart');
        
        $this->onExecuteAfter('getComponent',array(&$result));
		return $result;
	}	
	
	public function save($data)
	{
	    $this->onExecuteBefore('save',array(&$data));
       
		$table	= JTable::getInstance('extension');

		// Save the rules.
		if (isset($data['params']) && isset($data['params']['rules'])) {
			$rules	= new JAccessRules($data['params']['rules']);
			$asset	= JTable::getInstance('asset');

			if (!$asset->loadByName($data['option'])) {
				$root	= JTable::getInstance('asset');
				$root->loadByName('root.1');
				$asset->name = $data['option'];
				$asset->title = $data['option'];
				$asset->setLocation($root->id, 'last-child');
			}
			$asset->rules = (string) $rules;

			if (!$asset->check() || !$asset->store()) {
				$this->setError($asset->getError());
				return false;
			}

			// We don't need this anymore
			unset($data['option']);
			unset($data['params']['rules']);
		}

		// Load the previous Data
		if (!$table->load($data['id'])) {
			$this->setError($table->getError());
			return false;
		}

		unset($data['id']);

		// Bind the data.
		if (!$table->bind($data)) {
			$this->setError($table->getError());
			return false;
		}

		// Check the data.
		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}

		// Store the data.
		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}

		// Clean the component cache.
		$this->cleanCache('_system');
        
        $this->onExecuteAfter('save',array(&$data));
		return true;
	}
	
}
