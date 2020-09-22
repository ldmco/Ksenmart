<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JFormFieldOrderDiscounts extends JFormField {

    protected $type = 'OrderDiscounts';

    public function getInput() {
        $db = JFactory::getDbo();

        $html = '';
        $html.= '<div class="positions">';
        $html.= '<div id="ksm-slidemodule-orderdiscount-container">';
        $this->value = json_decode($this->value);
        if(!empty($this->value) && count($this->value)){
            foreach($this->value as $discount_id => $order_discount){
                if($discount_id > 0) {
                    $query = $db->getQuery(true);
                    $query->select('*')->from('#__ksenmart_discounts')->where('id=' . (int)$discount_id);
                    $db->setQuery($query);
                    $discount = $db->loadObject();
                    $order_discount->sum = KSMPrice::showPriceWithTransform($order_discount->sum);
                    $html .= '<div class="position">';
                    $html .= '    <label class="inputname_response">' . $discount->title . ': <strong>' . $order_discount->sum . '</strong></label>';
                    $html .= '    <a class="del" href="#"></a>';
                    $html .= '    <input type="hidden" name="' . $this->name . '[' . $discount_id . ']" value="' . $discount_id . '" />';
                    $html .= '</div>';
                } else {
                    $html .= '<div class="position">';
                    $html .= '    <label class="inputname">' . JText::_('KSM_ORDERS_ORDER_CUSTOM_DISCOUNT') . ': </label>';
                    $html .= '    <input style="margin-top: 0;" name="' . $this->name . '[-1][cost]" value="' . $order_discount->value . '" type="text" class="inputname" >';
                    $html .= '    <a class="del" href="#"></a>';
                    $html .= '</div>';
                }
            }
            $html.= '</div>';
        } else {
            $html.= '</div>';
            $html.= '<div class="position">';
            $html.= '	<label class="inputname_response">' . JText::_('KSM_ORDERS_ORDER_NO_DISCOUNTS') . '</strong></label>';
            $html.= '</div>';
        }
        $html.= '</div>';
        $html.= '<div class="row">';
        $html.= '    <a href="' . JRoute::_('index.php?option=com_ksenmart&view=discounts&layout=search&items_tpl=orderdiscount&items_to=ksm-slidemodule-orderdiscounts-container&tmpl=component') . '" class="add add_discounts">' . JText::_('KSM_ORDERS_ORDER_ADD_DISCOUNTS') . '</a>';
        $html.= '    <a style="margin-left: 10px;" href="#" class="add add_custom">' . JText::_('KSM_ORDERS_ORDER_ADD_CUSTOM_DISCOUNT') . '</a>';
        $html.= '</div>';

        $script = '
		jQuery(document).ready(function(){
		
			jQuery("body").on("click", ".ksm-slidemodule-orderdiscounts .add_discounts", function(){
				var url=jQuery(this).attr("href");
				var width=jQuery(window).width();
				var height=jQuery(window).height();
				openPopupWindow(url,width,height);
				return false;
			});
			
			jQuery("body").on("click", ".ksm-slidemodule-orderdiscounts .add_custom", function(){
			    var html = "<div class=\"position\">";
			    html += "    <label class=\"inputname\">" + Joomla.JText._("KSM_ORDERS_ORDER_CUSTOM_DISCOUNT") + ": </label>";
			    html += "    <input style=\"margin-top: 0;\" name=\"' . $this->name . '[-1][cost]\" type=\"text\" class=\"inputname\" >";
			    html += "    <a class=\"del\" href=\"#\"></a>";
			    html += "</div>";
			    jQuery(".ksm-slidemodule-orderdiscounts .positions").append(html);
			    jQuery(this).hide();
			    
			    return false;
			});
			
			jQuery("body").on("click", ".ksm-slidemodule-orderdiscounts .del", function(){
			    jQuery(this).closest(".position").remove();
			    afterAddingDiscounts([]);
				
				return false;
			});
			
		});
		
		function afterAddingDiscounts(ids){
		    jQuery("#ksm-slidemodule-orderdiscount-container input").each(function(){
		        ids.push(jQuery(this).val());
		    });
            var data={};
            var vars={};
            var form=jQuery(".form");
            data["model"]="orders";
            data["form"]="order";
            data["fields"]=["discounts","costs"];
            vars["discounts"]=ids;			
            vars["items"]=getOrderItems();
            data["vars"]=vars;
            data["id"]=form.find(".id").val();
            KMRenewFormFields(data);
        }	
		';

        $document = JFactory::getDocument();
        $document->addScriptDeclaration($script);

        return $html;
    }
}