<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class KsenControllerUsers extends KsenController
{

    function get_user_orders() {
        $id = JRequest::getInt('id');
        $html = '';

        $model = $this->getModel('users');
        $view = $this->getView('users', 'html');
        $view->setModel($model, true);
		$view->setLayout('user_orders');
		$view->orders = $model->getUserOrders($id);
		ob_start();
		$view->display();
		$html.=ob_get_contents();
		ob_end_clean();

        $response = array(
            'html' => $html,
            'message' => array(),
            'errors' => 0
		);
        $response = json_encode($response);
        JFactory::getDocument()->setMimeEncoding('application/json');
        echo $response;
        JFactory::getApplication()->close();
    }

	function set_user_subsriber()
	{
		$db=JFactory::getDBO();
		$user_id=JRequest::getVar('user_id',null);
		$value=JRequest::getVar('value',null);
		$query=$db->getQuery(true);
		$query->select('group_id')->from('#__user_usergroup_map')->where('user_id='.$user_id);
		$db->setQuery($query);
		$groups=$db->loadColumn();	
		if ($value==1 && !in_array(KSUsers::getSubscribersGroupID(),$groups))
		{
			$query=$db->getQuery(true);
			$values=array(KSUsers::getSubscribersGroupID(),$user_id);
			$query->insert('#__user_usergroup_map')->columns('group_id,user_id')->values(implode(',', $values));			
			$db->setQuery($query);
			$db->Query();
		}	
		elseif ($value==0)
		{
			$query = $db->getQuery(true);
			$query->delete('#__user_usergroup_map')->where('user_id='.$user_id)->where('group_id='.KSUsers::getSubscribersGroupID());
			$db->setQuery($query);
			$db->query();			
		}	
		
		$response=array(
			'erros'=>0,
			'message'=>array()
		);
		$response=json_encode($response);
		JFactory::getDocument()->setMimeEncoding('application/json');
		echo $response;
        JFactory::getApplication()->close();	
	}

	public function get_search_items_html(){
		$ids = JRequest::getVar('ids');
		$items_tpl = JRequest::getVar('items_tpl');
		$html='';
		
		$model=$this->getModel('users');
		$view=$this->getView('users','html');
		$view->setModel($model,true);
		$items=$model->getUsers($ids);
		$total=count($items);
		if ($total>0)
		{
			$view->setLayout($items_tpl);
			foreach($items as $item)
			{
				$view->item=&$item;
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
