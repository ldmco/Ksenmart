<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if (!class_exists('KSMShippingPlugin')) {
    require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmshippingplugin.php');
}

class plgKMShippingCourierMoscow extends KSMShippingPlugin {
    
    public $_params = array('city' => 0, 'area' => 0);
    
    public function onDisplayParamsForm($name = '', $params = null) {
        if ($name != $this->_name) return;
        if (empty($params)) $params = $this->_params;
        $currency_code = $this->getDefaultCurrencyCode();
        $html = '';
        $html.= '<div class="set">';
        $html.= '	<h3 class="headname">' . JText::_('ksm_shipping_algorithm') . '</h3>';
        $html.= '	<div class="row">';
        $html.= '		<label class="inputname">' . JText::_('ksm_shipping_couriermoscow_city_price') . '</label>';
        $html.= '		<input type="text" class="inputbox" name="jform[params][city]" value="' . $params['city'] . '">';
        $html.= '	</div>';
        $html.= '	<div class="row">';
        $html.= '		<label class="inputname">' . JText::_('ksm_shipping_couriermoscow_area_price') . '</label>';
        $html.= '		<input type="text" class="inputbox" name="jform[params][area]" value="' . $params['area'] . '">';
        $html.= '	</div>';
        $html.= '</div>';
        $html.= '
		<script>
		jQuery(document).ready(function(){
			removeCountry(1);
			addCountry(1);
			addRegion(1);
			addRegion(35);
		});	
		</script>
		<style>
		.ksm-slidemodule-countries a,.ksm-slidemodule-regions a, .ksm-slidemodule-countries i,.ksm-slidemodule-regions i {display:none;}
		</style>
		';
        
        return $html;
    }
    
    function onAfterExecuteKSMCartGetcart($model, $cart = null) {
        if (empty($cart)) return;
        if (empty($cart->shipping_id)) return;
        $app = JFactory::getApplication();
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id,params,regions')->from('#__ksenmart_shippings')->where('id=' . $cart->shipping_id)->where('type=' . $db->quote($this->_name))->where('published=1');
        $db->setQuery($query);
        $shipping = $db->loadObject();
        if (empty($shipping)) return;
        if (empty($cart->region_id)) return;
        if (!$this->checkRegion($shipping->regions, $cart->region_id)) return;
        $shipping->params = json_decode($shipping->params, true);
        $distance = (int)$app->getUserStateFromRequest('com_ksenmart.distance', 'distance', 0);
        $cart->shipping_sum = $shipping->params['city'] + $shipping->params['area'] * $distance;
        $cart->shipping_sum_val = KSMPrice::showPriceWithTransform($cart->shipping_sum);
        $cart->total_sum+= $cart->shipping_sum;
        $cart->total_sum_val = KSMPrice::showPriceWithTransform($cart->total_sum);
        
        return;
    }
    
    public function onAfterDisplayKSMCartDefault_shipping($view, &$tpl = null, &$html) {
        if (empty($view->cart)) return;
        if (empty($view->cart->shipping_id)) return;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('
                s.id,
                s.params,
                s.regions
            ')->from('#__ksenmart_shippings AS s')->where('s.id=' . $view->cart->shipping_id)->where('s.type=' . $db->quote($this->_name))->where('s.published=1');
        $db->setQuery($query);
        $shipping = $db->loadObject();
        if (empty($shipping)) return;
        if (empty($view->cart->region_id)) return;
        if (!$this->checkRegion($shipping->regions, $view->cart->region_id)) return;
        
        $html.= '
		<div class="default_shipping-plugin-renew">
			<script>
			
				KsenmartMap.MoscowParams={
					border: [
						[55.882073,37.726311],
						[55.852343,37.785019],
						[55.827035,37.833771],
						[55.817372,37.839264],
						[55.774048,37.843384],
						[55.733388,37.841324],
						[55.713236,37.838577],
						[55.694625,37.829651],
						[55.674453,37.835144],
						[55.656988,37.840637],
						[55.643011,37.826904],
						[55.605324,37.7603],
						[55.576937,37.692322],
						[55.571491,37.673782],
						[55.57577,37.594131],
						[55.594827,37.514481],
						[55.601825,37.502808],
						[55.644952,37.453369],
						[55.663199,37.432083],
						[55.683765,37.41629],
						[55.692686,37.41011],
						[55.706258,37.392258],
						[55.716725,37.384705],
						[55.745785,37.370285],
						[55.78411,37.369598],
						[55.807319,37.388138],
						[55.834765,37.396377],
						[55.847128,37.392258],
						[55.859874,37.397064],
						[55.871458,37.410797],
						[55.880336,37.43895],
						[55.883038,37.454742],
						[55.88381,37.470535],
						[55.8923,37.498001],
						[55.905031,37.526153],
						[55.909273,37.550873],
						[55.911587,37.581085],
						[55.906188,37.604431],
						[55.898859,37.631897],
						[55.894615,37.677216],
						[55.893843,37.701248],
						[55.888441,37.714981]
					]    
				};		
				
				KsenmartMap.pointClosestToMoscow = function(){
					var polygon = new ymaps.Polygon([KsenmartMap.MoscowParams.border],{}, {opacity:0});
					KsenmartMap.options.ymap.geoObjects.add(polygon);
					var closest = polygon.geometry.getClosest(KsenmartMap.options.ymap_point.geometry.getCoordinates());
					KsenmartMap.options.ymap.geoObjects.remove(polygon);
					return closest;
				};	
				
				KsenmartMap.getDistance = function(){
					var distance=0;
					var polygon = new ymaps.Polygon([KsenmartMap.MoscowParams.border],{}, {opacity:0});
					KsenmartMap.options.ymap.geoObjects.add(polygon);
					var contains = polygon.geometry.contains(KsenmartMap.options.ymap_point.geometry.getCoordinates());
					KsenmartMap.options.ymap.geoObjects.remove(polygon);
					if (!contains)
					{
						var closest=KsenmartMap.pointClosestToMoscow();
						distance=Math.round(closest.distance/1000);	
					}
					return distance;	
				}			
			
				KsenmartMap.afterSetPoint = function(){
					var distance=KsenmartMap.getDistance();
					var data={};
					data["layouts"]={"0":"default_total"};
					data["distance"]=distance;
					KMGetLayouts(data);				
				};

			</script>
		</div>
		';
    }
    
    public function onAfterExecuteKSMOrdersGetorder($model, $order = null) {
        $this->onAfterExecuteHelperKSMOrdersGetOrder($order);
    }
    
    public function onAfterExecuteHelperKSMOrdersGetOrder($order = null) {
        if (empty($order)) return;
        if (empty($order->shipping_id)) return;
        $app = JFactory::getApplication();
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id,params,regions')->from('#__ksenmart_shippings')->where('id=' . $order->shipping_id)->where('type=' . $db->quote($this->_name))->where('published=1');
        $db->setQuery($query);
        $shipping = $db->loadObject();
        if (empty($shipping)) return;
        if (empty($order->region_id)) return;
        if (!$this->checkRegion($shipping->regions, $order->region_id)) return;
        $shipping->params = json_decode($shipping->params, true);
        $distance = (int)$app->getUserStateFromRequest('com_ksenmart.distance', 'distance', 0);
        $order->costs['shipping_cost'] = $shipping->params['city'] + $shipping->params['area'] * $distance;
        $order->costs['shipping_cost_val'] = KSMPrice::showPriceWithTransform($order->costs['shipping_cost']);
        $order->costs['total_cost']+= $order->costs['shipping_cost'];
        $order->costs['total_cost_val'] = KSMPrice::showPriceWithTransform($order->costs['total_cost']);
        
        return;
    }
    
    public function onAfterGetKSMFormInputOrderAddress_fields($form, $field, $html) {
        $region_id = $form->getValue('region_id');
        $shipping_id = $form->getValue('shipping_id');
        
        if (empty($shipping_id)) return;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id,params,regions')->from('#__ksenmart_shippings')->where('id=' . $shipping_id)->where('type=' . $db->quote($this->_name))->where('published=1');
        $db->setQuery($query);
        $shipping = $db->loadObject();
        if (empty($shipping)) return;
        if (empty($region_id)) return;
        if (!$this->checkRegion($shipping->regions, $region_id)) return;
        $html.= '
		<script>
		
			KsenmartMap.MoscowParams={
				border:[
					[55.882073,37.726311],
					[55.852343,37.785019],
					[55.827035,37.833771],
					[55.817372,37.839264],
					[55.774048,37.843384],
					[55.733388,37.841324],
					[55.713236,37.838577],
					[55.694625,37.829651],
					[55.674453,37.835144],
					[55.656988,37.840637],
					[55.643011,37.826904],
					[55.605324,37.7603],
					[55.576937,37.692322],
					[55.571491,37.673782],
					[55.57577,37.594131],
					[55.594827,37.514481],
					[55.601825,37.502808],
					[55.644952,37.453369],
					[55.663199,37.432083],
					[55.683765,37.41629],
					[55.692686,37.41011],
					[55.706258,37.392258],
					[55.716725,37.384705],
					[55.745785,37.370285],
					[55.78411,37.369598],
					[55.807319,37.388138],
					[55.834765,37.396377],
					[55.847128,37.392258],
					[55.859874,37.397064],
					[55.871458,37.410797],
					[55.880336,37.43895],
					[55.883038,37.454742],
					[55.88381,37.470535],
					[55.8923,37.498001],
					[55.905031,37.526153],
					[55.909273,37.550873],
					[55.911587,37.581085],
					[55.906188,37.604431],
					[55.898859,37.631897],
					[55.894615,37.677216],
					[55.893843,37.701248],
					[55.888441,37.714981]
				]    
			};		
			
			KsenmartMap.pointClosestToMoscow = function(){
				var polygon = new ymaps.Polygon([KsenmartMap.MoscowParams.border],{}, {opacity:0});
				KsenmartMap.options.ymap.geoObjects.add(polygon);
				var closest = polygon.geometry.getClosest(KsenmartMap.options.ymap_point.geometry.getCoordinates());
				KsenmartMap.options.ymap.geoObjects.remove(polygon);
				return closest;
			};	
			
			KsenmartMap.getDistance = function(){
				var distance=0;
				var polygon = new ymaps.Polygon([KsenmartMap.MoscowParams.border],{}, {opacity:0});
				KsenmartMap.options.ymap.geoObjects.add(polygon);
				var contains = polygon.geometry.contains(KsenmartMap.options.ymap_point.geometry.getCoordinates());
				KsenmartMap.options.ymap.geoObjects.remove(polygon);
				if (!contains)
				{
					var closest=KsenmartMap.pointClosestToMoscow();
					distance=Math.round(closest.distance/1000);	
				}
				return distance;	
			}			
		
			KsenmartMap.afterSetPoint = function(){
				var distance=KsenmartMap.getDistance();
				var data={};
				var vars={};
				var form=jQuery(".form");
				data["model"]="orders";
				data["form"]="order";
				data["fields"]=["costs"];
				vars["user_id"]=form.find("#jformuser_id").val();
				vars["region_id"]=form.find("#jformregion_id").val();
				vars["shipping_id"]=form.find("#jformshipping_id").val();
				vars["items"]=getOrderItems();
				data["vars"]=vars;
				data["id"]=form.find(".id").val();
				data["distance"]=distance;
				KMRenewFormFields(data);
			};

		</script>
		';
        
        return $html;
    }
}
