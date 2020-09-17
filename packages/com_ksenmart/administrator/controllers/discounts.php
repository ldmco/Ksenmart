<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');
class KsenMartControllerDiscounts extends KsenMartController {
	
	function get_action_params() {
		$type = JRequest::getVar('type', '');
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('enabled')->from('#__extensions')->where('element=' . $db->quote($type))->where('folder="kmdiscountactions"');
		$db->setQuery($query);
		$enabled = $db->loadResult();
		if (empty($enabled) || !$enabled) JFactory::getApplication()->close();
		$dispatcher = JDispatcher::getInstance();
		$results = $dispatcher->trigger('onDisplayParamsForm', array(
			$type
		));
		if (isset($results[0]) && $results[0]) echo $results[0];
		JFactory::getApplication()->close();
	}

	public function get_search_items_html(){
		$ids = JRequest::getVar('ids');
		$items_tpl = JRequest::getVar('items_tpl');
		$html = '';

		$model = $this->getModel('discounts');
		$view = $this->getView('discounts','html');
		$view->setModel($model,true);
		$items = $model->getDiscounts($ids);
		$total = count($items);
		if ($total>0)
		{
			$view->setLayout($items_tpl);
			foreach($items as $item)
			{
				$view->item = &$item;
				ob_start();
				$view->display();
				$html.=ob_get_contents();
				ob_end_clean();
			}
		}

		$response=array(
			'html'=>$html,
			'total'=>$total
		);
		$response=json_encode($response);
		JFactory::getDocument()->setMimeEncoding('application/json');
		echo $response;
		JFactory::getApplication()->close();
	}
}