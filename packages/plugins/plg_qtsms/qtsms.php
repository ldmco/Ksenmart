<?php defined('_JEXEC') or die('Restricted access');

if (!class_exists('KMPlugin'))
{
	require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}

class plgKMPluginsQtsms extends KMPlugin
{
	private $_order_id = 0;
	
	function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}
	
	function onAfterExecuteKSMCartcloseOrder($model = null){
		if(empty($model) || !isset($model->order_id)) return;
		
		$status_id = $this->params->get('status_id', 0);
		if($status_id == 1){
			$host = $this->params->get('host', '');
			$login = $this->params->get('login', '');
			$password = $this->params->get('password', '');
			$phone = $this->params->get('phone', '');
			$sender = $this->params->get('sender', '');
			if(empty($host) || empty($login) || empty($password) || empty($phone) || empty($sender)) return;
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('*')->from('#__ksenmart_orders')->where('id=' . (int)$model->order_id);
			$db->setQuery($query);
			$order = $db->loadObject();
			
			if(empty($order)) return;
			$order->customer_fields = json_decode($order->customer_fields, true);
			$name = $order->customer_fields['first_name'];
			$custom_phone = $order->customer_fields['phone'];
			
			$message = $this->params->get('message', '');
			$message = str_replace('{id}', $model->order_id, $message);
			$message = str_replace('{name}', $name, $message);
			$message = str_replace('{phone}', $custom_phone, $message);
			
			if (!class_exists('QTSMS')){
				require (JPATH_ROOT . DS . 'plugins' . DS . 'kmplugins' . DS . 'qtsms' . DS . '/helper/qtsms.php');
			}
			
			$sms = new QTSMS($login, $password, $host);
			$period = 600;
			
			$result = $sms->post_message($message, $phone, $sender, '', $period);
		}
	}
	
	function onBeforeExecuteKSMCartaddToCart($model = null){
		if(empty($model) || !isset($model->order_id)) return;
		
		$this->_order_id = $model->order_id;
	}
	
	function onAfterExecuteKSMCartaddToCart($model = null){
		if(empty($model) || !isset($model->order_id)) return;
		
		if($this->_order_id == 0 && $model->order_id > 0){
			$status_id = $this->params->get('status_id', 0);
			
			if($status_id == 2){
				$host = $this->params->get('host', '');
				$login = $this->params->get('login', '');
				$password = $this->params->get('password', '');
				$phone = $this->params->get('phone', '');
				$sender = $this->params->get('sender', '');
				if(empty($host) || empty($login) || empty($password) || empty($phone) || empty($sender)) return;
				$db = JFactory::getDBO();
				$query = $db->getQuery(true);
				$query->select('*')->from('#__ksenmart_orders')->where('id=' . (int)$model->order_id);
				$db->setQuery($query);
				$order = $db->loadObject();
				
				if(empty($order) || $order->status_id != 2) return;
				$order->customer_fields = json_decode($order->customer_fields, true);
				$name = $order->customer_fields['first_name'];
				$custom_phone = $order->customer_fields['phone'];
				
				$message = $this->params->get('message', '');
				$message = str_replace('{id}', $model->order_id, $message);
				$message = str_replace('{name}', $name, $message);
				$message = str_replace('{phone}', $custom_phone, $message);
				
				if (!class_exists('QTSMS')){
					require (JPATH_ROOT . DS . 'plugins' . DS . 'kmplugins' . DS . 'qtsms' . DS . '/helper/qtsms.php');
				}
				
				$sms = new QTSMS($login, $password, $host);
				$period = 600;
				
				$result = $sms->post_message($message, $phone, $sender, '', $period);
			}
		}
	}
	
	public function onAfterGetKSMFormInputOrderStatus_id(&$form, &$field_name, &$html){
		$html .= '<input id="sendsms" style="float: left; margin-right: 10px;" value="1" type="checkbox" name="sendSMS" /><label for="sendsms">' . JText::_('KSM_PLUGIN_QTSMS_SENDSMS_LBL') . '</label>';
	}
	
	function onAfterExecuteKSMOrdersSaveorder($model = null, $return = null){
		$jinput = JFactory::getApplication()->input;
		$sendsms = $jinput->get('sendSMS', null);
		if(isset($sendsms) && !empty($sendsms)){
			$host = $this->params->get('host', '');
			$login = $this->params->get('login', '');
			$password = $this->params->get('password', '');
			$phone = $this->params->get('phone', '');
			$sender = $this->params->get('sender', '');
			if(empty($host) || empty($login) || empty($password) || empty($phone) || empty($sender)) return;
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('o.*, os.title as status_title, os.system')->from('#__ksenmart_orders as o')->leftjoin('#__ksenmart_order_statuses as os ON os.id=o.status_id')->where('o.id=' . (int)$return['id']);
			$db->setQuery($query);
			$order = $db->loadObject();
			
			$order->customer_fields = json_decode($order->customer_fields, true);
			$name = $order->customer_fields['first_name'];
			$phone = $order->customer_fields['phone'];
			if(empty($phone)) return;
			$status = $order->system ? JText::_('ksm_orders_' . $order->status_title) : $order->status_title;
			
			$message = $this->params->get('message_client', '');
			$message = str_replace('{id}', $model->order_id, $message);
			$message = str_replace('{name}', $name, $message);
			$message = str_replace('{status}', $status, $message);
			if (!class_exists('QTSMS')){
				require (JPATH_ROOT . DS . 'plugins' . DS . 'kmplugins' . DS . 'qtsms' . DS . '/helper/qtsms.php');
			}
			
			$sms = new QTSMS($login, $password, $host);
			$period = 600;
			
			$result = $sms->post_message($message, $phone, $sender, '', $period);
		}
	}
}
