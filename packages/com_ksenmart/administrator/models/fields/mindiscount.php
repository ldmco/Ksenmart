<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldMindiscount extends JFormField {
	protected $type = 'Mindiscount';
	
	public function getInput() {
		if (count($this->value) == 0 || $this->value == '') {
			$this->value = array(
				'mindifprice' => 0,
				'type' => 0
			);
		}
		
		$currencies = KSMPrice::getCurrencies();
		$currency = KSMPrice::_getDefaultCurrency();
		$currency = $currencies[$currency];
		$html = '';
		$html .= '<label class="inputname" style="width: 185px;">' . JText::_('KSM_SHOPDISCOUNTS_PURCHASE_PRICE_LABEL') . '</label>';
		$html .= '<input style="float:left;margin-right:10px;width:70px;" type="text" class="inputbox_205" name="' . $this->name . '[mindifprice]" value="' . $this->value['mindifprice'] . '">';
		$html .= '<select class="sel" style="width: 80px;" name="' . $this->name . '[type]">';
		$html .= '	<option value="0" ' . ($this->value['type'] == 0?'selected':'') . '>%</option>';
		$html .= '	<option value="1" ' . ($this->value['type'] == 1?'selected':'') . '>' . $currency->code . '</option>';
		$html .= '</select>';
		
		return $html;
	}
}