<?php defined('_JEXEC') or die('Restricted access');

if (!class_exists('KMPlugin'))
{
	require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}

class plgKMPluginsExportorders extends KMPlugin
{
	
	function __construct(&$subject, $config){
		parent::__construct($subject, $config);
	}

	function onAfterDisplayAdminKSMordersdefault_items_list_top($view, $name = '', &$html = ''){
		$html = str_replace('<div class="drag">', '<a class="export_orders">' . JText::_('KSM_PLUGIN_EXPORTORDERS_EXPORT_BUTTON') . '</a><div class="drag">', $html);
		$document = JFactory::getDocument();
		$document->addScript('../plugins/' . $this->_type . '/' . $this->_name . '/assets/js/default.js');
		$style = '
			.export_orders {
				float: right;
				padding: 11px;
				margin-left: 15px;
				font-size: 14px;
				background: #525252;
				text-decoration: none;
				color: white;
				height: 18px;
				cursor: pointer;
			}
			.export_orders:hover {
				text-decoration: none;
				color: #fff;
			}
		';
		$document->addStyleDeclaration($style);
	}

	/**
	 * @throws Exception
     */
	public function onAjaxExportOrdersGetCSV(){
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDBO();

		$statuses 	= $jinput->get('statuses', array());
		$statuses = array_filter($statuses, 'KSFunctions::filterArray');
		$searchword = $jinput->get('searchword', '');
		$order_dir 	= $jinput->get('order_dir', 'desc');
		$order_type = $jinput->get('order_type', 'date_add');
		$from_date 	= $jinput->get('from_date', '');
		$to_date 	= $jinput->get('to_date', '');

		$query = $db->getQuery(true);
		$query->select('id')->from('#__ksenmart_orders')->order($order_type . ' ' . $order_dir);
		if(count($statuses) > 0) $query->where('status_id in (' . implode(',', $statuses) . ')');
		if(!empty($searchword)) $query->where('customer_fields like ' . $db->quote('%' . $searchword . '%') . ' or address_fields like ' . $db->quote('%' . $searchword . '%') . ' or date_add like ' . $db->quote('%' . $searchword . '%'));
		if(!empty($from_date)) {
			$from_date = date('Y-m-d', strtotime($from_date)) . ' 00:00:00';
			$query->where('date_add>' . $db->quote($from_date));
		}
		if(!empty($to_date)) {
			$to_date = date('Y-m-d', strtotime($to_date)) . ' 23:59:59';
			$query->where('date_add<' . $db->quote($to_date));
		}
		$db->setQuery($query);
		$orders = $db->loadColumn();

		$header = array(
			JText::_('ksm_plugin_exportorders_order_id'),
			JText::_('ksm_plugin_exportorders_date_add'),
			JText::_('ksm_plugin_exportorders_customer_name'),
			JText::_('ksm_plugin_exportorders_customer_phone'),
			JText::_('ksm_plugin_exportorders_customer_mail'),
			JText::_('ksm_plugin_exportorders_address'),
			JText::_('ksm_plugin_exportorders_region'),
			JText::_('ksm_plugin_exportorders_shipping'),
			JText::_('ksm_plugin_exportorders_payment'),
			JText::_('ksm_plugin_exportorders_items'),
			JText::_('ksm_plugin_exportorders_cost'),
			JText::_('ksm_plugin_exportorders_discount_cost'),
			JText::_('ksm_plugin_exportorders_shipping_cost'),
			JText::_('ksm_plugin_exportorders_total_cost'),
		);

		$f = fopen(JPATH_ROOT.'/administrator/components/com_ksenmart/tmp/exportorders.csv', 'w');
		fputcsv($f, $header, ';');

		$regions = array();
		$shippings = array();
		$payments = array();
		$products = array();
		foreach($orders as $order_id){
			$order = KSMOrders::getOrder($order_id);
			$order->customer_name = '';
			if (isset($order->customer_fields->name) && !empty($order->customer_fields->name)) $order->customer_name.= $order->customer_fields->name;
			if (isset($order->customer_fields->last_name) && !empty($order->customer_fields->last_name)) $order->customer_name.= $order->customer_fields->last_name . ' ';
			if (isset($order->customer_fields->first_name) && !empty($order->customer_fields->first_name)) $order->customer_name.= $order->customer_fields->first_name . ' ';
			if (isset($order->customer_fields->middle_name) && !empty($order->customer_fields->middle_name)) $order->customer_name.= $order->customer_fields->middle_name;

			if(!isset($regions[$order->region_id])){
				$query = $db->getQuery(true);
				$query->select('title')->from('#__ksenmart_regions')->where('id=' . (int)$order->region_id);
				$db->setQuery($query);
				$regions[$order->region_id] = $db->loadResult();
			}

			if(!isset($shippings[$order->shipping_id])){
				$query = $db->getQuery(true);
				$query->select('title')->from('#__ksenmart_shippings')->where('id=' . (int)$order->shipping_id);
				$db->setQuery($query);
				$shippings[$order->shipping_id] = $db->loadResult();
			}

			if(!isset($payments[$order->payment_id])){
				$query = $db->getQuery(true);
				$query->select('title')->from('#__ksenmart_payments')->where('id=' . (int)$order->payment_id);
				$db->setQuery($query);
				$payments[$order->payment_id] = $db->loadResult();
			}

			$items_info = '';
			foreach($order->items as $item){
				if(!isset($products[$item->product_id])){
					$query = $db->getQuery(true);
					$query->select('p.title, u.form5 as unit')->from('#__ksenmart_products as p')->where('p.id=' . (int)$item->product_id);
					$query->leftJoin('#__ksenmart_product_units as u ON u.id=p.product_unit');
					$db->setQuery($query);
					$products[$item->product_id] = $db->loadObject();
				}
				$items_info .= $products[$item->product_id]->title . ' - ' . $item->count . ' ' . $products[$item->product_id]->unit . '\r\n';
			}

			$arr = array(
				$order->id,
				$order->date_add,
				$order->customer_name,
				$order->customer_fields->phone,
				$order->customer_fields->email,
				$order->address_fields,
				$regions[$order->region_id],
				$shippings[$order->shipping_id],
				$payments[$order->payment_id],
				$items_info,
				$order->costs['cost'],
				$order->costs['discount_cost'],
				$order->costs['shipping_cost'],
				$order->costs['total_cost']
			);
			fputcsv($f, $arr, ';');
		}
		fclose($f);
		//$contents = file_get_contents(JPATH_ROOT.'/administrator/components/com_ksenmart/tmp/exportorders.csv');
		//header('Content-Type: application/octet-stream');
		//header('Content-Disposition: attachment; filename="export.csv"');
		//echo $contents;
		echo '/administrator/components/com_ksenmart/tmp/exportorders.csv';
		$app = JFactory::getApplication();
		$app->close();
	}
}