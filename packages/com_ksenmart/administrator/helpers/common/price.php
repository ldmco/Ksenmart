<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class KSMPrice {
    
    private static $_currencies = array();
    private static $_discounts = array();
    private static $_currency = 0;

    public static function getCurrencies(){
        if (empty(self::$_currencies)) {
            $db = JFactory::getDBO();
            $query = "select * from #__ksenmart_currencies";
            $db->setQuery($query);
            $rows = $db->loadObjectList('id');
            
            if($rows){
                self::$_currencies = $rows;
            }
        }
        return self::$_currencies;
    }

    public static function _getDefaultCurrency(){
        $currencies = self::getCurrencies();
        foreach ($currencies as $currency) {
            if($currency->default){
                return $currency->id;
            }
        }
    }
    
    public static function showPriceWithoutTransform($price, $currency = 0) {
        self::setDefaultUserCurrency($currency);
        $currency = self::getDefaultUserCurrency();
        self::getCurrencies();
        $currency = self::$_currencies[$currency];
        
        $price = str_replace('{price}', $price, $currency->template);
        
        return $price;
    }
    
    public static function showPriceWithTransform($price, $currency = 0) {
        $currency = 0;
        self::setDefaultUserCurrency($currency);
        $currency = self::getDefaultUserCurrency();
        self::getCurrencies();
        $currency = self::$_currencies[$currency];
        $price = number_format($price, $currency->fractional, '.', $currency->separator);
        $price = str_replace('{price}', '<span class="price_num">' . $price . '</span>', $currency->template); //Ужас!!!
        
        return $price;
    }
    
    public static function getPriceInDefaultCurrency($price, $currency = 0) {
        self::getCurrencies();
        if($currency == 0){
            $currency = self::_getDefaultCurrency();
        }
        $currency = self::$_currencies[$currency];
        $price = $price / $currency->rate;
        
        return $price;
    }
    
    public static function getPriceInCurrentCurrency($price, $currency) {
        $session = JFactory::getSession();
        $curr_currency = $session->get('com_ksenmart.catalog.currency', self::_getDefaultCurrency());
        self::getCurrencies();
        if($currency == 0){
            $currency = self::_getDefaultCurrency();
        }
        $currency = self::$_currencies[$currency];
        $curr_currency = self::$_currencies[$curr_currency];
        $price = $price / $currency->rate * $curr_currency->rate;
        
        return $price;
    }
    
    public static function getPriceInDefaultCurrencyWithTransform($price, $currency) {
        self::getCurrencies();
        $currency = self::$_currencies[$currency];
        $price = number_format($price, $currency->fractional, '.', $currency->separator);
        $price = str_replace('{price}', $price, $currency->template);
        
        return $price;
    }
    
    public static function getPriceInCurrency($price, $currency = 0) {
        self::getCurrencies();
        if($currency <= 0){
            $currency = self::getDefaultUserCurrency();
        }
        $currency = self::$_currencies[$currency];
        $price = $price * $currency->rate;
        $price = number_format($price, $currency->fractional, '.', $currency->separator);
        
        return $price;
    }
    
    public static function getCurrencyCode($currency = 0) {
        self::getCurrencies();
        $currency = self::$_currencies[$currency];
        
        return $currency->code;
    }
    
    public static function getCurrencyName($currency = 0) {
        self::getCurrencies();
        $currency = self::$_currencies[$currency];
        
        return $currency->title;
    }
    
    public static function getPriceWithDiscount($price, $subscribe = 0) {
        $user = KSUsers::getUser();
        $db = JFactory::getDBO();
        $percent = 0;
        $rur = 0;
        /*$where = " and (0";
              foreach ($user->groups as $group)
                  if ($group != 2 || ($group == 2 && $subscribe == 2)) $where .=
                          " or user_group like '%|$group|%'";
              if ($subscribe == 1) $where .= " or user_group like '%|2|%'";
              $where .= ")";
              if (array_key_exists($where, self::$_discounts)) {
                  $discounts = self::$_discounts[$where];
              } else {
                  $query = "select * from #__ksenmart_discounts where enabled='1' $where";
                  $db->setQuery($query);
                  $discounts = $db->loadObjectList();
                  self::$_discounts[$where] = $discounts;
              }
        */
        
        $price = ($price - $rur) * (100 - $percent) / 100;
        
        
        return number_format($price, 2, '.', '');
    }
    
    public static function sendOrderIndoCustomer($order_id) {
        $dispatcher = JDispatcher::getInstance();
        $db = JFactory::getDBO();
        $Itemid = KSSystem::getShopItemid();
        $query = "select * from #__ksenmart_orders where id='$order_id'";
        $db->setQuery($query);
        $order = $db->loadObject();
        $content = '';
        $content.= '
            <table class="cellpadding">
                <tr>
                    <td colspan="2">Информация</td>
                </tr>
                <tr>
                    <td>Имя:</td>
                    <td>' . $order->name . '</td>
                </tr>                           
                <tr>
                    <td>E-mail:</td>
                    <td>' . $order->email . '</td>
                </tr>
                <tr>
                    <td>Адрес доставки:</td>
                    <td>' . $order->address . '</td>
                </tr>
            </table>    
            <h2>Заказ</h2>
            <table id="cart_content_tbl" cellspacing="0">
                <colgroup>
                    <col width="50%" />
                    <col width="15%" />
                    <col width="20%" />
                    <col width="5%" />
                </colgroup>
                <tr id="cart_content_header">
                    <td><b>Продукт</b></td>
                    <td align="center"><b>Кол-во</b></td>
                    <td align="center"><b>Цена</b></td>
                    <td align="center"><b>Стоимость</b></td>
                </tr>
        ';
        $query = "select * from #__ksenmart_order_items where order_id='$order_id'";
        $db->setQuery($query);
        $order->items = $db->loadObjectList();
        
        foreach ($order->items as & $item) {
            $item_properties = array();
            $properties = explode(';', $item->properties);
            
            foreach ($properties as $property) {
                $property = explode(':', $property);
                $query = "select * from #__ksenmart_properties where id='$property[0]'";
                $this->_db->setQuery($query);
                $prop = $this->_db->loadObject();
                if (count($prop) > 0) {
                    $item_properties[] = new stdClass();
                    $item_properties[count($item_properties) - 1]->title = $prop->title;
                    if ($prop->type == 'select' || $prop->type == 'radio') {
                        $query = "select * from #__ksenmart_property_values where id='$property[1]'";
                        $this->_db->setQuery($query);
                        $val = $this->_db->loadObject();
                        if (count($val) > 0) $item_properties[count($item_properties) - 1]->value = $val->title . ' ' . $prop->finishing;
                    }
                }
            }
            $query = "select * from #__ksenmart_products where id='$item->product_id'";
            $db->setQuery($query);
            $item->product = $db->loadObject();
            $link = JURI::root() . JRoute::_('index.php?option=com_ksenmart&view=product&id=' . $item->product->id . ":" . $item->product->alias . '&Itemid=' . $Itemid);
            $content.= '        
            <tr class="row_odd">
                <td class="vid_produkt">
                    <a class="title_lookp" href="' . $link . '" >' . $item->product->title . '</a>
            ';
            if ($item->product->product_code != '') $content.= ' <i>Арт. ' . $item->product->product_code . '</i>';
            
            foreach ($item_properties as $item_property) {
                if (!empty($item_property->value)) $content.= '<br><span>' . $item_property->title . ':</span> ' . $item_property->value;
                else $content.= '<br><span>' . $item_property->title . '</span>';
            }
            $content.= '                            
                    <div class="cart_product_brief_description">
                        <p>' . $item->product->introcontent . '</p>
                    </div>
                </td>
                <td align="center">' . $item->count . '</td>
                <td align="center">' . KSMPrice::showPriceWithTransform($item->price) . '</td>
                <td align="center" nowrap="nowrap">
                    ' . KSMPrice::showPriceWithTransform($item->count * $item->price) . '
                </td>
            </tr>';
        }
        $order->total_cost = $order->cost;
        $dispatcher->trigger('onAfterGetOrder', array(&$order
        ));
        $results = $dispatcher->trigger('onDisplayAfterLetterContent', array(&$order
        ));
        $order->onDisplayAfterLetterContent = trim(implode("\n", $results));
        $content.= '
            <tr>
                <td id="cart_total_label">
                    Общая стоимость товаров
                </td>
                <td align="center"></td>
                <td></td>
                <td id="cart_total" align="center">' . KSMPrice::showPriceWithTransform($order->cost) . '</td>
            </tr>
        ';
        $content.= $order->onDisplayAfterLetterContent;
        $content.= '
            <tr>
                <td id="cart_total_label">
                    Стоимость доставки:
                </td>
                <td align="center">
                </td>
                <td></td>
                <td id="cart_total" align="center">' . KSMPrice::showPriceWithTransform($order->delivery_cost) . '</td>
            </tr>
            <tr>
                <td id="cart_total_label">
                    Итого
                </td>
                <td align="center"></td>
                <td></td>
                <td id="cart_total" align="center">' . KSMPrice::showPriceWithTransform($order->total_cost + $order->delivery_cost) . '</td>
            </tr>
        </table>';
        $mail = & JFactory::getMailer();
        $mail->isHTML(true);
        $params = JComponentHelper::getParams('com_ksenmart');
        $sender = array(
            $params->get('shop_email') ,
            $params->get('shop_name')
        );
        $mail->setSender($sender);
        $mail->Subject = 'Новый заказ №' . $order_id;
        $mail->Body = $content;
        $mail->AddAddress($order->email, $order->name);
        $mail->Send();
    }
    
    public static function sendOrderIndoAdmin($order_id) {
        $dispatcher = JDispatcher::getInstance();
        $db = JFactory::getDBO();
        $Itemid = KSSystem::getShopItemid();
        $query = "select * from #__ksenmart_orders where id='$order_id'";
        $db->setQuery($query);
        $order = $db->loadObject();
        $content = '';
        $content.= '
            <table class="cellpadding">
                <tr>
                    <td colspan="2">Информация</td>
                </tr>
                <tr>
                    <td>Имя:</td>
                    <td>' . $order->name . '</td>
                </tr>                           
                <tr>
                    <td>E-mail:</td>
                    <td>' . $order->email . '</td>
                </tr>
                <tr>
                    <td>Адрес доставки:</td>
                    <td>' . $order->address . '</td>
                </tr>
            </table>    
            <h2>Заказ</h2>
            <table id="cart_content_tbl" cellspacing="0">
                <colgroup>
                    <col width="50%" />
                    <col width="15%" />
                    <col width="20%" />
                    <col width="5%" />
                </colgroup>
                <tr id="cart_content_header">
                    <td><b>Продукт</b></td>
                    <td align="center"><b>Кол-во</b></td>
                    <td align="center"><b>Цена</b></td>
                    <td align="center"><b>Стоимость</b></td>
                </tr>
        ';
        $query = "select * from #__ksenmart_order_items where order_id='$order_id'";
        $db->setQuery($query);
        $order->items = $db->loadObjectList();
        
        foreach ($order->items as & $item) {
            $item_properties = array();
            $properties = explode(';', $item->properties);
            
            foreach ($properties as $property) {
                $property = explode(':', $property);
                $query = "select * from #__ksenmart_properties where id='$property[0]'";
                $this->_db->setQuery($query);
                $prop = $this->_db->loadObject();
                if (count($prop) > 0) {
                    $item_properties[] = new stdClass();
                    $item_properties[count($item_properties) - 1]->title = $prop->title;
                    if ($prop->type == 'select' || $prop->type == 'radio') {
                        $query = "select * from #__ksenmart_property_values where id='$property[1]'";
                        $this->_db->setQuery($query);
                        $val = $this->_db->loadObject();
                        if (count($val) > 0) $item_properties[count($item_properties) - 1]->value = $val->title . ' ' . $prop->finishing;
                    }
                }
            }
            $query = "select * from #__ksenmart_products where id='$item->product_id'";
            $db->setQuery($query);
            $item->product = $db->loadObject();
            $link = JURI::root() . JRoute::_('index.php?option=com_ksenmart&view=product&id=' . $item->product->id . ":" . $item->product->alias . '&Itemid=' . $Itemid);
            $content.= '        
            <tr class="row_odd">
                <td class="vid_produkt">
                    <a class="title_lookp" href="' . $link . '" >' . $item->product->title . '</a>
            ';
            if ($item->product->product_code != '') $content.= ' <i>Арт. ' . $item->product->product_code . '</i>';
            
            foreach ($item_properties as $item_property) {
                if (!empty($item_property->value)) $content.= '<br><span>' . $item_property->title . ':</span> ' . $item_property->value;
                else $content.= '<br><span>' . $item_property->title . '</span>';
            }
            $content.= '                            
                    <div class="cart_product_brief_description">
                        <p>' . $item->product->introcontent . '</p>
                    </div>
                </td>
                <td align="center">' . $item->count . '</td>
                <td align="center">' . KSMPrice::showPriceWithTransform($item->price) . '</td>
                <td align="center" nowrap="nowrap">
                    ' . KSMPrice::showPriceWithTransform($item->count * $item->price) . '
                </td>
            </tr>';
        }
        $order->total_cost = $order->cost;
        $dispatcher->trigger('onAfterGetOrder', array(&$order
        ));
        $results = $dispatcher->trigger('onDisplayAfterLetterContent', array(&$order
        ));
        $order->onDisplayAfterLetterContent = trim(implode("\n", $results));
        $content.= '
            <tr>
                <td id="cart_total_label">
                    Общая стоимость товаров
                </td>
                <td align="center"></td>
                <td></td>
                <td id="cart_total" align="center">' . KSMPrice::showPriceWithTransform($order->cost) . '</td>
            </tr>
        ';
        $content.= $order->onDisplayAfterLetterContent;
        $content.= '            
            <tr>
                <td id="cart_total_label">
                    Стоимость доставки:
                </td>
                <td align="center">
                </td>
                <td></td>
                <td id="cart_total" align="center">' . KSMPrice::showPriceWithTransform($order->delivery_cost) . '</td>
            </tr>
            <tr>
                <td id="cart_total_label">
                    Итого
                </td>
                <td align="center"></td>
                <td></td>
                <td id="cart_total" align="center">' . KSMPrice::showPriceWithTransform($order->total_cost + $order->delivery_cost) . '</td>
            </tr>
        </table>';
        $mail = & JFactory::getMailer();
        $mail->isHTML(true);
        $params = JComponentHelper::getParams('com_ksenmart');
        $sender = array(
            $params->get('shop_email') ,
            $params->get('shop_name')
        );
        $mail->setSender($sender);
        $mail->Subject = 'Новый заказ №' . $order_id;
        $mail->Body = $content;
        $mail->AddAddress($params->get('shop_email') , $params->get('shop_name'));
        $mail->Send();
    }

    public static function setDefaultUserCurrency($id){
        if($id > 0){
            $session = JFactory::getSession();
            $session->set('com_ksenmart.catalog.currency', $id);

            return true;
        }
    }

    public static function getDefaultUserCurrency(){
        $session = JFactory::getSession();
        $currency = $session->get('com_ksenmart.catalog.currency', self::_getDefaultCurrency());
        return $currency;
    }
}
