<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class KsenMartControllerSmmhunter extends KsenMartController 
{
	
	public function register()
	{
		$app = JFactory::getApplication();
		$data = $app->input->get('jform', array(), 'ARRAY');
		
		$response = $this->send($data['name'], $data['email']);
		
		echo $response;
		
		$app->close();		
	}
	
	public function send($name, $email)
	{
		$db = JFactory::getDBO();
		
		$postdata = http_build_query(
			array(
				'url' => JURI::root(),
				'name' => $name,
				'email' => $email
			)
		);				
		$opts = array(
			'http' => array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => $postdata
			)
		);
		$context = stream_context_create($opts);
		
		if (!$response = file_get_contents('http://smm-hunter.ru/index.php?option=com_smmhunter&task=api.register', false, $context))
		{
			$response = array(
				'status ' => 'error',
				'message' => JText::_('ks_smmhunter_error_connect')
			);
			$response = json_encode($response);
		}
		
		$response = json_decode($response);
		if ($response->status == 'success')
		{
			$params = new stdClass();
			$params->login = $response->result->login;
			$params->password = $response->result->password;
			$params->smmhunter_code = $response->result->smmhunter_code;
			$params = json_encode($params);
			
			$query = $db->getQuery(true);
			$query
				->update('#__extensions')
				->set('params = '.$db->quote($params))
				->where('type = '.$db->quote('plugin'))
				->where('folder = '.$db->quote('system'))
				->where('element = '.$db->quote('smmhunter'))
			;
			$db->setQuery($query);
			$db->query();			
		}
		
		$response = json_encode($response);
		
		return $response;
	}

}
