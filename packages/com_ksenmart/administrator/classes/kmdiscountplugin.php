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
	
	private $_discounts = null;
	protected $_discount_catalog = 0;
	protected $db;

    function __construct(&$subject, $config) {
		$params = JComponentHelper::getParams('com_ksenmart');
		$this->_discount_catalog = $params->get('discount_catalog', 0);
        parent::__construct($subject, $config);
    }
	
    function calculateDiscountProduct($price, $params) {
		$discount_value = 0;
        $undiscount_price = $price;
        if($params['type']==1 && $price>$discount_value) {
            $discount_value = $params['value'];   
        }elseif($params['type'] == 0) {
		    $discount = $price*$params['value']/100;
            if($price > $price-$discount) $discount_value = $discount;
        }
		
        return $discount_value;
    }
	
    function onBeforeStartComponent() {
        $view = JRequest::getVar('view','noneview');
        $product_id = 0;		
        if($view=='product') $product_id = JRequest::getVar('id',0);	
        $query = $this->db->getQuery(true);
        $query->select('id, type')->from('#__ksenmart_discounts')->where('type=' . $this->db->quote($this->_name))->where('enabled=1');
        $this->db->setQuery($query);
        $discounts = $this->db->loadObjectList();
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
		if(empty($this->_discounts)) $this->getDiscounts();
        foreach($this->_discounts as $discount) {
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
	
	protected function getDiscounts(){
		$query = $this->db->getQuery(true);
		$query->select('id')->from('#__ksenmart_discounts')->where('type=' . $this->db->quote($this->_name))->where('enabled=1')->order('ordering');
		$this->db->setQuery($query);
		$this->_discounts = $this->db->loadObjectList();
	} 
	
	function onAfterExecuteHelperKSMProductsGetProduct($prd = null){
		if(!$this->_discount_catalog)
			return false;
		if(empty($prd)) 
			return false;
		if(empty($this->_discounts)) $this->getDiscounts();
		foreach($this->_discounts as $discount) {
            $return = $this->onCheckDiscountDate($discount->id);
            if(!$return) continue;
            $return = $this->onCheckDiscountCountry($discount->id);
            if(!$return) continue;
            $return = $this->onCheckDiscountUserGroups($discount->id);
            if(!$return) continue;
            $return = $this->onCheckDiscountActions($discount->id);
            if($return == 1) continue;
            $prd = $this->onSetProductDiscount($prd, $discount->id);
        }
	}
	
	function onAfterExecuteHelperKSMProductsGetProductPriceProperties($price = 0, $prd = null){
		if(!$this->_discount_catalog)
			return false;
		if(empty($prd)) 
			return false;
		if(empty($this->_discounts)) $this->getDiscounts();
		unset($prd->original_price);
		foreach($this->_discounts as $discount) {
            $return = $this->onCheckDiscountDate($discount->id);
            if(!$return) continue;
            $return = $this->onCheckDiscountCountry($discount->id);
            if(!$return) continue;
            $return = $this->onCheckDiscountUserGroups($discount->id);
            if(!$return) continue;
            $return = $this->onCheckDiscountActions($discount->id);
            if($return == 1) continue;
            $prd = $this->onSetProductDiscount($prd, $discount->id);
        }
	}
	
	function onSetProductDiscount($prd = null, $discount_id = null){
		return $prd;
	}

    function onAfterExecuteKSMOrdersGetorder($model,$order=null) {
        if(empty($order)) return false;
        $order_discounts = json_decode($order->discounts, true);
        if(!count($order_discounts)) return false;
        $order_discounts_ids = array();
        foreach($order_discounts as $key => $params) $order_discounts_ids[] = $key;
        $query = $this->db->getQuery(true);
        $query->select('id')->from('#__ksenmart_discounts')->where('type=' . $this->db->quote($this->_name))->where('id in (' . implode(',', $order_discounts_ids) . ')');
        $this->db->setQuery($query);
        $discounts = $this->db->loadObjectList();
        foreach($discounts as $discount) {
            $this->onSetOrderDiscount($order, $discount->id, $order_discounts[$discount->id]);
        }
        return true;
    }

    function onCheckDiscountDate($discount_id = null) {
        if(empty($discount_id)) return true;
        $query = $this->db->getQuery(true);
        $query->select('from_date,to_date')->from('#__ksenmart_discounts')->where('type=' . $this->db->quote($this->_name))->where('id=' . (int)$discount_id)->where('enabled=1');
        $this->db->setQuery($query);
        $discount = $this->db->loadObject();
        if(empty($discount)) return true;
        $date = date('Y-m-d');
        if($date > $discount->to_date || $date < $discount->from_date) return false;
        return true;
    }

    function onCheckDiscountActions($discount_id = null) {
        if(empty($discount_id)) return 0;
        $query = $this->db->getQuery(true);
        $query->select('id,actions_limit')->from('#__ksenmart_discounts')->where('type=' . $this->db->quote($this->_name))->where('id=' . (int)$discount_id)->where('enabled=1');
        $this->db->setQuery($query);
        $discount = $this->db->loadObject();
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
        $query = $this->db->getQuery(true);
        $query->select('user_groups')->from('#__ksenmart_discounts')->where('type=' . $this->db->quote($this->_name))->where('id=' . (int)$discount_id)->where('enabled=1');
        $this->db->setQuery($query);
        $user_groups = $this->db->loadResult(); 
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
        $query = $this->db->getQuery(true);
        $query->select('regions')->from('#__ksenmart_discounts')->where('type=' . $this->db->quote($this->_name))->where('id=' . (int)$discount_id)->where('enabled=1');
        $this->db->setQuery($query);
        $regions = $this->db->loadResult();
        if(empty($regions)) return true;
        
		$app = JFactory::getApplication();
		$user = KSUsers::getUser();
		$region_id  = (int)$app->getUserState('com_ksenmart.region_id', $user->region_id);
		
		return $this->checkRegion($regions, $region_id);
    }

    function onCheckDiscountManufacturers($discount_id = null, $product_id = null) {
        if(empty($discount_id)) return true;
        $query = $this->db->getQuery(true);
        $query->select('manufacturers')->from('#__ksenmart_discounts')->where('type=' . $this->db->quote($this->_name))->where('id=' . (int)$discount_id)->where('enabled=1');
        $this->db->setQuery($query);
        $manufacturers = $this->db->loadResult();
        if(empty($manufacturers)) return true;
        $manufacturers = json_decode($manufacturers, true); 
        if(!count($manufacturers)) return true;
        if(empty($product_id)) return true;
        $query = $this->db->getQuery(true);
        $query->select('manufacturer')->from('#__ksenmart_products')->where('id=' . (int)$product_id);
        $this->db->setQuery($query);
        $manufacturer = $this->db->loadResult();
        if(empty($manufacturer)) return false;
        if(!in_array($manufacturer, $manufacturers)) return false;
        return true;
    }

    function onCheckDiscountCategories($discount_id = null, $product_id = null) {
        if(empty($discount_id)) return true;
        $query = $this->db->getQuery(true);
        $query->select('categories')->from('#__ksenmart_discounts')->where('type=' . $this->db->quote($this->_name))->where('id=' . (int)$discount_id)->where('enabled=1');
        $this->db->setQuery($query);
        $categories = $this->db->loadResult();
        if(empty($categories)) return true;
        $categories = json_decode($categories, true);
        if(!count($categories)) return true;
        if(empty($product_id)) return true;
        $query = $this->db->getQuery(true);
        $query->select('category_id')->from('#__ksenmart_products_categories')->where('product_id=' . (int)$product_id);
        $this->db->setQuery($query);
        $prd_categories = $this->db->loadObjectList();
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
        $session = JFactory::getSession();
        $emailed = $session->get('com_ksenmart.emailed_discount_' . $discount_id, null);
        if(empty($emailed)) {
            $query = $this->db->getQuery(true);
            $query->select('content,info_methods')->from('#__ksenmart_discounts')->where('type=' . $this->db->quote($this->_name))->where('id=' . (int)$discount_id)->where('enabled=1');
            $this->db->setQuery($query);
            $discount = $this->db->loadObject();
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
        $session = JFactory::getSession();
        $query = $this->db->getQuery(true);
        $query->select('info_methods')->from('#__ksenmart_discounts')->where('type=' . $this->db->quote($this->_name))->where('id=' . (int)$discount_id)->where('enabled=1');
        $this->db->setQuery($query);
        $info_methods = $this->db->loadResult();
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
