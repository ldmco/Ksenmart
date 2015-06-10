<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldOrderPayment extends JFormField {
	
	protected $type = 'OrderPayment';
	
	public function getInput() {
		
		$db = JFactory::getDbo();
		$region_id = $this->form->getValue('region_id');
		$payments = array();
		$payment_selected = 0;
		$html = '';
		
		if (!empty($region_id)) {
			$query = $db->getQuery(true);
			$query->select('id as value,title as text,regions')->from('#__ksenmart_payments')->where('published=1')->order('ordering');
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			
			foreach ($rows as $row) {
				$row->regions = json_decode($row->regions, true);
				
				foreach ($row->regions as $country) {
					if (in_array($region_id, $country)) {
						$payment_selected = $row->value == $this->value ? $row->value : $payment_selected;
						$payments[] = $row;
					}
				}
			}
		}
		$this->form->setValue('payment_id', null, $payment_selected);
		if (count($payments)) {
			$emptyvalue = new stdClass();
			$emptyvalue->value = 0;
			$emptyvalue->text = JText::_('ksm_payments_choose_payment');
			array_unshift($payments, $emptyvalue);
			$html = JHTML::_('select.genericlist', $payments, $this->name, array(
				'class' => "sel",
				'style' => 'width:180px;'
			) , 'value', 'text', $this->value);
		} else {
			$html.= '<label class="inputname" style="width:auto;">' . JText::_('ksm_orders_order_no_payments') . '</label>';
			$html.= '<input type="hidden" id="jformshipping_id" name="' . $this->name . '" value="0">';
		}
		
		
		return $html;
	}
}