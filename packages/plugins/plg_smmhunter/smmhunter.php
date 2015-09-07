<?php 
defined('_JEXEC') or die('Restricted access');

if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

if (!class_exists('KMPlugin')) 
{
	require (JPATH_ROOT . '/administrator/components/com_ksenmart/classes/kmplugin.php');
}

class plgSystemSmmhunter extends KMPlugin 
{
	
	public function onAfterRender()
	{
		if (JFactory::getApplication()->isAdmin())
		{
			return true;
		}	
		
		$smmhunter_code = $this->params->get('smmhunter_code', '');
		
		if (empty($smmhunter_code))
		{
			return true;
		}
		
		$code = '
		<script src="'.JURI::root().'plugins/system/smmhunter/assets/js/smmhunter-track.js"></script>	
		<script src="http://smm-hunter.ru/smm/?task=track.script&token='.$smmhunter_code.'"></script>	
		</body>
		';
		
		$output = JResponse::getBody();
		$output = str_replace('</body>', $code, $output);
		JResponse::setBody($output);
		
		return true;
	}
	
	public function onAjaxSmmhunterSaveuser()
	{
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$session = JFactory::getSession();
		$vk_user_id = $app->input->get('vk_user_id', 0);
		$order_id = $session->get('shop_order_id', 0);
		
		if (!empty($order_id))
		{
			$query = $db->getQuery(true);
			$query
				->update('#__ksenmart_orders')
				->set('vk_user_id = '.$db->quote($vk_user_id))
				->where('id = '.$order_id)
			;
			$db->setQuery($query);
			$db->query();
		}
		
		$session->set('vk_user_id', $vk_user_id);
		
		$app->close();
	}
	
	public function onAfterExecuteKSMCartAddtocart($model, $result = null)
	{
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$session = JFactory::getSession();
		$vk_user_id = $session->get('vk_user_id', 0);
		$order_id = $session->get('shop_order_id', 0);
		
		$query = $db->getQuery(true);
		$query
			->update('#__ksenmart_orders')
			->set('vk_user_id = '.$db->quote($vk_user_id))
			->where('id = '.$order_id)
		;
		$db->setQuery($query);
		$db->query();
		
		return;	
	}
	
    public function onAfterDisplayAdminKSMOrdersOrder_Info(&$view, &$tpl, &$html)
	{
		if (empty($view->order->vk_user_id))
		{
			return;
		}
		
        $html .= '<div calss="row" style="clear: both;">';
        $html .=    '<label class="inputname">'.JText::_('ks_smmhunter_order_vk_link_label').'</label>';
        $html .=    '<label class="inputname"><a target="_blank" href="http://vk.com/id'.$view->order->vk_user_id.'">'.JText::_('ks_smmhunter_order_vk_link_text').'</a></label>';
        $html .= '</div>';

        return true;
    }

}