<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if (!class_exists('KMDiscountPlugin')) {
	require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmdiscountplugin.php');
}

class plgKMDiscountCoupons extends KMDiscountPlugin {
	
	var $_params = array(
		'value' => 0,
		'type' => 1,
		'repeated' => 0,
		'coupons' => array()
	);
	
	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
	}
	
	function onDisplayParamsForm($name = '', $params = null, $discount_id = null) {
		if ($name != $this->_name) 
		return;
		if (empty($params)) $params = $this->_params;
		if (!empty($discount_id)) {
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('*')->from('#__ksenmart_discount_coupons')->where('discount_id=' . $discount_id);
			$db->setQuery($query);
			$params['coupons'] = $db->loadObjectList('id');
		}
		$currency_code = $this->getDefaultCurrencyCode();
		$html = '';
		$html.= '<style>';
		$html.= '.coupon-label {width: 100%!important;float: none!important;}';
		$html.= '.coupons-ul li.active .line {background-color: #ECF0BE;}';
		$html.= '.coupons-ul li .line font {width:225px;display:inline-block;}';
		$html.= '.ksenmart-coupons .module-head label {padding-left:10px;}';
		$html.= '</style>';
		$html.= '<div class="set">';
		$html.= '	<h3 class="headname">' . JText::_('ksm_discount_algorithm') . '</h3>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_discount_coupons_value') . '</label>';
		$html.= '		<input type="text" class="inputbox" name="jform[params][value]" value="' . $params['value'] . '">';
		$html.= '		<select class="sel" name="jform[params][type]">';
		$html.= '			<option value="0" ' . ($params['type'] == 0 ? 'selected' : '') . '>%</option>';
		$html.= '			<option value="1" ' . ($params['type'] == 1 ? 'selected' : '') . '>' . $currency_code . '</option>';
		$html.= '		</select>';
		$html.= '	</div>';
		$html.= '	<div class="row">';
		$html.= '		<label class="inputname">' . JText::_('ksm_discount_coupons_repeated') . '</label>';
		$html.= '		<input type="radio" class="checkbox" name="jform[params][repeated]" value="0" ' . ($params['repeated'] == 0 ? 'checked' : '') . '>' . JText::_('jno');
		$html.= '		&nbsp;&nbsp;';
		$html.= '		<input type="radio" class="checkbox" name="jform[params][repeated]" value="1" ' . ($params['repeated'] == 1 ? 'checked' : '') . '>' . JText::_('jyes');
		$html.= '	</div>';
		$html.= '</div>';
		$html.= '<div class="ksenmart-coupons slide_module">';
		$html.= '	<div class="module-head">';
		$html.= '		<label>' . JText::_('ksm_discount_coupons_list') . '</label>';
		$html.= '		<a class="show_module_content" onclick="shSlideModuleContent(this);return false;"></a>';
		$html.= '	</div>';
		$html.= '	<div class="module-content">';
		$html.= '		<div class="lists">';
		$html.= '			<div class="row">';
		$html.= '		 		<ul class="coupons-ul">';
		if (isset($params['coupons'])) {
			
			foreach ($params['coupons'] as $coupon) {
				$html.= '		 	<li class="' . ($coupon->published == 1 ? 'active' : '') . '">';
				$html.= '		 		<div class="line">';
				$html.= '		 			<label class="inputname coupon-label"><font>' . JText::_('ksm_discount_coupons_coupon') . $coupon->code . '</font>' . JText::_('ksm_discount_coupons_used') . $coupon->used . '<input type="checkbox" onclick="setActive(this,this.parentNode.parentNode.parentNode);" name="coupons[' . $coupon->id . '][published]" value="1" ' . ($coupon->published == 1 ? 'checked' : '') . '></label>';
				$html.= '		 			<input type="hidden" name="coupons[' . $coupon->id . '][id]" value="' . $coupon->id . '">';
				$html.= '		 		</div>';
				$html.= '		 		<a href="#" onclick="removeCoupon(this);return false;"></a>';
				$html.= '			</li>';
			}
		}
		$html.= '	 			</ul>';
		$html.= '			</div>';
		$html.= '			<div class="row">';
		$html.= '			 	<a href="#" class="add" id="add-coupon" onclick="addCoupon();return false;">' . JText::_('ksm_add') . '</a>';
		$html.= '			</div>';
		$html.= '		</div>';
		$html.= '	</div>';
		$html.= '</div>';
		$html.= '<script>';
		$html.= 'function addCoupon()';
		$html.= '{';
		$html.= '	var html="<li><div class=\'line\'><label class=\'inputname\'>' . JText::_('ksm_discount_coupons_print_code') . '</label><input type=\'text\' name=\'new_coupons[]\' class=\'inputbox\'><p>&nbsp;</p></div><a href=\'#\' onclick=\'removeCoupon(this);return false;\'></a></li>";';
		$html.= '	jQuery(".coupons-ul").append(html);';
		$html.= '}';
		$html.= 'function removeCoupon(obj)';
		$html.= '{';
		$html.= '	jQuery(obj).parents("li").remove();';
		$html.= '}';
		$html.= '</script>';
		
		return $html;
	}
	
	function onAfterExecuteKSMDiscountsSavediscount($model, $return) {
		$discount_id = $return['id'];
		$db = JFactory::getDBO();
		if (empty($discount_id)) 
		return false;
		$coupons = JRequest::getVar('coupons', array());
		$in = array();
		
		foreach ($coupons as $coupon_id => $coupon) {
			$published = isset($coupon['published']) ? 1 : 0;
			$query = $db->getQuery(true);
			$query->update('#__ksenmart_discount_coupons')->set('published=' . $published)->where('id=' . $coupon_id);
			$db->setQuery($query);
			$db->query();
			$in[] = $coupon_id;
		}
		$query = $db->getQuery(true);
		$query->delete('#__ksenmart_discount_coupons');
		if (count($in)) {
			$query->where('id not in (' . implode(',', $in) . ')');
		}
		$query->where('discount_id=' . $discount_id);
		$db->setQuery($query);
		$db->query();
		$new_coupons = JRequest::getVar('new_coupons', array());
		
		foreach ($new_coupons as $new_coupon) {
			$query = $db->getQuery(true);
			$query->insert('#__ksenmart_discount_coupons')->columns(array(
				'discount_id',
				'code',
				'published'
			))->values($discount_id . ',' . $db->quote($new_coupon) . ',1');
			$db->setQuery($query);
			$db->query();
		}
		
		return true;
	}
	
	function onAfterDisplayKSMCartDefault_content($view, &$tpl = null, &$html) {
		if (!self::canDisplay())
			return false;
			
		$session = JFactory::getSession();
		$coupon_id = $session->get('ksenmart.coupon_id', null);
		if (!empty($coupon_id)) {
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('code')->from('#__ksenmart_discount_coupons')->where('published=1')->where('id=' . $coupon_id);
			$db->setQuery($query);
			$code = $db->loadResult();
			$html.= '<form id="km-coupon-form" method="post">';
			$html.= '	<input type="hidden" name="task" value="unset_discount_coupon">';
			$html.= '</form>';
		} else {
			$html.= '<form id="km-coupon-form" method="post">';
			$html.= '	<input type="hidden" name="discount_code" value="" />';
			$html.= '	<input type="hidden" name="task" value="set_discount_coupon">';
			$html.= '</form>';
		}
	}
	
	function onBeforeDisplayKSMCartDefault_shipping($view, &$tpl = null, &$html) {
		if (!self::canDisplay())
			return false;
			
		$document = JFactory::getDocument();
		$session = JFactory::getSession();
		$coupon_id = $session->get('ksenmart.coupon_id', null);
		if (!empty($coupon_id)) {
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('code')->from('#__ksenmart_discount_coupons')->where('published=1')->where('id=' . $coupon_id);
			$db->setQuery($query);
			$view->code = $db->loadResult();
			$html .= KSSystem::loadPluginTemplate($this->_name, $this->_type, $view, 'unset');
			
			$script = '
			jQuery(document).ready(function(){
			
				jQuery("#cart").on("click",".km-coupons .btn",function(){
					jQuery("#km-coupon-form").submit();
				});
			
			});
			';
			$document->addScriptDeclaration($script);
		} else {
			$html .= KSSystem::loadPluginTemplate($this->_name, $this->_type, $view, 'set');
			
			$script = '
			jQuery(document).ready(function(){
			
				jQuery("#cart").on("click",".km-coupons .btn",function(){
					var discount_code=jQuery(".km-coupons input[name=\'discount_code\']").val();
					jQuery("#km-coupon-form input[name=\'discount_code\']").val(discount_code);
					jQuery("#km-coupon-form").submit();
				});
			
			});
			';
			$document->addScriptDeclaration($script);
		}
	}
	
	function onAfterDisplayAdminKSMOrdersOrder_info($view, &$tpl = null, &$html) {
		if (empty($view->order->discounts)) 
		return;
		$view->order->discounts = json_decode($view->order->discounts, true);
		if (!is_array($view->order->discounts)) 
		return;
		
		foreach ($view->order->discounts as $discount) {
			if (isset($discount['coupon_id'])) {
				$db = JFactory::getDBO();
				$query = $db->getQuery(true);
				$query->select('code')->from('#__ksenmart_discount_coupons')->where('id=' . $discount['coupon_id']);
				$db->setQuery($query);
				$code = $db->loadResult();
				if (!empty($code)) {
					$html.= '<div class="row">';
					$html.= '	<label class="inputname">' . JText::_('ksm_discount_coupons_coupon') . '</label>';
					$html.= '	<label class="inputname">' . $code . '</label>';
					$html.= '</div>';
					
					return $html;
				}
			}
		}
		
		return;
	}
	
	function onBeforeStartComponent() {
		$session = JFactory::getSession();
		$db = JFactory::getDBO();
		$task = JRequest::getVar('task', null);
		if ($task == 'set_discount_coupon') {
			$code = JRequest::getVar('discount_code', null);
			$query = $db->getQuery(true);
			$query->select('kdc.discount_id,kdc.id,kd.params')->from('#__ksenmart_discount_coupons as kdc')->innerjoin('#__ksenmart_discounts as kd on kd.id=kdc.discount_id')->where('kdc.code=' . $db->quote($code))->where('kdc.published=1')->where('kd.enabled=1');
			$db->setQuery($query);
			$coupon = $db->loadObject();
			if (isset($coupon->discount_id) && !empty($coupon->discount_id)) {
				$coupon->params = json_decode($coupon->params, true);
				$return = $this->onCheckDiscountDate($coupon->discount_id);
				if ($return) {
					$return = $this->onCheckDiscountCountry($coupon->discount_id);
					if ($return) {
						$return = $this->onCheckDiscountUserGroups($coupon->discount_id);
						if ($return) {
							$return = $this->onCheckDiscountActions($coupon->discount_id);
							if ($return != 1) {
								$session->set('ksenmart.coupon_id', $coupon->id);
								JRequest::setVar('discount_code', null);
								JRequest::setVar('task', null);
							}
						}
					}
				}
			}
		} elseif ($task == 'unset_discount_coupon') {
			$session->set('ksenmart.coupon_id', null);
		}
		$query = $db->getQuery(true);
		$query->select('id,params')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('enabled=1');
		$db->setQuery($query);
		$discounts = $db->loadObjectList();
		
		foreach ($discounts as $discount) {
			$discount->params = json_decode($discount->params, true);
			$return = $this->onCheckDiscountDate($discount->id);
			if (!$return) 
			continue;
			$return = $this->onCheckDiscountCountry($discount->id);
			if (!$return) 
			continue;
			$return = $this->onCheckDiscountUserGroups($discount->id);
			if (!$return) 
			continue;
			$return = $this->onCheckDiscountActions($discount->id);
			if ($return == 1) 
			continue;
			$this->onSendDiscountEmail($discount->id);
		}
	}
	
	function onAfterExecuteKSMCartGetcart($model, $cart = null) {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id,params')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('enabled=1');
		$db->setQuery($query);
		$discounts = $db->loadObjectList();
		
		foreach ($discounts as $discount) {
			$discount->params = json_decode($discount->params, true);
			$return = $this->onCheckDiscountDate($discount->id);
			if (!$return) 
			continue;
			$return = $this->onCheckDiscountCountry($discount->id);
			if (!$return) 
			continue;
			$return = $this->onCheckDiscountUserGroups($discount->id);
			if (!$return) 
			continue;
			$return = $this->onCheckDiscountActions($discount->id);
			if ($return == 1) 
			continue;
			$this->onSetCartDiscount($cart, $discount->id);
		}
	}
	
	function onSetCartDiscount($cart = null, $discount_id = null) {
		if (empty($cart)) 
		return false;
		if (empty($discount_id)) 
		return false;
		$session = JFactory::getSession();
		$coupon_id = $session->get('ksenmart.coupon_id', null);
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id')->from('#__ksenmart_discount_coupons')->where('id=' . (int)$coupon_id)->where('discount_id=' . $discount_id);
		$db->setQuery($query);
		$coupon_id = $db->loadResult();
		if (empty($coupon_id)) 
		return false;
		$discount_set_value = 0;
		
		foreach ($cart->items as & $item) {
			$query = $db->getQuery(true);
			$query->select('params,sum')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('id=' . $discount_id)->where('enabled=1');
			$db->setQuery($query);
			$discount = $db->loadObject();
			if (empty($discount)) 
			return false;
			$discount->params = json_decode($discount->params, true);
			$discount->params['coupon_id'] = $coupon_id;
			$discount->discount_value = 0;
			if (!isset($item->discounts)) $item->discounts = array();
			$return = $this->onCheckDiscountCategories($discount_id, $item->product_id);
			if (!$return) 
			continue;
			$return = $this->onCheckDiscountManufacturers($discount_id, $item->product_id);
			if (!$return) 
			continue;
			$item->discounts[$discount_id] = $this->calculateItemDiscount($item, $discount, $discount_set_value, $discount->params);
		}
		
		return true;
	}
	
	function onAfterExecuteKSMCartCloseorder($model) {
		$session = JFactory::getSession();
		$coupon_id = $session->get('ksenmart.coupon_id', null);
		if (!empty($coupon_id)) {
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('kd.params')->from('#__ksenmart_discount_coupons as kdc')->innerjoin('#__ksenmart_discounts as kd on kd.id=kdc.discount_id')->where('kdc.id=' . $coupon_id)->where('kdc.published=1')->where('kd.enabled=1');
			$db->setQuery($query);
			$params = $db->loadResult();
			if (!empty($params)) {
				$params = json_decode($params, true);
				$query = $db->getQuery(true);
				$query->update('#__ksenmart_discount_coupons')->set('used=used+1');
				if ($params['repeated'] == 0) $query->set('published=0');
				$query->where('id=' . $coupon_id);
				$db->setQuery($query);
				$db->query();
			}
			$session->set('ksenmart.coupon_id', null);
		}
	}
	
	function onSetOrderDiscount($order = null, $discount_id = null, $params = null) {
		if (empty($order)) 
		return false;
		if (empty($discount_id)) 
		return false;
		if (empty($params)) 
		return false;
		$db = JFactory::getDBO();
		$discount_set_value = 0;
		
		foreach ($order->items as & $item) {
			$query = $db->getQuery(true);
			$query->select('sum')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('id=' . $discount_id)->where('enabled=1');
			$db->setQuery($query);
			$discount = $db->loadObject();
			if (empty($discount)) 
			return false;
			$discount->discount_value = 0;
			if (!isset($item->discounts)) $item->discounts = array();
			$return = $this->onCheckDiscountCategories($discount_id, $item->product_id);
			if (!$return) 
			continue;
			$return = $this->onCheckDiscountManufacturers($discount_id, $item->product_id);
			if (!$return) 
			continue;
			$item->discounts[$discount_id] = $this->calculateItemDiscount($item, $discount, $discount_set_value, $params);
		}
		
		return true;
	}
	
	function onGetDiscountContent($discount_id = null) {
		if (empty($discount_id)) 
		return;
		$db = JFactory::getDBO();
		$session = JFactory::getSession();
		$created = $session->get('com_ksenmart.created_discount_' . $discount_id, null);
		$query = $db->getQuery(true);
		$query->select('content')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('id=' . $discount_id)->where('enabled=1');
		$db->setQuery($query);
		$content = $db->loadResult();
		if (empty($content)) 
		return;
		$query = $db->getQuery(true);
		$query->select('id')->from('#__ksenmart_discount_coupons')->where('code=' . $db->quote($created))->where('published=1');
		$db->setQuery($query);
		$coupon_id = $db->loadResult();
		if (!empty($created) && empty($coupon_id)) 
		return;
		if (!empty($created)) $content = str_replace('{code}', $created, $content);
		
		return $content;
	}
	
	function canDisplay(){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id,params')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('enabled=1');
		$db->setQuery($query);
		$discounts = $db->loadObjectList();
		
		$flag = false;
		foreach ($discounts as $discount) {
			if ($this->onCheckDiscountCountry($discount->id))
				$flag = true;
		}
		
		return $flag;
	}
	
}