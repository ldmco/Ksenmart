<?php 
defined( '_JEXEC' ) or die;
jimport( 'joomla.application.component.modelkmadmin' );

class KsenMartModelPanel extends JModelKMAdmin {

	function __construct() {
		parent::__construct();
	}

	function populateState()
	{
	    $this->onExecuteBefore('populateState');
        
		$app = JFactory::getApplication();
		if ($layout = JRequest::getVar('layout','default')) {
			$this->context .= '.'.$layout;
		}
		
		$widget_type=$app->getUserStateFromRequest($this->context . '.widget_type', 'widget_type','all');
		$this->setState('widget_type',$widget_type);
        
        $this->onExecuteAfter('populateState');	
	}
	
	function getWidgets()
	{
	    $this->onExecuteBefore('getWidgets');
        
		$widget_type=$this->getState('widget_type');
		$query=$this->db->getQuery(true);
		$query->select('kw.*')->from('#__ksenmart_widgets as kw');
		if ($widget_type!='all')
		{
			$query->leftjoin('#__ksenmart_widgets_types_values as kwtv on kwtv.widget_id=kw.id');
			$query->innerjoin('#__ksenmart_widgets_types as kwt on kwtv.type_id=kwt.id and kwt.name='.$this->db->quote($widget_type));
		}
		$this->db->setQuery($query);
		$widgets=$this->db->loadObjectList('name');
		
		$widgets_groups=array();
		$config=$this->getWidgetsConfig();
		foreach($widgets as &$widget)
		{
			$widget->image=JURI::root().'/media/ksenmart/images/icons/'.$widget->image;
			$widget->info='';
			if (file_exists(JPATH_COMPONENT.'/views/'.$widget->view.'/widget_info_'.$widget->name.'.php'))
			{
				ob_start();
				require JPATH_COMPONENT.'/views/'.$widget->view.'/widget_info_'.$widget->name.'.php';
				$widget->info=ob_get_contents();
				ob_end_clean();				
			}
		}
		unset($widget);
		
		if (empty($config))
		{
			foreach($widgets as $widget)
			{
				if (!isset($widgets_groups[$widget->group]))
					$widgets_groups[$widget->group]=array();
				$widgets_groups[$widget->group][$widget->name]=$widget;
			}
		}
		else
		{
			foreach($config as $group_id=>$config_widgets)
			{
				$widgets_groups[$group_id]=array();
				foreach($config_widgets as $config_widget_name=>$config_widget_size)
				{
					$widgets_groups[$group_id][$config_widget_name]=$widgets[$config_widget_name];
					$widgets_groups[$group_id][$config_widget_name]->class=$config_widget_size;
				}			
			}
		}
		
        $this->onExecuteAfter('getWidgets', array(&$widgets_groups));
		return $widgets_groups;
	}	
	
	function getWidgetsConfig()
	{
	    $this->onExecuteBefore('getWidgetsConfig');
        
		$widget_type=$this->getState('widget_type');
		$user_id = JFactory::getUser()->id;
		$query=$this->db->getQuery(true);
		$query->select('config_'.$widget_type)->from('#__ksenmart_widgets_users_config')->where('user_id='.$user_id);
		$this->db->setQuery($query);
		$config=$this->db->loadResult();
		if (empty($config))
			$config=null;
		else
			$config=json_decode($config,true);
		
        $this->onExecuteAfter('getWidgetsConfig', array(&$config));
		return $config;		
	}
	
	function saveWidgetsConfig($user_id,$config)
	{
	    $this->onExecuteBefore('saveWidgetsConfig', array(&$user_id,&$config));
        
		$widget_type=$this->getState('widget_type');
		$table = $this->getTable('widgetsusersconfig');
		
		$config=json_encode($config);
		$data=array(
			'user_id' => $user_id,
			'config_'.$widget_type => $config
		);
		
		$query=$this->db->getQuery(true);
		$query->insert('#__ksenmart_widgets_users_config')->columns('user_id')->values(array($user_id));
		$this->db->setQuery($query);
		$this->db->query();		
		
		if (!$table->bindCheckStore($data)) {
			$this->setError($table->getError());
			return false;
		}
		
        $this->onExecuteAfter('saveWidgetsConfig', array(&$user_id,&$config));
		return true;		
	}
}