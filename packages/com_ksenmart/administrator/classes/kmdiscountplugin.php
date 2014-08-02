<?php

defined('_JEXEC') or die;

if(!class_exists('KMPlugin')) {
    require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}

abstract class KMDiscountPlugin extends KMPlugin {

    function __construct(&$subject, $config) {
        parent::__construct($subject, $config);
    }

    function onBeforeStartComponent() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('enabled=1');
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
            $this->onSendDiscountEmail($discount->id);
        }
    }

    function onAfterExecuteCartGetcart($model,$cart=null) {
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

    function onAfterExecuteOrdersGetorder($model,$order=null) {
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
        /*if(empty($discount_id)) return true;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('countries')->from('#__ksenmart_discounts')->where('type=' . $db->quote($this->_name))->where('id=' . $discount_id)->where('enabled=1');
        $db->setQuery($query);
        $countries = $db->loadResult();
        if(empty($countries)) return true;
        $countries = json_decode($countries, true);
        if(!count($countries)) return true;
        if(!class_exists('SxGeo')) include (JPATH_ROOT . '/components/com_ksenmart/helpers/geo.php');
        $SxGeo = new SxGeo(JPATH_ROOT . '/components/com_ksenmart/helpers/SxGeoCity.dat', SXGEO_BATCH | SXGEO_MEMORY);
        $data = $SxGeo->getCityFull($_SERVER['REMOTE_ADDR']);
        if(!isset($data['country']) || empty($data['country'])) return false;
        $query = $db->getQuery(true);
        $query->select('id')->from('#__ksenmart_countries')->where('code=' . $db->quote($data['country']));
        $db->setQuery($query);
        $country_id = $db->loadResult();
        if(!in_array($country_id, $countries)) return false;*/
        return true;
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
