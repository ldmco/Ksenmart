<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if (!class_exists('KMPlugin')) {
	require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}
KSSystem::import('models.form.ksform');
jimport('joomla.log.log');

class plgKMExportimport1C_exchange extends KMPlugin {
	
	var $view = null;
	var $_log = null;
	private $_db = null;
	private $_properties = null;
	private $_categories = null;
	private $_currencies = null;
	
	function __construct(&$subject, $config) {
		$this->_db = JFactory::getDbo();
		parent::__construct($subject, $config);
	}

	function onAfterDisplayAdminKSMexportimportdefault_text($view, &$tpl, &$html){
		if ($this->_name != $view->type)
			return false;

		$this->view = $view;
		$jinput = JFactory::getApplication()->input;
		$step = $jinput->get('step', 'config');

		switch($step){
			case 'config':
				$html = $this->getConfigStep();
				break;
			case 'saveconfig':
				$this->saveConfig();
				$html = $this->getConfigStep();
				break;
		}

		return true;
	}

	function getConfigStep(){
		$html = KSSystem::loadPluginTemplate($this->_name, $this->_type, $this->view, 'config');

		return $html;
	}
	
	public function onBeforeViewKSMCatalog($view){
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$exchange = $jinput->get('1c_exchange', null);
		//if(!empty($exchange)){
			//$this->_log = &JLog::getInstance('my.log.php');
		JLog::addLogger(
			array('text_file' => 'exchange.log.php')
		);
		JLog::add(json_encode($_GET));
		$type = $jinput->get('type', '');
		$mode = $jinput->get('mode', '');
		if(!empty($type) && !empty($mode)){
			//$login = $_SERVER['PHP_AUTH_USER'];
			//$password = $_SERVER['PHP_AUTH_PW'];
			//if($login == 'admin' && $password == '12345'){
				header("Content-Type: text/html; charset=utf-8");
				switch($type){
					case 'catalog':
						switch($mode){
							case 'checkauth':
								$this->checkauth();
							break;
							case 'init':
								$this->cataloginit();
							break;
							case 'file':
								$this->catalogfile();
							break;
							case 'import':
								$this->catalogimport();
							break;
						}
					break;
					case 'sale':
						switch($mode){
							case 'checkauth':
								$this->checkauth();
							break;
							case 'init':
								$this->saleinit();
							break;
							case 'file':
								$this->catalogfile();
							break;
							case 'query':
								$this->salequery();
							break;
							case 'success':
								$this->catalogsuccess();
							break;
						}
					break;
				}
			/*} else {
				JLog::add("failure " . session_id());
				echo "failure\n";
				echo "incorrect login/password";
			}*/
			JFactory::getApplication()->close();
		}
		//}
	}
	
	private function checkauth(){
		JLog::add("auth " . session_id());
		echo "success\n";
		echo session_name()."\n";
		echo session_id();
		JFactory::getApplication()->close();
	}
	
	private function cataloginit(){
		JLog::add("init " . session_id());
		echo "zip=yes\n";
        echo "file_limit=15000000\n";
		JFactory::getApplication()->close();
	}
	
	private function saleinit(){
		JLog::add("init " . session_id());
		echo "zip=yes\n";
        echo "file_limit=5000000\n";
		echo session_id()."\n";
		echo "version=2.09";
		$_SESSION['exchange'] = array();
		$_SESSION['exchange']['step'] = 1;
		JFactory::getApplication()->close();
	}
	
	private function catalogfile(){
		JLog::add("file " . session_id());
		jimport('joomla.archive.zip');
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$filename = $jinput->get('filename', '');
		JLog::add($filename);
		if (function_exists("file_get_contents"))
			$data = file_get_contents("php://input");
		elseif (isset($HTTP_RAW_POST_DATA))
			$data = &$HTTP_RAW_POST_DATA;
		else
			$data = false;
		if(isset($data) && $data !== false && !empty($filename)){
			$new_file = JPATH_ROOT . '/plugins/kmexportimport/1C_exchange/import/' . $filename;
			$data_len = mb_strlen($data, 'latin1');
			$zip = new JArchiveZip;
			if ($fp = fopen($new_file, "ab")){
				$result = fwrite($fp, $data);
				if ($result === $data_len){
					if($zip->extract($new_file, JPATH_ROOT . '/plugins/kmexportimport/1C_exchange/import/')){
						echo "success";
					} else
						echo 'failure no extract';
				} else
					echo 'failure wrong length';
			} else
				echo 'failure no open file';
		}
		/*if(!empty($filename) && isset($_FILES[$filename])){
			copy($_FILES[$filename]['tmp_name'], JPATH_ROOT . '/plugins/kmexportimport/1C_exchange/import/' . $filename);
		}*/
		JFactory::getApplication()->close();
	}
	
	private function catalogimport(){
		JLog::add("import " . session_id());
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$filename = $jinput->get('filename', '');
		if(!empty($filename)){
			if(file_exists(JPATH_ROOT . '/plugins/kmexportimport/1C_exchange/import/' . $filename)){
				$xml = simplexml_load_file(JPATH_ROOT . '/plugins/kmexportimport/1C_exchange/import/' . $filename);
				if($xml){
					if(isset($xml->Классификатор)){
						foreach($xml->Классификатор->children() as $name=>$element){
							switch($name){
								case 'Группы':
									foreach($element->Группа as $category){
										$this->setCatChilds($category, 0);
									}
								case 'Свойства':
									foreach($element->Свойство as $property){
										$this->setProperty($property);
									}
								break;
							}
						}
					} elseif(isset($xml->Каталог)){
						foreach($xml->Каталог->Товары->Товар as $product){
							JLog::add('Add product');
							$this->setProduct($product);
						}
					} elseif(isset($xml->ПакетПредложений) && isset($xml->ПакетПредложений->Предложения)){
						foreach($xml->ПакетПредложений->Предложения->Предложение as $element){
							if(isset($element->Цены)){
								$this->setPrice($element);
							} elseif(isset($element->Остатки)) {
								$this->setStock($element);
							}
						}
					}
				}
			}
			//foreach($xml->Классификатор->Группы as $category){
				//var_dump($category);
			//}
			
		}
		echo "success";
		JFactory::getApplication()->close();
	}
	
	private function salequery(){
		if(!isset($_SESSION['exchange'])){
			$_SESSION['exchange'] = array();
			$_SESSION['exchange']['step'] = 1;
		}
		JLog::add('step' . $_SESSION['exchange']['step']);
		if($_SESSION['exchange']['step'] == 1){
			if($fp = fopen("php://output", "ab")){
				fwrite($fp, "<"."?xml version=\"1.0\" encoding=\"UTF-8\"?".">\n");
				fwrite($fp, "<КоммерческаяИнформация ВерсияСхемы=\"2.03\" ДатаФормирования=\"".date("Y-m-d")."T".date("H:i:s")."\">\n");
				$query = $this->_db->getQuery(true);
				$query->select('id')->from('#__ksenmart_orders');
				$this->_db->setQuery($query);
				$orders = $this->_db->loadColumn();
				if(!empty($orders)){
					$query = $this->_db->getQuery(true);
					$query->select('*')->from('#__ksenmart_currencies')->where("`default` = 1");
					$this->_db->setQuery($query);
					$currency = $this->_db->loadObject();
					if($currency->code == "RUR") $currency->code = "RUB";
					foreach($orders as $oid){
						$order = KSMOrders::getOrder($oid);
						//$order->items = KSMOrders::getOrderItems($oid);
						$flag = false;
						foreach ($order->items as &$item){
							if (!empty($item->properties)){
								$item->properties = json_decode($item->properties);
								foreach ($item->properties as $key => &$property){
									$query = $this->_db->getQuery(true);
									$query->select('
										o.properties,
										p.title AS prop_title,
										pv.title AS prop_value_title
									');
									$query->from('#__ksenmart_order_items AS o');
									$query->leftjoin('#__ksenmart_properties AS p ON p.id=' . $this->_db->q($key));
									$query->leftjoin('#__ksenmart_property_values AS pv ON pv.id=' . $this->_db->q($property->value_id));
									$query->where('o.order_id=' . $this->_db->q($oid));
									
									$this->_db->setQuery($query);
									
									$property_tmp = $this->_db->loadObject();
									$property->title = $property_tmp->prop_title;
									$property->value = $property_tmp->prop_value_title;
								}
								$query = $this->_db->getQuery(true);
								$query->select('*')->from('#__ksenmart_products')->where("id=" . $item->product_id);
								$this->_db->setQuery($query);
								$item->product = $this->_db->loadObject();
								if(empty($item->product)) $flag = true;
							}
						}
						if($flag) continue;
						unset($item);
						$query = $this->_db->getQuery(true);
						$query->select('title')->from('#__ksenmart_payments')->where("id=" . $order->payment_id);
						$this->_db->setQuery($query);
						$order->payment = $this->_db->loadResult();
						$query = $this->_db->getQuery(true);
						$query->select('*')->from('#__ksenmart_order_statuses')->where("id=" . $order->status_id);
						$this->_db->setQuery($query);
						$status = $this->_db->loadObject();
						if(!empty($status)) $order->status_name = $status->system ? JText::_('ksm_orders_' . $status->title) : $status->title;
						$query = $this->_db->getQuery(true);
						$query->select('title')->from('#__ksenmart_shippings')->where("id=" . $order->shipping_id);
						$this->_db->setQuery($query);
						$order->shipping = $this->_db->loadResult();
						$timest = strtotime($order->date_add);
						$date = date('Y-m-d', $timest);
						$time = date('H:i:s', $timest);
						//$order->customer_fields = json_decode($order->customer_fields, true);
						//$order->address_fields = json_decode($order->address_fields, true);
						fwrite($fp, "\t<Документ>\n");
						fwrite($fp, "\t\t<Ид>" . $order->id . "</Ид>\n");
						fwrite($fp, "\t\t<Номер>" . $order->id . "</Номер>\n");
						fwrite($fp, "\t\t<Дата>" . $date . "</Дата>\n");
						fwrite($fp, "\t\t<ХозОперация>Заказ товара</ХозОперация>\n");
						fwrite($fp, "\t\t<Роль>Продавец</Роль>\n");
						fwrite($fp, "\t\t<Валюта>" . $currency->code . "</Валюта>\n");
						fwrite($fp, "\t\t<Контрагенты>\n");
						fwrite($fp, "\t\t\t<Контрагент>\n");
						fwrite($fp, "\t\t\t\t<Ид></Ид>\n");
						fwrite($fp, "\t\t\t\t<Наименование>Розничный покупатель</Наименование>\n");
						fwrite($fp, "\t\t\t\t<Роль>Покупатель</Роль>\n");
						fwrite($fp, "\t\t\t\t<ПолноеНаименование>Розничный покупатель</ПолноеНаименование>\n");
						if(isset($order->customer_fields->last_name)){
							fwrite($fp, "\t\t\t\t<Фамилия>" . $order->customer_fields->last_name . "</Фамилия>\n");
						}
						if(isset($order->customer_fields->name)){
							fwrite($fp, "\t\t\t\t<Имя>" . $order->customer_fields->name . "</Имя>\n");
						}
						fwrite($fp, "\t\t\t</Контрагент>\n");
						fwrite($fp, "\t\t</Контрагенты>\n");
						fwrite($fp, "\t\t<Курс>" . $currency->rate . "</Курс>\n");
						fwrite($fp, "\t\t<Сумма>" . $order->cost . "</Сумма>\n");
						fwrite($fp, "\t\t<Время>" . $time . "</Время>\n");
						fwrite($fp, "\t\t<Комментарий>" . $order->note . "</Комментарий>\n");
						fwrite($fp, "\t\t<Товары>\n");
						foreach($order->items as $item){
							fwrite($fp, "\t\t\t<Товар>\n");
							if(empty($item->product->id_1c)) $item->product->id_1c = 'ks_' . $item->product->id;
							fwrite($fp, "\t\t\t\t<Ид>" . $item->product->id_1c . "</Ид>\n");
							fwrite($fp, "\t\t\t\t<Наименование>" . $item->product->title . "</Наименование>\n");
							fwrite($fp, "\t\t\t\t<БазоваяЕдиница Код=\"796\"  МеждународноеСокращение=\"PCE\" НаименованиеПолное=\"Штука\">шт.</БазоваяЕдиница>\n");
							fwrite($fp, "\t\t\t\t<ЦенаЗаЕдиницу>" . $item->price . "</ЦенаЗаЕдиницу>\n");
							fwrite($fp, "\t\t\t\t<Количество>" . $item->count . "</Количество>\n");
							fwrite($fp, "\t\t\t\t<Сумма>" . $item->price*$item->count . "</Сумма>\n");
							fwrite($fp, "\t\t\t\t<ЗначенияРеквизитов>\n");
							fwrite($fp, "\t\t\t\t\t<ЗначениеРеквизита>\n");
							fwrite($fp, "\t\t\t\t\t\t<Наименование>ВидНоменклатуры</Наименование>\n");
							fwrite($fp, "\t\t\t\t\t\t<Значение>Товар</Значение>\n");
							fwrite($fp, "\t\t\t\t\t</ЗначениеРеквизита>\n");
							fwrite($fp, "\t\t\t\t\t<ЗначениеРеквизита>\n");
							fwrite($fp, "\t\t\t\t\t\t<Наименование>ТипНоменклатуры</Наименование>\n");
							fwrite($fp, "\t\t\t\t\t\t<Значение>Товар</Значение>\n");
							fwrite($fp, "\t\t\t\t\t</ЗначениеРеквизита>\n");
							fwrite($fp, "\t\t\t\t</ЗначенияРеквизитов>\n");
							fwrite($fp, "\t\t\t</Товар>\n");
						}
						fwrite($fp, "\t\t\t<Товар>\n");
						fwrite($fp, "\t\t\t\t<Ид>ORDER_DELIVERY</Ид>\n");
						fwrite($fp, "\t\t\t\t<Наименование>Доставка заказа</Наименование>\n");
						fwrite($fp, "\t\t\t\t<БазоваяЕдиница НаименованиеПолное=\"Штука\" МеждународноеСокращение=\"PCE\">шт.</БазоваяЕдиница>\n");
						fwrite($fp, "\t\t\t\t<ЦенаЗаЕдиницу>" . $order->costs['shipping_cost'] . "</ЦенаЗаЕдиницу>\n");
						fwrite($fp, "\t\t\t\t<Количество>1</Количество>\n");
						fwrite($fp, "\t\t\t\t<Сумма>" . $order->costs['shipping_cost'] . "</Сумма>\n");
						fwrite($fp, "\t\t\t\t<ЗначенияРеквизитов>\n");
						fwrite($fp, "\t\t\t\t\t<ЗначениеРеквизита>\n");
						fwrite($fp, "\t\t\t\t\t\t<Наименование>ВидНоменклатуры</Наименование>\n");
						fwrite($fp, "\t\t\t\t\t\t<Значение>Услуга</Значение>\n");
						fwrite($fp, "\t\t\t\t\t</ЗначениеРеквизита>\n");
						fwrite($fp, "\t\t\t\t\t<ЗначениеРеквизита>\n");
						fwrite($fp, "\t\t\t\t\t\t<Наименование>ТипНоменклатуры</Наименование>\n");
						fwrite($fp, "\t\t\t\t\t\t<Значение>Услуга</Значение>\n");
						fwrite($fp, "\t\t\t\t\t</ЗначениеРеквизита>\n");
						fwrite($fp, "\t\t\t\t</ЗначенияРеквизитов>\n");
						fwrite($fp, "\t\t\t</Товар>\n");
						fwrite($fp, "\t\t</Товары>\n");
						fwrite($fp, "\t\t<ЗначенияРеквизитов>\n");
						fwrite($fp, "\t\t\t<ЗначениеРеквизита>\n");
						fwrite($fp, "\t\t\t\t<Наименование>Метод оплаты</Наименование>\n");
						fwrite($fp, "\t\t\t\t<Значение>" . $order->payment . "</Значение>\n");
						fwrite($fp, "\t\t\t</ЗначениеРеквизита>\n");
						fwrite($fp, "\t\t\t<ЗначениеРеквизита>\n");
						fwrite($fp, "\t\t\t\t<Наименование>Статус заказа</Наименование>\n");
						fwrite($fp, "\t\t\t\t<Значение>" . $order->status_name . "</Значение>\n");
						fwrite($fp, "\t\t\t</ЗначениеРеквизита>\n");
						fwrite($fp, "\t\t\t<ЗначениеРеквизита>\n");
						fwrite($fp, "\t\t\t\t<Наименование>Способ доставки</Наименование>\n");
						fwrite($fp, "\t\t\t\t<Значение>" . $order->shipping . "</Значение>\n");
						fwrite($fp, "\t\t\t</ЗначениеРеквизита>\n");
						fwrite($fp, "\t\t</ЗначенияРеквизитов>\n");
						fwrite($fp, "\t</Документ>\n");
					}
				}
				fwrite($fp, "</КоммерческаяИнформация>\n");
				$_SESSION['exchange']['step'] = 2;
			}
		} elseif($_SESSION['exchange']['step'] == 2) {
			if($fp = fopen("php://output", "ab")){
				$query = $this->_db->getQuery(true);
				$query->select('*')->from('#__ksenmart_products')->where('published=1');
				$this->_db->setQuery($query);
				$products = $this->_db->loadObjectList();
				if(!empty($products)){
					fwrite($fp, "<"."?xml version=\"1.0\" encoding=\"UTF-8\"?".">\n");
					fwrite($fp, "<КоммерческаяИнформация xmlns=\"urn:1C.ru:commerceml_2\" xmlns:xs=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" ВерсияСхемы=\"2.09\" ДатаФормирования=\"".date("Y-m-d")."T".date("H:i:s")."\">\n");
					fwrite($fp, "\t<Каталог СодержитТолькоИзменения=\"true\">\n");
					fwrite($fp, "\t\t<Наименование>Основной каталог товаров</Наименование>\n");
					fwrite($fp, "\t\t<Товары>\n");
					foreach($products as $product){
						if(empty($product->id_1c)) continue;
						fwrite($fp, "\t\t\t<Товар>\n");
						if(empty($product->id_1c)) $product->id_1c = 'ks_' . $product->id;
						fwrite($fp, "\t\t\t\t<Ид>" . $product->id_1c . "</Ид>\n");
						if(empty($product->product_code)){
							fwrite($fp, "\t\t\t\t<Артикул>" . $product->product_code . "</Артикул>\n");
						}
						fwrite($fp, "\t\t\t\t<Наименование>" . $product->title . "</Наименование>\n");
						$query = $this->_db->getQuery(true);
						$query->select('c.id_1c')->from('#__ksenmart_categories as c')->leftjoin('#__ksenmart_products_categories as pc on c.id=pc.category_id')->where('pc.product_id=' . $product->id);
						$this->_db->setQuery($query);
						$pcats = $this->_db->loadColumn();
						if(!empty($pcats)){
							fwrite($fp, "\t\t\t\t<Группы>\n");
							foreach($pcats as $pcat){
								fwrite($fp, "\t\t\t\t\t<Ид>" . $pcat . "</Ид>\n");
							}
							fwrite($fp, "\t\t\t\t</Группы>\n");
						}
						fwrite($fp, "\t\t\t\t<Описание>" . htmlentities($product->content) . "</Описание>\n");
						$query = $this->_db->getQuery(true);
						$query->select('*')->from('#__ksenmart_files')->where('owner_id=' . $product->id)->where("owner_type='product'")->order('ordering');
						$this->_db->setQuery($query,0,1);
						$image = $this->_db->loadObject();
						if(!empty($image)){
							fwrite($fp, "\t\t\t\t<Картинка>" . JURI::root() . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . 'products' . DS . 'original' . DS . $image->filename . "</Картинка>\n");
						}
						/*$query = $this->_db->getQuery(true);
						$query->select('p.*,ppv.')->from('#__ksenmart_properties as p')->leftjoin('#__ksenmart_product_properties_values as ppv on p.id=ppv.property_id')->where('ppv.product_id=' . $product->id);
						$this->_db->setQuery($query);
						$pcats = $this->_db->loadObjectList();*/
						$properties = $this->getProductProperties($product->id);
						if(!empty($properties)){
							fwrite($fp, "\t\t\t\t<ЗначенияСвойств>\n");
							foreach($properties as $property){
								fwrite($fp, "\t\t\t\t\t<ЗначенияСвойства>\n");
								fwrite($fp, "\t\t\t\t\t\t<Ид>" . $property->id_1c . "</Ид>\n");
								if($property->type != 'select'){
									fwrite($fp, "\t\t\t\t\t\t<Значение>" . $property->text . "</Значение>\n");
								} else {
									fwrite($fp, "\t\t\t\t\t\t<Значение/>\n");
								}
								fwrite($fp, "\t\t\t\t\t</ЗначенияСвойства>\n");
							}
							fwrite($fp, "\t\t\t\t</ЗначенияСвойств>\n");
						}
						fwrite($fp, "\t\t\t\t<ЗначенияРеквизитов>\n");
						fwrite($fp, "\t\t\t\t\t<ЗначениеРеквизита>\n");
						fwrite($fp, "\t\t\t\t\t\t<Наименование>ВидНоменклатуры</Наименование>\n");
						fwrite($fp, "\t\t\t\t\t\t<Значение>Товар</Значение>\n");
						fwrite($fp, "\t\t\t\t\t</ЗначениеРеквизита>\n");
						fwrite($fp, "\t\t\t\t\t<ЗначениеРеквизита>\n");
						fwrite($fp, "\t\t\t\t\t\t<Наименование>ТипНоменклатуры</Наименование>\n");
						fwrite($fp, "\t\t\t\t\t\t<Значение>Товар</Значение>\n");
						fwrite($fp, "\t\t\t\t\t</ЗначениеРеквизита>\n");
						fwrite($fp, "\t\t\t\t\t<ЗначениеРеквизита>\n");
						fwrite($fp, "\t\t\t\t\t\t<Наименование>Полное наименование</Наименование>\n");
						fwrite($fp, "\t\t\t\t\t\t<Значение>" . $product->title . "</Значение>\n");
						fwrite($fp, "\t\t\t\t\t</ЗначениеРеквизита>\n");
						fwrite($fp, "\t\t\t\t</ЗначенияРеквизитов>\n");
						fwrite($fp, "\t\t\t</Товар>\n");
					}
					fwrite($fp, "\t\t</Товары>\n");
					fwrite($fp, "\t\t<Описание>Основной каталог товаров</Описание>\n");
					fwrite($fp, "\t</Каталог>\n");
					fwrite($fp, "</КоммерческаяИнформация>\n");
				}
				$_SESSION['exchange']['step'] = 3;
			}
		} else {
			//$_SESSION['exchange']['step'] = 2;
			echo "finished=yes\n";
		}
	}
	
	private function catalogsuccess(){
		echo "success\n";
	}
	
	protected function setStock($stock){
		$query = $this->_db->getQuery(true);
		$query->update('#__ksenmart_products')->set('in_stock=' . (int)$stock->Остатки->Остаток->Склад->Количество)->where('id_1c=' . $this->_db->quote($stock->Ид));
		$this->_db->setQuery($query);
		$this->_db->Query();
	}
	
	protected function setPrice($price){
		if(empty($this->_currencies)){
			$query = $this->_db->getQuery(true);
			$query->select('*')->from('#__ksenmart_currencies');
			$this->_db->setQuery($query);
			$this->_currencies = $this->_db->loadObjectList();
		}
		$cur_id = 1;
		if(!empty($price->Цены->Цена->Валюта)){
			foreach($this->_currencies as $currency){
				if($currency->code == trim($price->Цены->Цена->Валюта)){
					$cur_id == $currency->id;
				}
			}
		}
		$query = $this->_db->getQuery(true);
		$query->update('#__ksenmart_products')->set('price_type=' . $cur_id)->set('price=' . (int)$price->Цены->Цена->ЦенаЗаЕдиницу)->where('id_1c=' . $this->_db->quote($price->Ид));
		$this->_db->setQuery($query);
		$this->_db->Query();
	}
	
	protected function setProduct($product){
		if(!empty($product->Наименование)){
			$query = $this->_db->getQuery(true);
			$query->select('id')->from('#__ksenmart_products')->where('title=' . $this->_db->q($product->Наименование));
			$this->_db->setQuery($query);
			$pid = $this->_db->loadResult();
			
			if(empty($pid)){
				$alias = KSFunctions::GenAlias($product->Наименование);
				$product_code = '';
				if(isset($product->Артикул) && !empty($product->Артикул)) $product_code = $product->Артикул;
				$qvalues = array(
					$this->_db->quote($product->Наименование),
					$this->_db->quote($alias),
					$this->_db->quote($product_code),
					$this->_db->quote($product->Описание),
					$this->_db->quote('product'),
					'NOW()',
					1,
					$this->_db->quote($product->Ид)
				);
				$query = $this->_db->getQuery(true);
				$query->update('#__ksenmart_products')->set('ordering=ordering+1');
				$this->_db->setQuery($query);
				$this->_db->query();
				$query = $this->_db->getQuery(true);
				$query->insert('#__ksenmart_products')->columns('title,alias,product_code,content,type,date_added,published,id_1c')->values(implode(',', $qvalues));
				$this->_db->setQuery($query);
				$this->_db->Query();
				$pid = $this->_db->insertid();
			} else {
				$to_update = array();
                $to_update[] = 'date_added=NOW()';
				$to_update[] = 'title=' . $this->_db->quote($product->Наименование);
				if(isset($product->Артикул) && !empty($product->Артикул)) $to_update[] = 'product_code=' . $this->_db->quote($product->Артикул);
				$to_update[] = 'content=' . $this->_db->quote($product->Описание);
				$to_update[] = 'id_1c=' . $this->_db->quote($product->Ид);
				
				$query = $this->_db->getQuery(true);
				$query->update('#__ksenmart_products')->set($to_update)->where('id=' . $pid);
				$this->_db->setQuery($query);
				$this->_db->Query();
			}
			if(isset($product->Группы)){
				$cats = array();
				$query = $this->_db->getQuery(true);
				$query->select('category_id')->from('#__ksenmart_products_categories')->where('product_id=' . $pid);
				$this->_db->setQuery($query);
				$cats = $this->_db->loadColumn();
				if(empty($cats)){
					$is_default = true;
				} else {
					$is_default = false;
				}
				
				foreach($product->Группы->Ид as $cat){
					$query = $this->_db->getQuery(true);
					$query->select('id')->from('#__ksenmart_categories')->where('id_1c=' . $this->_db->q($cat));
					$this->_db->setQuery($query);
					$cid = $this->_db->loadResult();
					if(!empty($cid)){
						if(empty($cats) || !in_array($cid, $cats)){
							$cats[] = $cid;
							$query = $this->_db->getQuery(true);
							$query->insert('#__ksenmart_products_categories')->columns('product_id,category_id,is_default')->values($pid . ',' . $cid . ',' . (int)$is_default);
							$this->_db->setQuery($query);
							$this->_db->Query();
							$is_default = false;
						}
					}
				}
				if(count($cats) > 0){
					$query = $this->_db->getQuery(true);
					$query->delete('#__ksenmart_products_categories')->where('product_id=' . $pid)->where('category_id not in (' . implode(',', $cats) . ')');
					$this->_db->setQuery($query);
					$this->_db->Query();
				}
			}
			if(isset($product->ЗначенияСвойств)){
				foreach($product->ЗначенияСвойств->ЗначенияСвойства as $property){
					$query = $this->_db->getQuery(true);
					$query->select('id,type')->from('#__ksenmart_properties')->where('id_1c=' . $this->_db->q($property->Ид));
					$this->_db->setQuery($query);
					$prop = $this->_db->loadObject();
					
					if(isset($prop)){
						if($prop->type == 'text'){
							if(!empty($property->Значение)){
								$query = $this->_db->getQuery(true);
								$query->select('*')->from('#__ksenmart_property_values')->where('property_id=' . $prop->id)->where('title=' . $this->_db->quote($property->Значение));
								$this->_db->setQuery($query);
								$prop_value = $this->_db->loadObject();
								if(count($prop_value) == 0) {
									$alias = KSFunctions::GenAlias($property->Значение);
									$qvalues = array(
										$prop->id,
										$this->_db->quote($property->Значение),
										$this->_db->quote($alias),
										$this->_db->quote($property->Ид));
									$query = $this->_db->getQuery(true);
									$query->insert('#__ksenmart_property_values')->columns('property_id,title,alias,id_1c')->values(implode(',', $qvalues));
									$this->_db->setQuery($query);
									$this->_db->query();
									$prop_value_id = $this->_db->insertid();
								} else  $prop_value_id = $prop_value->id;
								$query = $this->_db->getQuery(true);
								$query->select('*')->from('#__ksenmart_product_properties_values')->where('product_id=' . $pid)->where('property_id=' . $prop->id)->where('value_id=' . $prop_value_id);
								$this->_db->setQuery($query);
								$prod_prop_value = $this->_db->loadObject();
								if(count($prod_prop_value) == 0) {
									$qvalues = array(
										$pid,
										$prop->id,
										$prop_value_id,
										$this->_db->quote($property->Значение));
									$query = $this->_db->getQuery(true);
									$query->insert('#__ksenmart_product_properties_values')->columns('product_id,property_id,value_id,text')->values(implode(',', $qvalues));
									$this->_db->setQuery($query);
									$this->_db->query();
								}
							}
						} else {
							$query = $this->_db->getQuery(true);
							$query->select('*')->from('#__ksenmart_property_values')->where('property_id=' . $prop->id)->where('id_1c=' . $this->_db->quote($property->Значение));
							$this->_db->setQuery($query);
							$prop_value = $this->_db->loadObject();
							if(count($prop_value) == 0) {
								$alias = KSFunctions::GenAlias($property->Значение);
								$qvalues = array(
									$prop->id,
									$this->_db->quote($property->Значение),
									$this->_db->quote($alias));
								$query = $this->_db->getQuery(true);
								$query->insert('#__ksenmart_property_values')->columns('property_id,title,alias')->values(implode(',', $qvalues));
								$this->_db->setQuery($query);
								$this->_db->query();
								$prop_value_id = $this->_db->insertid();
							} else  $prop_value_id = $prop_value->id;
							$query = $this->_db->getQuery(true);
							$query->select('*')->from('#__ksenmart_product_properties_values')->where('product_id=' . $pid)->where('property_id=' . $prop->id)->where('value_id=' . $prop_value_id);
							$this->_db->setQuery($query);
							$prod_prop_value = $this->_db->loadObject();
							if(count($prod_prop_value) == 0) {
								$qvalues = array(
									$pid,
									$prop->id,
									$prop_value_id);
								$query = $this->_db->getQuery(true);
								$query->insert('#__ksenmart_product_properties_values')->columns('product_id,property_id,value_id')->values(implode(',', $qvalues));
								$this->_db->setQuery($query);
								$this->_db->query();
							}
						}
					}
				}
			}
			if(isset($product->Картинка)){
				$query = $this->_db->getQuery(true);
				$query->select('*')->from('#__ksenmart_files')->where(array(
					"media_type='image'",
					"owner_type='product'",
					"owner_id=" . $pid))->order('ordering');
				$this->_db->setQuery($query);
				$images = $this->_db->loadObjectList('id');
				$i = count($images);
				foreach($images as $image) $this->delPhoto($image->filename, $image->folder);
				$i = 1;
				foreach($product->Картинка as $photo){
					$photo = trim($photo);
					if(!empty($photo)){
						$file = basename($photo);
						$nameParts = explode('.', $file);
						$file = microtime(true) . '.' . $nameParts[count($nameParts) - 1];	
						$copied = false;
						if (strpos($photo, 'http://') !== false){
							if($photo_content = file_get_contents($photo)) {
								if (file_put_contents(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . 'products' . DS . 'original' . DS . $file, $photo_content)){
									$copied = true;
								}
							}						
						} else {
							if(file_exists(JPATH_ROOT . DS . 'plugins' . DS . 'kmexportimport' . DS . '1C_exchange' . DS . 'import' . DS . $photo)){
								if(copy(JPATH_ROOT . DS . 'plugins' . DS . 'kmexportimport' . DS . '1C_exchange' . DS . 'import' . DS . $photo, JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . 'products' . DS . 'original' . DS .  $file)){
									$copied = true;
								}
							}
						}
						if ($copied){
							$mime = mime_content_type(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . 'products' . DS . 'original' . DS .  $file);
							$qvalues = array(
								$pid,
								$this->_db->quote('image'),
								$this->_db->quote('product'),
								$this->_db->quote('products'),
								$this->_db->quote($file),
								$this->_db->quote($mime),
								$this->_db->quote(''),
								$i);
							$query = $this->_db->getQuery(true);
							$query->insert('#__ksenmart_files')->columns('owner_id,media_type,owner_type,folder,filename,mime_type,title,ordering')->values(implode(',', $qvalues));
							$this->_db->setQuery($query);
							$this->_db->query();
							$i++;							
						}
					}
				}
			}
		}
	}
	
	protected function setProperty($property){
		$title = trim($property->Наименование);
		if(!empty($title)){
			$query = $this->_db->getQuery(true);
			$query->select('id, id_1c')->from('#__ksenmart_properties')->where('title=' . $this->_db->q($title));
			$this->_db->setQuery($query);
			$prop = $this->_db->loadObject();
			
			if(empty($prop)){
				$alias = KSFunctions::GenAlias($title);
				if($property->ТипЗначений == 'Строка'){
					$type = 'text';
					$view = 'text';
				} else {
					$type = 'select';
					$view = 'select';
				}
				$qvalues = array(
					$this->_db->quote($title),
					$this->_db->quote($alias),
					$this->_db->quote($type),
					$this->_db->quote($view),
					1,
					$this->_db->quote($property->Ид)
				);
				$query = $this->_db->getQuery(true);
				$query->insert('#__ksenmart_properties')->columns('title,alias,type,view,published,id_1c')->values(implode(',', $qvalues));
				$this->_db->setQuery($query);
				$this->_db->Query();
				$pid = $this->_db->insertid();
				
				if(empty($this->_categories)){
					$query = $this->_db->getQuery(true);
					$query->select('id')->from('#__ksenmart_categories');
					$this->_db->setQuery($query);
					$this->_categories = $this->_db->loadColumn();
				}
				foreach($this->_categories as $category){
					$query = $this->_db->getQuery(true);
					$query->insert('#__ksenmart_product_categories_properties')->columns('category_id,property_id')->values($category . ',' . $pid);
					$this->_db->setQuery($query);
					$this->_db->Query();
				}
			} else {
				if($prop->id_1c != $property->Ид){
					$query = $this->_db->getQuery(true);
					$query->update('#__ksenmart_properties')->set('id_1c=' . $this->_db->quote($property->Ид))->where('id=' . $prop->id);
					$this->_db->setQuery($query);
					$this->_db->Query();
				}
				$pid = $prop->id;
			}
			
			if(isset($property->ВариантыЗначений)){
				foreach($property->ВариантыЗначений->Справочник as $value){
					if(!empty($value->Значение)){
						$query = $this->_db->getQuery(true);
						$query->select('id,id_1c')->from('#__ksenmart_property_values')->where('title=' . $this->_db->q($value->Значение))->where('property_id=' . $pid);
						$this->_db->setQuery($query);
						$oval = $this->_db->loadObject();
						
						if(empty($oval)){
							$alias = KSFunctions::GenAlias($value->Значение);
							$qvalues = array(
								$this->_db->quote($value->Значение),
								$this->_db->quote($alias),
								$pid,
								$this->_db->quote($value->ИдЗначения)
							);
							$query = $this->_db->getQuery(true);
							$query->insert('#__ksenmart_property_values')->columns('title,alias,property_id,id_1c')->values(implode(',', $qvalues));
							$this->_db->setQuery($query);
							$this->_db->Query();
						} else {
							if($oval->id_1c != $value->ИдЗначения){
								$query = $this->_db->getQuery(true);
								$query->update('#__ksenmart_property_values')->set('id_1c=' . $this->_db->quote($value->ИдЗначения))->where('id=' . $oval->id);
								$this->_db->setQuery($query);
								$this->_db->Query();
							}
						}
					}
				}
			}
		}
	}
	
	protected function setCatChilds($cat, $parent){
		if(!empty($cat->Наименование)){
			$query = $this->_db->getQuery(true);
			$query->select('id, id_1c')->from('#__ksenmart_categories')->where('title=' . $this->_db->q($cat->Наименование))->where('parent_id=' . (int)$parent);
			$this->_db->setQuery($query);
			$ocat = $this->_db->loadObject();
			
			if(empty($ocat)){
				$alias = KSFunctions::GenAlias($cat->Наименование);
				$qvalues = array(
					$this->_db->quote($cat->Наименование),
					$this->_db->quote($alias),
					$parent,
					$this->_db->quote($cat->Ид)
				);
				$query = $this->_db->getQuery(true);
				$query->insert('#__ksenmart_categories')->columns('title,alias,parent_id,id_1c')->values(implode(',', $qvalues));
				$this->_db->setQuery($query);
				$this->_db->Query();
				$cid = $this->_db->insertid();
				
				if(empty($this->_properties)){
					$query = $this->_db->getQuery(true);
					$query->select('id')->from('#__ksenmart_properties');
					$this->_db->setQuery($query);
					$this->_properties = $this->_db->loadColumn();
				}
				foreach($this->_properties as $property){
					$query = $this->_db->getQuery(true);
					$query->insert('#__ksenmart_product_categories_properties')->columns('category_id,property_id')->values($cid . ',' . $property);
					$this->_db->setQuery($query);
					$this->_db->Query();
				}
			} else {
				if($ocat->id_1c != $cat->Ид){
					$query = $this->_db->getQuery(true);
					$query->update('#__ksenmart_categories')->set('id_1c=' . $this->_db->quote($cat->Ид))->where('id=' . $ocat->id);
					$this->_db->setQuery($query);
					$this->_db->Query();
				}
				$cid = $ocat->id;
			}
			if(isset($cat->Группы)){
				foreach($cat->Группы->Группа as $ccat){
					$this->setCatChilds($ccat, $cid);
				}
			}
		}
	}
	
	private function delPhoto($filename, $folder) {
		$db = JFactory::getDBO();
        $files = scandir(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS . $folder);
        foreach($files as $file) {
            if($file != '.' && $file != '..' && is_dir(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS .  $folder .  DS . $file))
                if(file_exists(JPATH_ROOT  . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS  . $folder  . DS .  $file . DS . $filename)) unlink(JPATH_ROOT . DS . 'media' . DS . 'com_ksenmart' . DS . 'images' . DS  . $folder . DS . $file . DS . $filename);
        }
        $query = $db->getQuery(true);
        $query->delete('#__ksenmart_files');
        $where = array("filename='$filename'");
        $query->where($where);
        $db->setQuery($query);
        $db->query();

        return true;
    }	
	
	private function getProductProperties($pid){
		$query = $this->_db->getQuery(true);
        $query->select('
                ppv.id,
                ppv.property_id,
                ppv.value_id,
                ppv.text,
                pv.id_1c,
                p.edit_price,
                p.title,
                p.type,
                p.view
            ')->from('#__ksenmart_properties AS p')->leftjoin('#__ksenmart_product_properties_values AS ppv ON p.id=ppv.property_id')->leftjoin('#__ksenmart_property_values AS pv ON p.id=pv.property_id')->group('p.id');
            //')->from('#__ksenmart_properties AS p')->innerjoin('#__ksenmart_product_properties_values AS ppv ON p.id=ppv.property_id')->innerjoin('#__ksenmart_property_values AS pv ON p.id=pv.property_id');
        if ($pid) {
            $query->where('ppv.product_id=' . $pid);
        }
        
        /*if ($by_sort) {
            switch ($by) {
                case 'ppv.id':
                    $query->where('ppv.id=' . $by_sort);
                break;
                default:
                    $query->where('ppv.product_id=' . $pid);
                break;
            }
        }
        $query->where('p.published=1')->group('ppv.property_id');
        
        if ($prid) {
            $query->where('ppv.property_id=' . $prid);
        }*/
        
        $query->order('p.ordering');
        $this->_db->setQuery($query);
        $properties = $this->_db->loadObjectList();
        //$properties = KSMProducts::getPropertiesChild($pid, $properties, $val_id);
		
		return $properties;
	}
	
	/*function onAfterDisplayAdminKSMexportimportdefault_text($view, &$tpl, &$html){
		if ($this->_name != $view->type)
			return false;
		
		$this->view = $view;
		$jinput = JFactory::getApplication()->input;
		$step = $jinput->get('step', 'config');

		switch($step){
			case 'config':
				$html = $this->getConfigStep();
				break;
		}

		return true;
	}
	
	function onAfterViewAdminKSMExportimport($view){
		if ($this->_name != $view->type)
			return false;
		
		$this->view = $view;
		$jinput = JFactory::getApplication()->input;
		$step = $jinput->get('step', 'config');

		switch($step){
			case 'export':
				$html = $this->getExportStep();				
				break;
		}

		return true;
	}	
	
	function getConfigStep(){
		$this->view->form = $this->getForm();
		$html = KSSystem::loadPluginTemplate($this->_name, $this->_type, $this->view, 'config');
		
		return $html;
	}

    function getForm() {
        
        JKSForm::addFormPath(JPATH_ROOT.'/plugins/kmexportimport/export_csv/assets/forms');
        JKSForm::addFieldPath(JPATH_ROOT.'/administrator/components/com_ksenmart/models/fields');
        
        $form = JKSForm::getInstance('com_ksenmart.exportcsv', 'exportcsv', array(
            'control' => 'jform',
            'load_data' => true
        ));
        
        if (empty($form)) 
			return false;
        
        return $form;
    }*/
	
}