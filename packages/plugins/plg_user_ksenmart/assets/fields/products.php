<?php 
defined('_JEXEC') or die;

class JFormFieldProducts extends JFormField 
{

	protected $type = 'Products';
	
	public function getInput()
	{
		foreach($this->value as &$product)
		{
			$product = KSMProducts::getProduct($product);
		}
		
		$view = new stdClass();
		$view->name = $this->name;		
		$view->products = $this->value;		
		$html = KSSystem::loadPluginTemplate('ksenmart', 'user', $view, 'products_edit');
		
		return $html;
	}

}