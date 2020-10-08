<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if (!class_exists('KMPlugin')) {
    require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_ksenmart'.DS.'classes'.DS.'kmplugin.php');
}
KSSystem::import('models.form.ksform');

class plgKMExportimportExport_ym_extend extends KMPlugin
{

    var $view = null;
    protected $db;
    protected $_discounts = array();
    private $_city = null;
    private $_region_id = null;
    private $_paymentAllow = false;

    function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);
    }

    function onAfterDisplayAdminKSMexportimportdefault_text($view, &$tpl, &$html)
    {
        if ($this->_name != $view->type) {
            return false;
        }

        $this->view = $view;
        $jinput     = JFactory::getApplication()->input;
        $step       = $jinput->get('step', 'config');

        switch ($step) {
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

    function ApiYM()
    {
        jimport('joomla.log.log');
        JLog::addLogger(
            array('text_file' => 'exchange.log.php')
        );
        $jinput = JFactory::getApplication()->input;
        $vars   = $jinput->get('pluginvars', array(), 'ARRAY');
        $app    = JFactory::getApplication();
        if (!empty($vars)) {
            $json = file_get_contents('php://input');
            JLog::add(json_encode($vars));
            JLog::add($json);
            /*$token = $jinput->get('auth-token', '', 'string');
            if($token != '77000001D055C601') JError::raiseError(403);
            if($vars[0] == 'cart'){

            }*/
            $json = file_get_contents("php://input");
            $json = '{"cart":{"currency":"RUR","items":[{"feedId":441423,"offerId":"1667","feedCategoryId":"41","offerName":"Гироскутер Smart Balance 6,5 дюйма с колонками (Граффити) (1)","count":1}],"delivery":{"region":{"id":213,"name":"Москва","type":"CITY","parent":{"id":1,"name":"Москва и Московская область","type":"SUBJECT_FEDERATION","parent":{"id":3,"name":"Центральный федеральный округ","type":"COUNTRY_DISTRICT","parent":{"id":225,"name":"Россия","type":"COUNTRY"}}}}}}}';
            //$json = '{"cart":{"currency":"RUR","items":[{"feedId":441423,"offerId":"1667","feedCategoryId":"41","offerName":"Гироскутер Smart Balance 6,5 дюйма с колонками (Граффити) (1)","count":1}],"delivery":{"region":{"id":39,"name":"Ростов-на-Дону","type":"CITY","parent":{"id":121146,"name":"Городской округ Ростов-на-Дону","type":"SUBJECT_FEDERATION_DISTRICT","parent":{"id":11029,"name":"Ростовская область","type":"SUBJECT_FEDERATION","parent":{"id":26,"name":"Южный федеральный округ","type":"COUNTRY_DISTRICT","parent":{"id":225,"name":"Россия","type":"COUNTRY"}}}}}}}}';
            if (!$json) {
                JError::raiseError(404);
            }
            $data       = json_decode($json);
            $payments   = array();
            $items      = array();
            $currencies = array();

            $cart_currency = ($data->cart->currency == 'RUR') ? 'RUB' : $data->cart->currency;
            $query         = $this->db->getQuery(true);
            $query->select('id')->from('#__ksenmart_currencies')->where('code='.$this->db->q($cart_currency));
            $this->db->setQuery($query);
            $cart_id_currency = $this->db->loadResult();

            $query = $this->db->getQuery(true);
            $query->select('value')->from('#__ksenmart_yandeximport')->where('setting = '.$this->db->quote('discounts'));
            $this->db->setQuery($query);
            $discounts = $this->db->loadResult();

            $discounts = json_decode($discounts, true);
            if (!is_array($discounts)) {
                $discounts = array();
            }

            if (count($discounts)) {
                $query = $this->db->getQuery(true);
                $query->select('*')->from('#__ksenmart_discounts')->where('id in ('.implode(',', $discounts).')');
                $this->db->setQuery($query);
                $this->_discounts = $this->db->loadObjectList('id');
            }
            foreach ($data->cart->items as $item) {
                $add        = true;
                $product_id = $item->offerId;

                $query = $this->db->getQuery(true);
                $query->select('p.*,pc.category_id')->from('#__ksenmart_products as p');
                $query->innerjoin('#__ksenmart_products_categories as pc on pc.product_id=p.id');
                $query->where('p.published=1')->where('p.id='.(int) $product_id);
                $this->db->setQuery($query);
                $product = $this->db->loadObject();
                if (empty($product) || !$product->published || $product->in_stock < $item->count) {
                    $add = false;
                    break;
                }

                if ($add) {
                    if (count($this->_discounts)) {
                        foreach ($this->_discounts as $discount) {
                            $this->onCheckDiscountManufacturers($discount->id, $product->manufacturer);
                            $this->onCheckDiscountManufacturers($discount->id, $product->category_id);
                            $vars = array($product, $discount->id);
                            JDispatcher::getInstance()->trigger('onSetProductDiscount', $vars);
                        }
                    }
                    $price = $product->price;
                    if (!empty($cart_id_currency)) {
                        if (empty($currencies)) {
                            $currencies = KSMPrice::getCurrencies();
                        }
                        $currency = $currencies[$cart_id_currency];
                        if ($product->price_type != $currency->id) {
                            $price = $price / $currencies[$product->price_type]->rate * $currency->rate;
                        }
                    }
                    $total   = $price * $item->count;
                    $items[] = array(
                        'feedId'   => $item->feedId,
                        'offerId'  => $item->offerId,
                        'price'    => (float) $total,
                        'count'    => (int) $item->count,
                        'delivery' => true
                    );
                }
            }

            $this->cartInfo($data->cart);
            $shippings = $this->getShippings();
            $shippings = $this->generateShippings($shippings);
            //$payments	= $this->getPayments();
            $payments = $this->generatePayments();

            $array = array(
                'cart' => array(
                    'items'           => $items,
                    'deliveryOptions' => $shippings,
                    'paymentMethods'  => $payments
                )
            );
            JLog::add(json_encode($array));
            $app->setHeader('Content-Type', 'application/json');
            $app->setHeader('charset', 'utf-8');
            $app->sendHeaders();
            $app->close(json_encode($array));
        }
        $app->close();
    }

    protected function generateShippings($shippings = array())
    {
        $ymshippings = array();
        foreach ($shippings as $shipping) {

            $ymshippings[] = array(
                'id'           => $shipping->id,
                'serviceName'  => $shipping->title,
                'paymentAllow' => $this->_paymentAllow,
                'type'         => 'DELIVERY',
                'price'        => $shipping->price,
                'dates'        => array(
                    'fromDate' => JFactory::getDate('now +'.$shipping->fromdate.' day')->Format('d-m-Y'),
                    'toDate'   => JFactory::getDate('now +'.$shipping->todate.' day')->Format('d-m-Y')
                )
            );
        }

        return $ymshippings;
    }

    protected function generatePayments()
    {
        $payments = array(
            'CARD_ON_DELIVERY',
            'CASH_ON_DELIVERY'
        );

        return $payments;
    }

    private function getShippings()
    {
        $query = $this->db->getQuery(true);
        $query
            ->select('
				s.id,
				s.title,
				s.type,
				s.regions,
				s.params,
				s.ordering
			')
            ->from('#__ksenmart_shippings AS s')
            //->where('s.published=1')
            ->order('s.ordering');

        $this->db->setQuery($query);
        $rows = $this->db->loadObjectList();

        $shippings = array();
        foreach ($rows as $row) {
            $row->regions = json_decode($row->regions, true);
            foreach ($row->regions as $country) {
                if (in_array($this->_region_id, $country)) {
                    $model             = null;
                    $cart              = new stdClass;
                    $cart->shipping_id = $row->id;
                    $cart->region_id   = $this->_region_id;
                    $cart->items       = array();
                    $vars              = array($model, $cart);
                    JDispatcher::getInstance()->trigger('onAfterExecuteKSMCartGetCart', $vars);
                    $row->price    = $cart->shipping_sum;
                    $row->params   = json_decode($row->params, true);
                    $row->fromdate = (isset($row->params[$this->_region_id]['fromdate'])) ? $row->params[$this->_region_id]['fromdate'] : 0;
                    $row->todate   = (isset($row->params[$this->_region_id]['todate'])) ? $row->params[$this->_region_id]['todate'] : 0;
                    if (empty($row->price) && $row->price !== 0) {
                        $row->price = 0;
                    }
                    $shippings[] = $row;
                }
            }
        }

        return $shippings;
    }

    private function getPayments()
    {
        $query = $this->db->getQuery(true);
        $query
            ->select('
				id,
				title,
				type,
				regions,
				params,
				ordering
			')
            ->from('#__ksenmart_payments')
            ->where('published=1')
            ->order('ordering');

        $this->db->setQuery($query);
        $rows = $this->db->loadObjectList();

        $payments = array();
        foreach ($rows as $row) {
            $row->regions = json_decode($row->regions, true);
            foreach ($row->regions as $country) {
                if (in_array($this->_region_id, $country)) {
                    $payments[] = $row;
                }
            }
        }

        return $payments;
    }

    function cartInfo($cart = null)
    {
        if (!isset($cart->delivery) && isset($cart->delivery->region)) {
            return false;
        }
        $this->setCartDelivery($cart->delivery->region);
        $query = $this->db->getQuery(true);
        $query->select('value')->from('#__ksenmart_yandeximport')->where('setting = '.$this->db->quote('postpaid_regions'));
        $this->db->setQuery($query);
        $postpaid_regions = $this->db->loadResult();
        if (isset($postpaid_regions) && !empty($postpaid_regions)) {
            foreach ($postpaid_regions as $country) {
                foreach ($country as $region) {
                    if ($region == $this->_region_id) {
                        $this->_paymentAllow = true;
                    }
                }
            }
        }

        return true;
    }

    function setCartDelivery($region = null)
    {
        if (!empty($region)) {
            switch ($region->type) {
                case 'CITY':
                    $this->_city = $region->name;
                    if ($this->_city == 'Москва') {
                        $query = $this->db->getQuery(true);
                        $query->select('id')->from('#__ksenmart_regions')->where('title='.$this->db->q($this->_city));
                        $this->db->setQuery($query);
                        $this->_region_id = $this->db->loadResult();
                    }
                    break;
                case 'SUBJECT_FEDERATION':
                    $name = $region->name;
                    if ($region->name == 'Москва и Московская область') {
                        $name = 'Московская область';
                    }
                    $query = $this->db->getQuery(true);
                    $query->select('id')->from('#__ksenmart_regions')->where('title='.$this->db->q($name));
                    $this->db->setQuery($query);
                    $region = $this->db->loadResult();
                    if (!empty($region)) {
                        $this->_region_id = $region;
                    }
                    break;
            }
            if (isset($region->parent)) {
                $this->setCartDelivery($region->parent);
            }
        }

        return true;
    }

    function onBeforeViewKSMCatalog($view)
    {
        $app    = JFactory::getApplication();
        $db     = JFactory::getDBO();
        $jinput = $app->input;
        $plugin = $jinput->get('plugin', null);
        if ($plugin == 'exportymextend') {
            $this->ApiYM();
        }
        $exportymextend = $jinput->get('export', null);

        if ($exportymextend != 'exportymextend') {
            return false;
        }

        $currencies = '';
        $categories = '';
        $offers     = '';
        $Itemid     = '&amp;Itemid='.KSSystem::getShopItemid();

        $query = $db->getQuery(true);
        $query->select('value')->from('#__ksenmart_yandeximport')->where('setting = '.$db->quote('shopname'));
        $db->setQuery($query);
        $shopname = $db->loadResult();

        $query = $db->getQuery(true);
        $query->select('value')->from('#__ksenmart_yandeximport')->where('setting = '.$db->quote('company'));
        $db->setQuery($query);
        $company = $db->loadResult();

        $query = $db->getQuery(true);
        $query->select('value')->from('#__ksenmart_yandeximport')->where('setting = '.$db->quote('utm_source'));
        $db->setQuery($query);
        $utm_source = $db->loadResult();

        $query = $db->getQuery(true);
        $query->select('value')->from('#__ksenmart_yandeximport')->where('setting = '.$db->quote('categories'));
        $db->setQuery($query);
        $cats = $db->loadResult();

        $cats = json_decode($cats, true);
        if (!is_array($cats)) {
            $cats = array();
        }
        if (!count($cats)) {
            $cats[] = 0;
        }

        $query = $db->getQuery(true);
        $query->select('value')->from('#__ksenmart_yandeximport')->where('setting = '.$db->quote('discounts'));
        $db->setQuery($query);
        $discounts = $db->loadResult();

        $discounts = json_decode($discounts, true);
        if (!is_array($discounts)) {
            $discounts = array();
        }

        $query = $db->getQuery(true);
        $query->select('rate')->from('#__ksenmart_currencies')->where('code = '.$db->quote('RUB'));
        $db->setQuery($query);
        $rur_rate = $db->loadResult();

        $query = $db->getQuery(true);
        $query->select('*')->from('#__ksenmart_currencies');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        foreach ($rows as $row) {
            $currencies .= '<currency id="'.$row->code.'" rate="'.round($rur_rate / $row->rate, 4).'"/>';
        }

        $query = $db->getQuery(true);
        $query->select('*')->from('#__ksenmart_categories')->where('id in ('.implode(',', $cats).')');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        foreach ($rows as $row) {
            $categories .= '<category id="'.$row->id.'" '.($row->parent_id != 0 ? 'parentId="'.$row->parent_id.'"' : '').'>'.htmlspecialchars($row->title,
                    ENT_QUOTES).'</category>';
        }

        if (count($discounts)) {
            $query = $db->getQuery(true);
            $query->select('*')->from('#__ksenmart_discounts')->where('id in ('.implode(',', $discounts).')');
            $db->setQuery($query);
            $this->_discounts = $db->loadObjectList('id');
        }

        $query = $db->getQuery(true);
        $query->select('p.*,pc.category_id')->from('#__ksenmart_products as p');
        $query->select('(select filename from #__ksenmart_files where owner_id=p.id and owner_type='.$db->quote('product').' and media_type='.$db->quote('image').' order by ordering limit 1) as picture');
        $query->select('(select title from #__ksenmart_manufacturers where id=p.manufacturer) as manufacturer_name');
        $query->select('(select code from #__ksenmart_currencies where id=p.price_type) as code');
        $query->innerjoin('#__ksenmart_products_categories as pc on pc.product_id=p.id');
        $query->where('pc.category_id in ('.implode(',',
                $cats).')')->where('p.published=1')->where('p.price>0')->where('p.parent_id=0');
        $query->group('id');
        $db->setQuery($query);
        $rows   = $db->loadObjectList();
        $domain = JURI::root();
        $domain = substr($domain, 0, -1);
        foreach ($rows as $row) {
            if (count($this->_discounts)) {
                foreach ($this->_discounts as $discount) {
                    $this->onCheckDiscountManufacturers($discount->id, $row->manufacturer);
                    $this->onCheckDiscountManufacturers($discount->id, $row->category_id);
                    $vars = array($row, $discount->id);
                    JDispatcher::getInstance()->trigger('onSetProductDiscount', $vars);
                }
            }
            if ($row->picture != '') {
                $row->picture = JURI::root().'media/com_ksenmart/images/products/original/'.$row->picture;
            } else {
                $row->picture = JURI::root().'media/com_ksenmart/images/products/original/no.jpg';
            }

            $row->link = $domain.JRoute::_('index.php?option=com_ksenmart&view=product&id='.$row->id.':'.$row->alias.'&Itemid='.KSSystem::getShopItemid(),
                    false, false);
            if (!empty($utm_source)) {
                $row->link .= '?utm_source='.$utm_source;
            }
            $offers .= '<offer id="'.$row->id.'" available="'.($row->in_stock > 0 ? 'true' : 'false').'" bid="1">
				<url>'.$row->link.'</url>
				<price>'.$row->price.'</price>
				<currencyId>'.$row->code.'</currencyId>
				<categoryId>'.$row->category_id.'</categoryId>
				<picture>'.$row->picture.'</picture>	
				<delivery>true</delivery>
				<name>'.htmlspecialchars($row->title, ENT_QUOTES).'</name>
				<vendor>'.htmlspecialchars($row->manufacturer_name, ENT_QUOTES).'</vendor>	
				<description>'.htmlspecialchars($row->content, ENT_QUOTES).'</description>
			</offer>';
        }

        header('Content-Type: text/xml;charset:utf-8');
        echo '<?xml version="1.0" encoding="utf-8"?>
		<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
		<yml_catalog date="'.date('Y-m-d H:i').'">
		<shop>
			<name>'.htmlspecialchars($shopname, ENT_QUOTES).'</name>
			<company>'.$company.'</company>
			<url>'.JURI::root().'</url>
			<platform>KsenMart based on Joomla</platform>
			<version>4.1</version>
			<agency>L.D.M. Co</agency>
			<email>boss.dm@gmail.com</email>	
			<currencies>
				'.$currencies.'
			</currencies>
			<categories>
				'.$categories.'
			</categories>
			<offers>
				'.$offers.'
			</offers>
		</shop>
		</yml_catalog>
		';

        $app->close();
    }

    function getConfigStep()
    {
        $this->view->form = $this->getForm();
        $data             = $this->getFormData();
        $this->view->form->bind($data);
        $html = KSSystem::loadPluginTemplate($this->_name, $this->_type, $this->view, 'config');

        return $html;
    }

    function saveConfig()
    {
        $app              = JFactory::getApplication();
        $db               = JFactory::getDBO();
        $jinput           = $app->input;
        $jform            = $jinput->get('jform', array(), 'array');
        $categories       = isset($jform['categories']) ? $jform['categories'] : array();
        $categories       = json_encode($categories);
        $discounts        = isset($jform['discounts']) ? $jform['discounts'] : array();
        $discounts        = json_encode($discounts);
        $postpaid_regions = isset($jform['postpaid_regions']) ? $jform['postpaid_regions'] : array();
        $postpaid_regions = json_encode($postpaid_regions);
        $shopname         = $jform['shopname'];
        $company          = $jform['company'];
        $utm_source       = trim($jform['utm_source']);

        $query = $db->getQuery(true);
        $query->update('#__ksenmart_yandeximport')->set('value='.$db->quote($categories))->where('setting='.$db->quote('categories'));
        $db->setQuery($query);
        $db->query();

        $query = $db->getQuery(true);
        $query->update('#__ksenmart_yandeximport')->set('value='.$db->quote($shopname))->where('setting='.$db->quote('shopname'));
        $db->setQuery($query);
        $db->query();

        $query = $db->getQuery(true);
        $query->update('#__ksenmart_yandeximport')->set('value='.$db->quote($company))->where('setting='.$db->quote('company'));
        $db->setQuery($query);
        $db->query();

        $query = $db->getQuery(true);
        $query->select('value')->from('#__ksenmart_yandeximport')->where('setting='.$db->quote('discounts'));
        $db->setQuery($query);
        $is_dis = $db->loadResult();

        if ($is_dis) {
            $query = $db->getQuery(true);
            $query->update('#__ksenmart_yandeximport')->set('value='.$db->quote($discounts))->where('setting='.$db->quote('discounts'));
            $db->setQuery($query);
            $db->query();
        } else {
            $query = $db->getQuery(true);
            $query->insert('#__ksenmart_yandeximport')->columns('value,setting')->values($db->quote($discounts).','.$db->quote('discounts'));
            $db->setQuery($query);
            $db->query();
        }

        $query = $db->getQuery(true);
        $query->select('value')->from('#__ksenmart_yandeximport')->where('setting='.$db->quote('utm_source'));
        $db->setQuery($query);
        $is_utm = $db->loadResult();

        if ($is_utm) {
            $query = $db->getQuery(true);
            $query->update('#__ksenmart_yandeximport')->set('value='.$db->quote($utm_source))->where('setting='.$db->quote('utm_source'));
            $db->setQuery($query);
            $db->query();
        } else {
            $query = $db->getQuery(true);
            $query->insert('#__ksenmart_yandeximport')->columns('value,setting')->values($db->quote($utm_source).','.$db->quote('utm_source'));
            $db->setQuery($query);
            $db->query();
        }

        $query = $db->getQuery(true);
        $query->select('value')->from('#__ksenmart_yandeximport')->where('setting='.$db->quote('postpaid_regions'));
        $db->setQuery($query);
        $is_reg = $db->loadResult();

        if ($is_reg) {
            $query = $db->getQuery(true);
            $query->update('#__ksenmart_yandeximport')->set('value='.$db->quote($postpaid_regions))->where('setting='.$db->quote('postpaid_regions'));
            $db->setQuery($query);
            $db->query();
        } else {
            $query = $db->getQuery(true);
            $query->insert('#__ksenmart_yandeximport')->columns('value,setting')->values($db->quote($postpaid_regions).','.$db->quote('postpaid_regions'));
            $db->setQuery($query);
            $db->query();
        }

        return true;
    }

    function getFormData()
    {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->select('*')->from('#__ksenmart_yandeximport');
        $db->setQuery($query);
        $settings = $db->loadObjectList('setting');

        $data             = new stdClass();
        $data->categories = json_decode($settings['categories']->value, true);
        if (isset($settings['discounts'])) {
            $data->discounts = json_decode($settings['discounts']->value, true);
        }
        if (empty($settings['postpaid_regions']->value)) {
            $settings['postpaid_regions']->value = '{}';
        }
        $data->postpaid_regions = json_decode($settings['postpaid_regions']->value, true);
        $data->shopname         = $settings['shopname']->value;
        $data->company          = $settings['company']->value;
        if (isset($settings['utm_source'])) {
            $data->utm_source = $settings['utm_source']->value;
        }

        return $data;
    }

    function getForm()
    {

        JKSForm::addFormPath(JPATH_ROOT.'/plugins/kmexportimport/export_ym_extend/assets/forms');
        JKSForm::addFieldPath(JPATH_ROOT.'/administrator/components/com_ksenmart/models/fields');
        JKSForm::addFieldPath(JPATH_ROOT.'/plugins/kmexportimport/export_ym_extend/assets/fields');

        $form = JKSForm::getInstance('com_ksenmart.exportym', 'exportymextend', array(
            'control'   => 'jform',
            'load_data' => true
        ));

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    function onCheckDiscountManufacturers($discount_id = null, $manufacturer_id = null)
    {
        if (empty($discount_id)) {
            return true;
        }
        if (empty($manufacturer_id)) {
            return true;
        }
        if (empty($this->_discounts[$discount_id]->manufacturers)) {
            return true;
        }
        $manufacturers = json_decode($this->_discounts[$discount_id]->manufacturers, true);
        if (!count($manufacturers)) {
            return true;
        }
        if (!in_array($manufacturer_id, $manufacturers)) {
            return false;
        }

        return true;
    }

    function onCheckDiscountCategories($discount_id = null, $category_id = null)
    {
        if (empty($discount_id)) {
            return true;
        }
        if (empty($category_id)) {
            return true;
        }
        if (empty($this->_discounts[$discount_id]->categories)) {
            return true;
        }
        $categories = json_decode($this->_discounts[$discount_id]->categories, true);
        if (!count($categories)) {
            return true;
        }
        if (!in_array($category_id, $categories)) {
            return false;
        }

        return true;
    }

}