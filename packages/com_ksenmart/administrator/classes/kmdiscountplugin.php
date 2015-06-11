<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if(!class_exists('KMPlugin')) {
    require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}

abstract class KMDiscountPlugin extends KMPlugin {

    function __construct(&$subject, $config) {
        parent::__construct($subject, $config);
    }
	
	function onAfterExecuteHelperKSMProductsgetProduct($product){    
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
        $query->select('*')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where("type!=".$db->quote('onorder'))->where("type!=".$db->quote('coupons'))->where('enabled=1');
        $db->setQuery($query);
        $discounts = $db->loadObjectList();
		
		$query = $db->getQuery(true);
		$query->select('id, template')->from('#__ksenmart_currencies');
		$db->setQuery($query);
		$price_types = $db->loadObjectList('id');
		
		
        $array_discount_prices = array();
        $summ_discount_price = 0;
        foreach($discounts as $discount) {
			$return = $this->onCheckDiscountDate($discount->id); 
            if(!$return) continue;  
            $return = $this->onCheckDiscountCountry($discount->id); 
            if(!$return) continue;
			if($product->id!=0){ 
  			   $return = $this->onCheckDiscountManufacturers($discount->id, $product->id); 
               if(!$return) continue;
			}
            $return = $this->onCheckDiscountUserGroups($discount->id); 
            if(!$return) continue;
            $return = $this->onCheckDiscountActions($discount->id); 
            if($return == 1) continue; 
                                                      
            if($discount->sum==1){
			   $summ_discount_price += $this->calculateDiscountProduct($product->price, json_decode($discount->params));
    		}else{
			   $array_discount_prices[] = $this->calculateDiscountProduct($product->price, json_decode($discount->params));
			}
        }
		if($summ_discount_price!=0) $array_discount_prices[] = $summ_discount_price;
		
		$final_discount = 0;
		foreach($array_discount_prices as $discount){
		    if($discount>$final_discount && $discount<$product->price) $final_discount = $discount;
		}
		 		
		if($final_discount<>0){ 
		     $product->old_price = $product->price;
			 $product->price -= $final_discount;
			 
			 $product->val_old_price = $product->val_price;
			 $val_price = str_replace("{price}", $product->price, $price_types[$product->price_type]->template);
			 if($product->price>999){
			    $val_price = substr_replace($val_price, " ", 1, 0);
			 }elseif($product->price>9999){
			    $val_price = substr_replace($val_price, " ", 2, 0);
			 }
			 $product->val_price = $val_price;
			 $product->discounts[] = $final_discount;
		}
	}
	
    function calculateDiscountProduct($price, $params) {
		$discount_value = 0;
        $undiscount_price = $price;
        if($params->type==1 && $price>$discount_value) {
            $discount_value = $params->value;   
        }elseif($params->type == 0) {
		    $discount = $price*$params->value/100;
            if($price > $price-$discount) $discount_value = $discount;
        }
        return $discount_value;
    }
	
    function onBeforeStartComponent() {
        $db = JFactory::getDBO();
        $view = JRequest::getVar('view','noneview');
        $product_id = 0;		
        if($view=='product') $product_id = JRequest::getVar('id',0);	
        $query = $db->getQuery(true);
        $query->select('id, type')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('enabled=1');
        $db->setQuery($query);
        $discounts = $db->loadObjectList();
		$active_discounts = array(); 
        foreach($discounts as &$discount) {  
			$return = $this->onCheckDiscountDate($discount->id); 
            if(!$return) continue; 
            $return = $this->onCheckDiscountCountry($discount->id); 
            if(!$return) continue;
			if($product_id!=0){ 
  			   $return = $this->onCheckDiscountManufacturers($discount->id, $product_id); 
               if(!$return) continue;
			}
            $return = $this->onCheckDiscountUserGroups($discount->id); 
            if(!$return) continue;
            $return = $this->onCheckDiscountActions($discount->id); 
            if($return == 1) continue; 
            
			$active_discounts[] = $discount->id;
            $this->onSendDiscountEmail($discount->id);  
			
        }
		$kmdiscounts = JRequest::getVar('kmdiscounts', array());
		$kmdiscounts = array_merge($kmdiscounts, $active_discounts);
		JRequest::setVar('kmdiscounts',$kmdiscounts);
    }

    function onAfterExecuteKSMCartGetcart($model,$cart=null) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('enabled=1')->order('ordering');
        $db->setQuery($query);
        $discounts = $db->loadObjectList();
        foreach($discounts as $discount) {
            $return = $this->onCheckDiscountDate($discount->id);
            if(!$return) continue;
            $return = $this->onCheckDiscountCountry($discount->id);
            if(!$return) continue;
            $return = $this->onCheckDiscountUserGroups($discount->id);
            if(!$return) continue;
            $return = $this->onCheckDiscountActions($discount->id);
            if($return == 1) continue;
            $this->onSetCartDiscount($cart, $discount->id);
        }
    }

    function onAfterExecuteKSMOrdersGetorder($model,$order=null) {
        if(empty($order)) return false;
        $order_discounts = json_decode($order->discounts, true);
        if(!count($order_discounts)) return false;
        $order_discounts_ids = array();
        foreach($order_discounts as $key => $params) $order_discounts_ids[] = $key;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('id in (' . implode(',', $order_discounts_ids) . ')');
        $db->setQuery($query);
        $discounts = $db->loadObjectList();
        foreach($discounts as $discount) {
            $this->onSetOrderDiscount($order, $discount->id, $order_discounts[$discount->id]);
        }
        return true;
    }

    function onCheckDiscountDate($discount_id = null) {
        if(empty($discount_id)) return true;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('from_date,to_date')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('id=' . $discount_id)->where('enabled=1');
        $db->setQuery($query);
        $discount = $db->loadObject();
        if(empty($discount)) return true;
        $date = date('Y-m-d');
        if($date > $discount->to_date || $date < $discount->from_date) return false;
        return true;
    }

    function onCheckDiscountActions($discount_id = null) {
        if(empty($discount_id)) return 0;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id,actions_limit')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('id=' . $discount_id)->where('enabled=1');
        $db->setQuery($query);
        $discount = $db->loadObject();
        if(empty($discount)) return 0;
        $dispatcher = JDispatcher::getInstance();
        $results = $dispatcher->trigger('onValidateAction', array($discount->id));
        //$results = array();

        if(!count($results)) return 0;
        $flag = 0;
        foreach($results as $result)
            if($result && !$discount->actions_limit) {
                $flag = 2;
                break;
            } elseif(!$result && $discount->actions_limit) {
                $flag = 1;
                break;
            } elseif($result) $flag = 2;
            else  $flag = 1;
        return $flag; 
    }

    function onCheckDiscountUserGroups($discount_id = null) {
        if(empty($discount_id)) return true;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('user_groups')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('id=' . $discount_id)->where('enabled=1');
        $db->setQuery($query);
        $user_groups = $db->loadResult(); 
        if(empty($user_groups)) return true;
        $user_groups = json_decode($user_groups, true); 
        if(!count($user_groups)) return true;
        $user = KSUsers::getUser();                      
        if(!$user->id) return false;
        if(!count($user->groups)) return false;
        foreach($user->groups as $user_group)
            if(in_array($user_group, $user_groups)) return true;
        return false;
    }

    function onCheckDiscountCountry($discount_id = null) {
        if(empty($discount_id)) return true;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('regions')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('id=' . $discount_id)->where('enabled=1');
        $db->setQuery($query);
        $regions = $db->loadResult();
        if(empty($regions)) return true;
        
		$app = JFactory::getApplication();
		$user = KSUsers::getUser();
		$region_id  = (int)$app->getUserState('com_ksenmart.region_id', $user->region_id);
		
		return $this->checkRegion($regions, $region_id);
    }

    function onCheckDiscountManufacturers($discount_id = null, $product_id = null) {
        if(empty($discount_id)) return true;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('manufacturers')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('id=' . $discount_id)->where('enabled=1');
        $db->setQuery($query);
        $manufacturers = $db->loadResult();
        if(empty($manufacturers)) return true;
        $manufacturers = json_decode($manufacturers, true); 
        if(!count($manufacturers)) return true;
        if(empty($product_id)) return true;
        $query = $db->getQuery(true);
        $query->select('manufacturer')->from('#__ksenmart_products')->where('id=' . $product_id);
        $db->setQuery($query);
        $manufacturer = $db->loadResult();
        if(empty($manufacturer)) return false;
        if(!in_array($manufacturer, $manufacturers)) return false;
        return true;
    }

    function onCheckDiscountCategories($discount_id = null, $product_id = null) {
        if(empty($discount_id)) return true;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('categories')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('id=' . $discount_id)->where('enabled=1');
        $db->setQuery($query);
        $categories = $db->loadResult();
        if(empty($categories)) return true;
        $categories = json_decode($categories, true);
        if(!count($categories)) return true;
        if(empty($product_id)) return true;
        $query = $db->getQuery(true);
        $query->select('category_id')->from('#__ksenmart_products_categories')->where('product_id=' . $product_id);
        $db->setQuery($query);
        $prd_categories = $db->loadObjectList();
        if(!count($prd_categories)) return false;
        foreach($prd_categories as $category)
            if(in_array($category->category_id, $categories)) return true;
        return false;
    }

    function calculateItemDiscount($item, $discount, &$discount_set_value, $params) {
        $discount_value = $params['value'];
        $undiscount_price = $item->price * $item->count;
        if($discount->sum == 1) {
            foreach($item->discounts as $item_discount) {
                if($item_discount->sum == 1) $undiscount_price -= $item_discount->discount_value;
            }
        }
        if($params['type'] == 1) {
            $discount_value -= $discount_set_value;
            if($undiscount_price < $discount_value) {
                $discount->discount_value = $undiscount_price;
                $discount_set_value += $undiscount_price;
            } else {
                $discount->discount_value = $discount_value;
                $discount_set_value += $discount_value;
            }
        } elseif($params['type'] == 0) {
            if($undiscount_price < $item->price * $item->count * $discount_value / 100) $discount->discount_value = $undiscount_price;
            else  $discount->discount_value = $item->price * $item->count * $discount_value / 100;
        }
        return $discount;
    }

    function onSendDiscountEmail($discount_id = null) {
        if(empty($discount_id)) return false;
        $db = JFactory::getDBO();
        $session = JFactory::getSession();
        $emailed = $session->get('com_ksenmart.emailed_discount_' . $discount_id, null);
        if(empty($emailed)) {
            $query = $db->getQuery(true);
            $query->select('content,info_methods')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('id=' . $discount_id)->where('enabled=1');
            $db->setQuery($query);
            $discount = $db->loadObject();
            if(empty($discount)) return;
            $info_methods = json_decode($discount->info_methods, true);
            if(KSUsers::getUser()->id == 0 || KSUsers::getUser()->email == '' || !in_array('email', $info_methods)) return;
            $mailer = JFactory::getMailer();
            $params = JComponentHelper::getParams('com_ksenmart');
            $sender = array($params->get('shop_email', ''), $params->get('shop_name', ''));
            $mailer->setSender($sender);
            $mailer->addRecipient(KSUsers::getUser()->email);
            $mailer->setSubject(JText::_('ksm_discount_email_subject'));
            $mailer->isHTML(true);
            $content = $this->onGetDiscountContent($discount_id);
            $mailer->setBody($content);
            $send = $mailer->Send();
            if($send) $session->set('com_ksenmart.emailed_discount_' . $discount_id, 1);
        }
    }

    function onSendDiscountModule($discount_id = null) {
        if(empty($discount_id)) return;
        $db = JFactory::getDBO();
        $session = JFactory::getSession();
        $query = $db->getQuery(true);
        $query->select('info_methods')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('id=' . $discount_id)->where('enabled=1');
        $db->setQuery($query);
        $info_methods = $db->loadResult();
        if(empty($info_methods)) return;
        $info_methods = json_decode($info_methods, true);
        if(!in_array('module', $info_methods)) return;
        $return = $this->onCheckDiscountDate($discount_id);
        if(!$return) return;
        $return = $this->onCheckDiscountCountry($discount_id);
        if(!$return) return;
        $return = $this->onCheckDiscountUserGroups($discount_id);
        if(!$return) return;
        $return = $this->onCheckDiscountActions($discount_id);
        if($return == 1) return;

        $content = $this->onGetDiscountContent($discount_id);

        return $content;
    }

}
