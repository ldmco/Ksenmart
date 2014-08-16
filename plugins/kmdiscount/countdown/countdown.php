<?php
defined ('_JEXEC') or die('Restricted access');

if (!class_exists ('KMDiscountPlugin')) {
	require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_ksenmart'.DS.'classes'.DS.'kmdiscountplugin.php');
}

class plgKMDiscountCountdown extends KMDiscountPlugin {

	var $_params=array('first_value'=>0,'type'=>1,'period_value'=>0,'period_type'=>0,'last_value'=>0,'clear_time'=>10);

	function __construct (& $subject, $config) {
		parent::__construct ($subject, $config);
	}
	
	function onDisplayParamsForm($name='',$params=null)
	{
		if ($name!=$this->_name)
			return;
		if (empty($params))	
			$params=$this->_params;
		$currency_code=$this->getDefaultCurrencyCode();
		$html='';
		$html.='<div class="set">';
		$html.='	<h3 class="headname">'.JText::_('ksm_discount_algorithm').'</h3>';
		$html.='	<div class="row">';
		$html.='		<label class="inputname">'.JText::_('ksm_discount_countdown_first_value').'</label>';
		$html.='		<input type="text" class="inputbox" name="jform[params][first_value]" value="'.$params['first_value'].'">';	
		$html.='		<select class="sel" name="jform[params][type]">';
		$html.='			<option value="0" '.($params['type']==0?'selected':'').'>%</option>';
		$html.='			<option value="1" '.($params['type']==1?'selected':'').'>'.$currency_code.'</option>';
		$html.='		</select>';
		$html.='	</div>';
		$html.='	<div class="row">';
		$html.='		<label class="inputname">'.JText::_('ksm_discount_countdown_change_on').'</label>';
		$html.='		<input type="text" class="inputbox" name="jform[params][period_value]" value="'.$params['period_value'].'">';	
		$html.='		<span class="two">'.JText::_('ksm_discount_countdown_period').'</span>';	
		$html.='		<select class="sel" name="jform[params][period_type]">';
		$html.='			<option value="0" '.($params['period_type']==0?'selected':'').'>'.JText::_('ksm_discount_countdown_period_minute').'</option>';
		$html.='			<option value="1" '.($params['period_type']==1?'selected':'').'>'.JText::_('ksm_discount_countdown_period_hour').'</option>';
		$html.='			<option value="2" '.($params['period_type']==2?'selected':'').'>'.JText::_('ksm_discount_countdown_period_day').'</option>';
		$html.='		</select>';		
		$html.='	</div>';
		$html.='	<div class="row">';
		$html.='		<label class="inputname">'.JText::_('ksm_discount_countdown_last_value').'</label>';
		$html.='		<input type="text" class="inputbox" name="jform[params][last_value]" value="'.$params['last_value'].'">';	
		$html.='	</div>';
		$html.='	<div class="row">';
		$html.='		<label class="inputname">'.JText::_('ksm_discount_countdown_clear_time').'</label>';
		$html.='		<input type="text" class="inputbox" name="jform[params][clear_time]" value="'.$params['clear_time'].'">';	
		$html.='		<span class="two">'.JText::_('ksm_discount_countdown_clear_time_minute').'</span>';	
		$html.='	</div>';		
		$html.='</div>';
		return $html;
	}
	
	function onBeforeStartComponent()
	{	
		$db=JFactory::getDBO();
		$query=$db->getQuery(true);
		$query->select('id,params')->from('#__ksenmart_discounts')->where('type='.$db->quote($this->_name))->where('enabled=1');
		$db->setQuery($query);
		$discounts=$db->loadObjectList();
		foreach($discounts as $discount)
		{
			$return=$this->onCheckDiscountDate($discount->id);
			if (!$return)
				continue;		
			$return=$this->onCheckDiscountCountry($discount->id);
			if (!$return)
				continue;		
			$return=$this->onCheckDiscountUserGroups($discount->id);
			if (!$return)
				continue;					
			$return=$this->onCheckDiscountActions($discount->id);
			if ($return==1)
				continue;
			$session=JFactory::getSession();
			$user_last_activity=$session->get('com_ksenmart.user_last_activity',null);
			$start_time=$session->get('com_ksenmart.countdown_start_time_'.$discount->id,null);
			$user_sitevisits=$session->get('com_ksenmart.user_sitevisits',0);
			$discount->params=json_decode($discount->params,true);			
			if ((!empty($discount->params['clear_time']) && ($user_last_activity+$discount->params['clear_time']*60<time())) || empty($user_last_activity) || empty($start_time))
			{	
				$session->set('com_ksenmart.user_last_visit',time());
				$session->set('com_ksenmart.countdown_start_time_'.$discount->id,time());
			}
		}
		parent::onBeforeStartComponent();
	}	

	function onSetCartDiscount($cart=null,$discount_id=null)
	{
		if (empty($cart))
			return false;
		if (empty($discount_id))
			return false;	
		$session=JFactory::getSession();
		$start_time=$session->get('com_ksenmart.countdown_start_time_'.$discount_id,null);	
		$end_time=$session->get('com_ksenmart.countdown_fixed_time_'.$discount_id,time());	
		if (empty($start_time))
			return false;
		$db=JFactory::getDBO();	
		$discount_set_value=0;
		foreach($cart->items as &$item)
		{
			$query=$db->getQuery(true);
			$query->select('params,sum')->from('#__ksenmart_discounts')->where('type='.$db->quote($this->_name))->where('id='.$discount_id)->where('enabled=1');
			$db->setQuery($query);
			$discount=$db->loadObject();
			if (empty($discount))
				return false;
			$discount->params=json_decode($discount->params,true);			
			$discount->discount_value=0;
			if (!isset($item->discounts))
				$item->discounts=array();
			$return=$this->onCheckDiscountCategories($discount_id,$item->product_id);
			if (!$return)
				continue;		
			$return=$this->onCheckDiscountManufacturers($discount_id,$item->product_id);
			if (!$return)
				continue;
			$time=0;	
			switch($discount->params['period_type'])
			{
				case '0':
					$time=floor(($end_time-$start_time)/60);
					break;
				case '1':
					$time=floor(($end_time-$start_time)/3600);
					break;
				case '2':
					$time=floor(($end_time-$start_time)/86400);
					break;					
			}
			$discount->params['value']=$discount->params['first_value']-$discount->params['period_value']*$time;
			if ($discount->params['value']<$discount->params['last_value'])
				$discount->params['value']=$discount->params['last_value'];
			$item->discounts[$discount_id]=$this->calculateItemDiscount($item,$discount,$discount_set_value,$discount->params);
		}
		return true;
	}
	
	function onSetOrderDiscount($order=null,$discount_id=null,$params=null)
	{
		if (empty($order))
			return false;
		if (empty($discount_id))
			return false;	
		if (empty($params))
			return false;				
		$db=JFactory::getDBO();	
		$discount_set_value=0;
		foreach($order->items as &$item)
		{
			$query=$db->getQuery(true);
			$query->select('sum')->from('#__ksenmart_discounts')->where('type='.$db->quote($this->_name))->where('id='.$discount_id)->where('enabled=1');
			$db->setQuery($query);
			$discount=$db->loadObject();
			if (empty($discount))
				return false;
			$discount->discount_value=0;
			if (!isset($item->discounts))
				$item->discounts=array();
			$return=$this->onCheckDiscountCategories($discount_id,$item->product_id);
			if (!$return)
				continue;		
			$return=$this->onCheckDiscountManufacturers($discount_id,$item->product_id);
			if (!$return)
				continue;
			$item->discounts[$discount_id]=$this->calculateItemDiscount($item,$discount,$discount_set_value,$params);
		}
		return true;
	}	
	
	function onGetDiscountContent($discount_id=null)
	{
		if (empty($discount_id))
			return;
		$db=JFactory::getDBO();	
		$session=JFactory::getSession();
		$query=$db->getQuery(true);
		$query->select('content')->from('#__ksenmart_discounts')->where('type='.$db->quote($this->_name))->where('id='.$discount_id)->where('enabled=1');
		$db->setQuery($query);
		$content=$db->loadResult();
		if (empty($content))
			return;	
		return $content;
	}

}