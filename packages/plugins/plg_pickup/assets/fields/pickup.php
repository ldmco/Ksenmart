<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JFormFieldPickup extends JFormField
{

	protected $type = 'Pickup';

	public function getInput()
	{
		$db          = JFactory::getDbo();
		$shipping_id = $this->form->getValue('shipping_id');
		$pickups     = array();
		$html        = '';

		if (empty($shipping_id)) return $html;
		$query = $db->getQuery(true);
		$query->select('params')
			->from('#__ksenmart_shippings')
			->where('id=' . (int) $shipping_id)
			->where('type=' . $db->q('pickup'));
		$db->setQuery($query);
		$params = $db->loadResult();
		if (empty($params)) return $html;
		$params = json_decode($params, true);
		foreach ($params as $param)
		{
			$new_param = new stdClass();
			$new_param->value = $param['id'];
			$new_param->text = $param['title'];
			$pickups[] = $new_param;
		}

		if (count($pickups))
		{
			$emptyvalue        = new stdClass();
			$emptyvalue->value = -1;
			$emptyvalue->text  = JText::_('ksm_shippings_choose_pickup');
			array_unshift($pickups, $emptyvalue);
			$html = JHTML::_('select.genericlist', $pickups, $this->name, array(
				'class' => "sel",
				'style' => 'width:180px;'
			), 'value', 'text', $this->value);
		}

		return $html;
	}
}
