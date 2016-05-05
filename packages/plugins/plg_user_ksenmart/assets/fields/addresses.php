<?php 
defined('_JEXEC') or die;

class JFormFieldAddresses extends JFormField 
{

	protected $type = 'Addresses';
	
	public function getInput()
	{
		JText::script('PLG_USER_KSENMART_ADDRESSES_CITY_TXT');
		JText::script('PLG_USER_KSENMART_ADDRESSES_STREET_TXT');
		JText::script('PLG_USER_KSENMART_ADDRESSES_HOUSE_TXT');
		JText::script('PLG_USER_KSENMART_ADDRESSES_ENTRANCE_TXT');
		JText::script('PLG_USER_KSENMART_ADDRESSES_FLOOR_TXT');
		JText::script('PLG_USER_KSENMART_ADDRESSES_FLAT_TXT');
		
		foreach($this->value as &$address)
		{
			$address->string = self::getAddressString($address);
			$address->default_checked = $address->default ? ' checked="checked"' : '';
		}
		
		$view = new stdClass();
		$view->addresses = $this->value;		
		$view->name = $this->name;		
		$html = KSSystem::loadPluginTemplate('ksenmart', 'user', $view, 'addresses_edit');
		
		return $html;
	}
	
	public function getAddressString($address)
	{
		$addr_parts = array();
		$string = '';
		
		if (!empty($address->zip))
		{
			$addr_parts[] = $address->zip;
		}				
		if (!empty($address->city))
		{
			$addr_parts[] = JText::sprintf('PLG_USER_KSENMART_ADDRESSES_CITY_TXT', $address->city);
		}
		if (!empty($address->street))
		{
			$addr_parts[] = JText::sprintf('PLG_USER_KSENMART_ADDRESSES_STREET_TXT', $address->street);
		}
		if (!empty($address->house))
		{
			$addr_parts[] = JText::sprintf('PLG_USER_KSENMART_ADDRESSES_HOUSE_TXT', $address->house);
		}
		if (!empty($address->entrance))
		{
			$addr_parts[] = JText::sprintf('PLG_USER_KSENMART_ADDRESSES_ENTRANCE_TXT', $address->entrance);
		}
		if (!empty($address->floor))
		{
			$addr_parts[] = JText::sprintf('PLG_USER_KSENMART_ADDRESSES_FLOOR_TXT', $address->floor);
		}
		if (!empty($address->flat))
		{
			$addr_parts[] = JText::sprintf('PLG_USER_KSENMART_ADDRESSES_FLAT_TXT', $address->flat);
		}
		$string = implode(', ', $addr_parts);

		return $string;
	}	
	
}