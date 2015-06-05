<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if (!class_exists('KSMShippingPlugin')) {
    require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmshippingplugin.php');
}

class plgKMShippingFixedRegions extends KSMShippingPlugin {
    
    public $_params = array();
    
    public function onDisplayParamsForm($name = '', $params = null) {
        if ($name != $this->_name) return;
        if (empty($params)) $params = $this->_params;
        $db = JFactory::getDBO();
        $currency_code = $this->getDefaultCurrencyCode();
        $html = '';
        $html.= '<div class="set">';
        $html.= '	<h3 class="headname">' . JText::_('ksm_shipping_algorithm') . '</h3>';
        $html.= '	<div class="lists">';
        $html.= '		<div class="row">';
        $html.= '			<ul class="regions-params-ul">';
        
        foreach ($params as $region_id => $cost) {
            $query = $db->getQuery(true);
            $query->select('title,country_id')->from('#__ksenmart_regions')->where('id=' . $region_id);
            $db->setQuery($query);
            $region = $db->loadObject();
            if (!empty($region)) {
                $html.= '		<li region_id="' . $region_id . '" country_id="' . $region->country_id . '">';
                $html.= '			<div class="line">';
                $html.= '				<label class="inputname">' . $region->title . '</label>';
                $html.= '				<input type="text" class="inputbox" name="jform[params][' . $region_id . ']" value="' . $cost . '">';
                $html.= '				<p>' . $currency_code . '</p>';
                $html.= '			</div>';
                $html.= '		</li>';
            }
        }
        $html.= '				<li class="no-regions" style="' . (count($params) > 0 ? 'display:none;' : '') . '"><div class="line"><label class="inputname">' . JText::_('ksm_shippings_shipping_no_regions') . '</label><p>&nbsp;</p></div></li>';
        $html.= '			</ul>';
        $html.= '		</div>';
        $html.= '	</div>';
        $html.= '</div>';
        $script = '
		jQuery(document).ready(function(){
		
			jQuery(".all-countries").click(function(){
				if (jQuery(this).is(".active"))
				{
					jQuery("#popup-window3 li").each(function(){
						if (!jQuery(this).is(".all-countries") && jQuery(this).is(".active"))
							removeFixedRegionsCountry(jQuery(this).attr("country_id"));
					});
				}
			});
			
			jQuery("#popup-window3 li").click(function(){
				if (!jQuery(this).is(".all-countries") && jQuery(this).is(".active"))
					removeFixedRegionsCountry(jQuery(this).attr("country_id"));
			});
			
			jQuery("body").on("click", ".ksm-slidemodule-countries .countries li i", function(){
				var country_id=jQuery(this).parents("li").attr("country_id");
				removeFixedRegionsCountry(country_id);
			});
			
			jQuery(".all-regions").click(function(){
				if (jQuery(this).is(".active"))
				{
					jQuery(this).parent().find("li").each(function(){
						if (!jQuery(this).is(".all-regions") && jQuery(this).is(".active"))
							removeFixedRegionsRegion(jQuery(this).attr("region_id"));
					});
				}
				else
				{
					jQuery(this).parent().find("li").each(function(){
						if (!jQuery(this).is(".all-regions") && !jQuery(this).is(".active"))
							addFixedRegionsRegion(jQuery(this).attr("region_id"));
					});				
				}
			});
			
			jQuery("#popup-window4 .regions-row li").click(function(){
				if (!jQuery(this).is(".all-regions") && jQuery(this).is(".active"))
					removeFixedRegionsRegion(jQuery(this).attr("region_id"));
				else if (!jQuery(this).is(".all-regions") && !jQuery(this).is(".active"))
					addFixedRegionsRegion(jQuery(this).attr("region_id"));
			});
			
			jQuery("body").on("click", ".ksm-slidemodule-regions .regions i", function(){
				var region_id=jQuery(this).parents("li").attr("region_id");
				removeFixedRegionsRegion(region_id);
			});	
			
			function removeFixedRegionsCountry(country_id)
			{
				jQuery(".regions-params-ul li[country_id="+country_id+"]").each(function(){
					removeFixedRegionsRegion(jQuery(this).attr("region_id"));
				});				
			}
			
			function addFixedRegionsRegion(region_id)
			{
				var html="";
				var title=jQuery("#popup-window4 li[region_id="+region_id+"] span").text();
				var country_id=jQuery("#popup-window4 li[region_id="+region_id+"]").attr("country_id");
				html+="<li region_id="+region_id+" country_id="+country_id+">";
				html+="		<div class=\'line\'>";
				html+="			<label class=\'inputname\'>"+title+"</label>";
				html+="			<input type=\'text\' class=\'inputbox\' name=\'jform[params]["+region_id+"]\' value=\'0\'>";
				html+="			<p>' . $currency_code . '</p>";
				html+="		</div>";
				html+="</li>";
				jQuery(".regions-params-ul .no-regions").hide();
				jQuery(".regions-params-ul").append(html);			
			}
			
			function removeFixedRegionsRegion(region_id)
			{
				jQuery(".regions-params-ul li[region_id="+region_id+"]").remove();
				if (jQuery(".regions-params-ul li").length==1)
					jQuery(".regions-params-ul .no-regions").show();				
			}			
			
		});
		';
        $document = JFactory::getDocument();
        $document->addScriptDeclaration($script);
        
        return $html;
    }
    
    public function onAfterExecuteKSMCartGetCart($model, $cart = null) {
        if (empty($cart)) return;
        if (empty($cart->shipping_id)) return;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id,params,regions')->from('#__ksenmart_shippings')->where('id=' . $cart->shipping_id)->where('type=' . $db->quote($this->_name))->where('published=1');
        $db->setQuery($query);
        $shipping = $db->loadObject();
        if (empty($shipping)) return;
        if (empty($cart->region_id)) return;
        if (!$this->checkRegion($shipping->regions, $cart->region_id)) return;
        $shipping->params = json_decode($shipping->params, true);
        if (!isset($shipping->params[$cart->region_id])) return;
        $cart->shipping_sum = (float)$shipping->params[$cart->region_id];
        $cart->shipping_sum_val = KSMPrice::showPriceWithTransform($cart->shipping_sum);
        $cart->total_sum+= $cart->shipping_sum;
        $cart->total_sum_val = KSMPrice::showPriceWithTransform($cart->total_sum);
        
        return;
    }
    
    public function onAfterExecuteKSMOrdersGetorder($model, $order = null) {
        $this->onAfterExecuteHelperKSMOrdersGetOrder($order);
    }
    
    public function onAfterExecuteHelperKSMOrdersGetOrder($order = null) {
        
        if (empty($order)) return;
        if (empty($order->shipping_id)) return;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id,params,regions')->from('#__ksenmart_shippings')->where('id=' . $order->shipping_id)->where('type=' . $db->quote($this->_name))->where('published=1');
        $db->setQuery($query);
        $shipping = $db->loadObject();
        if (empty($shipping)) return;
        if (empty($order->region_id)) return;
        if (!$this->checkRegion($shipping->regions, $order->region_id)) return;
        $shipping->params = json_decode($shipping->params, true);
        if (!isset($shipping->params[$order->region_id])) return;
        $order->costs['shipping_cost'] = $shipping->params[$order->region_id];
        $order->costs['shipping_cost_val'] = KSMPrice::showPriceWithTransform($order->costs['shipping_cost']);
        $order->costs['total_cost']+= $order->costs['shipping_cost'];
        $order->costs['total_cost_val'] = KSMPrice::showPriceWithTransform($order->costs['total_cost']);
        
        return;
    }
}