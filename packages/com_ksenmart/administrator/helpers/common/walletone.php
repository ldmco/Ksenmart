<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('helpers.corehelper');
class KSMWalletone extends KSCoreHelper {
	private static $_fields = array();

    public static function _setFields(array $data){
    	if(!empty($data)){
    		foreach ($data as $key => $value) {
    			self::$_fields[$key] = $value;
    		}
    	}
    	return $data;
    }

    public static function _getFields(){
    	if(empty(self::$_fields)){
    		self::$_fields = array();
    	}
    	return self::$_fields;
    }

    public static function getHash($secretKey) {
    	$data = self::_getFields();
        if (!empty($data)) {
            unset($data['WMI_SIGNATURE']);

	        //Сортировка значений внутри полей
	        foreach ($data as $name => $val) {
	            if (is_array($val)) {
	                usort($val, "strcasecmp");
	                $data[$name] = $val;
	            }
	        }
	        // Формирование сообщения, путем объединения значений формы,
	        // отсортированных по именам ключей в порядке возрастания.
	        uksort($data, "strcasecmp");
	        $fieldValues = "";
	        
	        foreach ($data as $name => $value) {
	            if (is_array($value)) foreach ($value as $v) {
	                //Конвертация из текущей кодировки (UTF-8)
	                //необходима только если кодировка магазина отлична от Windows-1251
	                $v = iconv('UTF-8', 'windows-1251', $v);
	                $fieldValues.= $v;
	            } else {
	                //Конвертация из текущей кодировки (UTF-8)
	                //необходима только если кодировка магазина отлична от Windows-1251
	                $valueEncode = iconv('UTF-8', 'windows-1251', $value);
	                if(!$valueEncode){
	                	$valueEncode = $value;
	                }
	                $fieldValues.= $valueEncode;
	            }
	        }

            $sign = base64_encode(pack("H*", md5($fieldValues . $secretKey)));
            
            return $sign;
        }
        return false;
    }

    public static function getPayment($id, $pluginType, array $conditions = array()){
    	JTable::addIncludePath(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'tables');
        $table      = JTable::getInstance('Payments', 'KsenMartTable');
        $conditions = array_merge(array('id' => $id, 'published' => 1, 'type' => $pluginType), $conditions);
        
        $table->load($conditions);
        $payment = $table->getProperties();
        return JArrayHelper::toObject($payment);
    }

    public static function getShipping($id, $pluginType, array $conditions = array()){
		JTable::addIncludePath(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'tables');
		$table      = JTable::getInstance('Shippings', 'KsenMartTable');
		$conditions = array_merge(array('id' => $id, 'published' => 1, 'type' => $pluginType), $conditions);

		$table->load($conditions);
		$shipping = $table->getProperties();
		return JArrayHelper::toObject($shipping);
    }

    public static function getOrder($id, array $conditions = array()){
		JTable::addIncludePath(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'tables');
		$table      = JTable::getInstance('Orders', 'KsenMartTable');
		$conditions = array_merge(array('id' => $id), $conditions);

		$table->load($conditions);
		$order                    = $table->getProperties();
		$order['customer_fields'] = json_decode($order['customer_fields']);
		$order['address_fields']  = json_decode($order['address_fields']);

		return JArrayHelper::toObject($order);
    }

    public static function getRegion($id, array $conditions = array()){
		JTable::addIncludePath(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'tables');
		$table      = JTable::getInstance('Regions', 'KsenMartTable');
		$conditions = array_merge(array('id' => $id), $conditions);

		$table->load($conditions);
		$region = $table->getProperties();

		return JArrayHelper::toObject($region);
    }

    public static function getCountry($id, array $conditions = array()){
		JTable::addIncludePath(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'tables');
		$table      = JTable::getInstance('Countries', 'KsenMartTable');
		$conditions = array_merge(array('id' => $id), $conditions);

		$table->load($conditions);
		$country = $table->getProperties();

		return JArrayHelper::toObject($country);
    }
}