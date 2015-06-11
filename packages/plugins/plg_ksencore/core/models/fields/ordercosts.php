<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldOrderCosts extends JFormField {

	protected $type = 'OrderCosts';
	
	public function getInput(){
		$html='';
		
		$html.='<div class="row">';
		$html.='	<label class="inputname">'.JText::_('ksm_orders_order_products_cost').'</label>';
		$html.='	<label class="inputname">'.$this->value['cost_val'].'</label>';
		$html.='</div>';
		$html.='<div class="row">';
		$html.='	<label class="inputname">'.JText::_('ksm_orders_order_discount_cost').'</label>';
		$html.='	<label class="inputname">'.$this->value['discount_cost_val'].'</label>';
		$html.='</div>';		
		$html.='<div class="row">';
		$html.='	<label class="inputname">'.JText::_('ksm_orders_order_shipping_cost').'</label>';
		$html.='	<label class="inputname">'.$this->value['shipping_cost_val'].'</label>';
		$html.='</div>';	
		$html.='<div class="row">';
		$html.='	<label class="inputname"><b>'.JText::_('ksm_orders_order_total_cost').'</b></label>';
		$html.='	<label class="inputname"><b>'.$this->value['total_cost_val'].'</b></label>';
		$html.='</div>';		
		return $html;
	}
}