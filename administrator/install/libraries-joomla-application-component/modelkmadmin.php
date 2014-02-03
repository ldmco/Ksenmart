<?php
defined('JPATH_PLATFORM') or die;
jimport('joomla.application.component.modeladmin');
jimport('joomla.form.kmform');

abstract class JModelKMAdmin extends JModelAdmin
{

	var $db=null;
	var $total=null;
	var $form=null;
	var $context = null;
	var $params = null;

	public function __construct($config = array())
	{
		parent::__construct($config);
		
		$this->db=JFactory::getDBO();
		$this->context = 'com_ksenmart';
		$this->context .= ($view = JRequest::getVar('view',false))?'.'.$view:'';
		$this->context .= ($view && $layout = JRequest::getVar('layout','default'))?'.'.$layout:'';
		$this->params = JComponentHelper::getParams('com_ksenmart');
	}
	
	public function onExecuteBefore($function = null, $vars = array())
	{
		$model=&$this;
		array_unshift($vars,$model);
		$dispatcher = JDispatcher::getInstance();
		$name=$this->getName();
		$dispatcher->trigger('onBeforeExecute'.$name.$function, $vars);
	}	
	
	public function onExecuteAfter($function = null, $vars = array())
	{
		$model=&$this;
		array_unshift($vars,$model);
		$dispatcher = JDispatcher::getInstance();
		$name=$this->getName();
		$dispatcher->trigger('onAfterExecute'.$name.$function, $vars);
	}

	public function getForm($data = array(), $loadData = true, $control = 'jform')
	{
		JKMForm::addFormPath(JPATH_COMPONENT . '/models/forms');
		JKMForm::addFieldPath(JPATH_COMPONENT . '/models/fields');
		$form = JKMForm::getInstance('com_ksenmart.'.$this->form,$this->form,array('control' => $control, 'load_data' => $loadData));

		if (empty($form))
			return false;
		
		return $form;
	}	
	
	public function getTable($type = '', $prefix = 'KsenmartTable', $config = array())
	{
		return JTable::getInstance( $type, $prefix, $config );
	}

}