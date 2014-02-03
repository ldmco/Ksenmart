<?php
defined('JPATH_PLATFORM') or die;
jimport('joomla.application.component.view');

abstract class JViewKMAdmin extends JView
{

	public function __construct($config = array())
	{
		parent::__construct($config = array());
		
		$name=$this->getName();
		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger('onBeforeViewAdmin'.$name, array(&$this));	
		
		$this->path = KMPath::getInstance();
        $this->params = JComponentHelper::getParams('com_ksenmart');
        $this->document= JFactory::getDocument();		
	}
	
	public function display($tpl = null)
	{
		$name=$this->getName();
		$dispatcher = JDispatcher::getInstance();	
		$dispatcher->trigger('onAfterViewAdmin'.$name, array(&$this));
		
        parent::display($tpl);
	}	
	
	public function loadTemplate($tpl = null)
	{
		$name=$this->getName();
		$layout = $this->getLayout();
		$html='';
		
		$function = isset($tpl) ? $layout .'_'. $tpl : $layout;
		$dispatcher = JDispatcher::getInstance();	
		$dispatcher->trigger('onBeforeDisplayAdmin'.$name.$function, array(&$this,&$tpl,&$html));
		if ($tpl!='empty')
			$html.=parent::loadTemplate($tpl);
			
		$dispatcher->trigger('onAfterDisplayAdmin'.$name.$function, array(&$this,&$tpl,&$html));	

		return $html;
	}
	
}