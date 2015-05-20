<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
class KsenMartControllerProfile extends JControllerLegacy {

    public function save_user() {
        $model = $this->getModel('profile');
        $this->setMessage($model->saveUser());
        $this->setRedirect(JRoute::_('index.php?option=com_ksenmart&view=profile', false));
    }

    public function load_order() {
        $session = JFactory::getSession();
        $model = $this->getModel('profile');
        $order = $model->getOrder();
        $session->set('shopcart_discount', $order->coupon);
        $session->set('shop_order_id', $order->id);
        $this->setRedirect(JRoute::_('index.php?option=com_ksenmart&view=cart&Itemid='.KSSystem::getShopItemid(), false));
    }

    public function add_address() {
        $model = $this->getModel('profile');
        $model->addAddress();
        $this->setRedirect(JRoute::_('index.php?option=com_ksenmart&view=profile', false));
    }
    
    public function edit_address() {
        $model   = $this->getModel('profile');
        $id      = JRequest::getInt('address_id', 1, 'post');
        
        $model->editAddress($id);
        $this->setRedirect(JRoute::_($_SERVER["REQUEST_URI"], false));
    }

    public function set_default_address() {
        $model = $this->getModel('profile');
        $model->setDefaultAddress();
        exit();
    }

    public function del_address() {
        $model = $this->getModel('profile');
        $model->deleteAddress();
        $this->setRedirect(JRoute::_('index.php?option=com_ksenmart&view=profile', false));
    }
    
    public function add_shop_review() {
        $model = $this->getModel('Comments');
        $model->addShopReview();
        $this->setRedirect(JRoute::_($_SERVER["REQUEST_URI"].'', false));
    }
    
    public function edit_shop_review() {
        $model   = $this->getModel('Comments');
        $jinput  = JFactory::getApplication()->input;
        $id      = $jinput->get('id', 1, 'int');
        $rate    = $jinput->get('rate', 1, 'int');
        $comment = $_POST['review'];

        $model->editShopReview($id, $comment, $rate);
        return true;
    }

    public function del_logo() {
        $user = JFactory::getUser();
        $db = JFactory::getDBO();
        $query = "select logo from #__ksen_users where id='$user->id'";
        $db->setQuery($query);
        $logo = $db->loadResult();
        unlink(JPATH_ROOT . '/' . $logo);
        $query = "update #__ksen_users set logo='' where id='$user->id'";
        $db->setQuery($query);
        $db->query();
        $this->setRedirect(JRoute::_('index.php?option=com_ksenmart&view=shopaccount'));
    }

    public function create_money_request() {

        $money_request_cost = JRequest::getVar('money_request_cost', 0);
        if ($money_request_cost == 0) {
            $this->setRedirect(JRoute::_('index.php?option=com_ksenmart&view=shopaccount&layout=money'));
            return false;
        }
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $query = "insert into #__ksenmart_money_requests (`user`,`cost`,`date_add`) values ('$user->id','$money_request_cost',NOW())";
        $db->setQuery($query);
        $db->query();
        $money_request_id = $db->insertid();
        $session = JFactory::getSession();
        $session->set('money_request_id', $money_request_id);
        $this->setRedirect(JRoute::_('index.php?option=com_ksenmart&view=shopaccount&layout=account_money_request'));
    }

    public function create_slider_request() {
        $product = JRequest::getVar('product', 0);
        $date_from = JRequest::getVar('date_from', '');
        $date_from = date('Y-m-d H:i:s', strtotime($date_from));
        $date_to = JRequest::getVar('date_to', '');
        $date_to = date('Y-m-d H:i:s', strtotime($date_to));
        $views = JRequest::getVar('views', '');
        $product = JRequest::getVar('product', 0);
        $type = JRequest::getVar('type', 0);
        $cost = JRequest::getVar('cost', 0);
        if ($cost == 0) {
            $this->setRedirect(JRoute::_('index.php?option=com_ksenmart&view=shopaccount&layout=slider_requests'));
            return false;
        }
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $query = "insert into #__ksenmart_slider_requests (`product`,`seller`,`type`,`date_from`,`date_to`,`views`,`status`,`cost`,`date_add`) values ('$product','$user->id','$type','$date_from','$date_to','$views','1','$cost',NOW())";
        $db->setQuery($query);
        $db->query();
        $slider_request_id = $db->insertid();
        //KsenMartHelper::sendSliderRequestInfoAdmin($slider_request_id);
        $this->setRedirect(JRoute::_('index.php?option=com_ksenmart&view=shopaccount&layout=slider_requests'));
    }

    public function create_product_request() {
        $title = JRequest::getVar('title', '');
        $price = JRequest::getVar('price', 0);
        $price_old = JRequest::getVar('price_old', 0);
        $in_stock = JRequest::getVar('in_stock', 0);
        $pitch = JRequest::getVar('pitch', 0);
        $pitch_name = JRequest::getVar('pitch_name', '');
        $content = JRequest::getVar('content', '');
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        if ($_FILES['photo']['tmp_name'] != '') {
            $allow_ext = array(
                'jpg',
                'jpeg',
                'gif',
                'bmp',
                'png',
                'zip');
            $filename = $_FILES['photo']['name'];
            $filename_parts = explode('.', $filename);
            $ext = $filename_parts[count($filename_parts) - 1];
            if (!in_array($ext, $allow_ext)) {
                $this->setMessage('Неверный формат файла фотографий');
                $this->setRedirect(JRoute::_('index.php?option=com_ksenmart&view=shopaccount&layout=new_product_request'));
                return false;
            }
        }
        $query = "insert into #__ksenmart_product_requests (`seller`,`title`,`price`,`price_old`,`in_stock`,`pitch`,`pitch_name`,`content`,`status`,`date_add`) values ('$user->id','$title','$price','$price_old','$in_stock','$pitch','$pitch_name','$content','1',NOW())";
        $db->setQuery($query);
        $db->query();
        $product_request_id = $db->insertid();
        if ($_FILES['photo']['tmp_name'] != '') {
            switch ($ext) {
                case 'zip':
                    require_once (JPATH_COMPONENT_ADMINISTRATOR . '/helpers/pclzip.lib.php');
                    $archive = new PclZip($_FILES['photo']['tmp_name']);
                    $dir = JPATH_COMPONENT_ADMINISTRATOR . '/tmp/' . microtime(true);
                    mkdir($dir);
                    $archive->extract($dir, PCLZIP_OPT_REMOVE_ALL_PATH);
                    $files = scandir($dir);
                    foreach ($files as $file) {
                        if ($file != '.' && $file != '..') {
                            $filename_parts = explode('.', $file);
                            $ext = $filename_parts[count($filename_parts) - 1];
                            if (in_array($ext, $allow_ext)) {
                                $filename = microtime(true) . '.' . $ext;
                                copy($dir . '/' . $file, JPATH_COMPONENT_ADMINISTRATOR . '/images/product_requests/' . $filename);
                                $query = "insert into #__ksenmart_images (`owner_type`,`owner_id`,`file`) values ('product_request','$product_request_id','$filename')";
                                $db->setQuery($query);
                                $db->query();
                            }
                            unlink($dir . '/' . $file);
                        }
                    }
                    rmdir($dir);
                    break;
                default:
                    $filename = microtime(true) . '.' . $ext;
                    copy($_FILES['photo']['tmp_name'], JPATH_COMPONENT_ADMINISTRATOR . '/images/product_requests/' . $filename);
                    $query = "insert into #__ksenmart_images (`owner_type`,`owner_id`,`file`) values ('product_request','$product_request_id','$filename')";
                    $db->setQuery($query);
                    $db->query();
            }
        }
        //KsenMartHelper::sendProductRequestInfoAdmin($product_request_id);
        $this->setRedirect(JRoute::_('index.php?option=com_ksenmart&view=shopaccount&layout=product_requests'));
    }

    public function loadAvatar(){
        $model = $this->getModel('profile');
        if($model->loadAvatar()){
            return true;
        }
        return false;
    }
    
    public function updateProductReview(){
        $review_id      = JRequest::getInt('review_id', 0, 'POST');
        $comment        = JRequest::getVar('comment', 0);
        $good           = JRequest::getVar('good', 0);
        $bad            = JRequest::getVar('bad', 0);
        $model          = $this->getModel('profile');

        if($model->updateProductReview($review_id, $comment, $good, $bad)){
            return true;
        }
        return false;
    }
    
    public function lose_t_product() {
        $model   = $this->getModel('profile');
        $jinput  = JFactory::getApplication()->input;
        
        $id      = $jinput->get('id', 0, 'int');
        $type    = $jinput->get('type', null, 'string');
        
        if(!empty($type)){
            if($type == 'favorities'){
                $model->removeFavorite($id);
            }elseif($type == 'watched'){
                $model->removeWatched($id);
            }
            return true;
        }
    }
    
    public function getDataShippingModule(){
        $jinput     = JFactory::getApplication()->input;
        $session    = JFactory::getSession();
        $region_id  = $jinput->get('region_id', 0, 'int');
        $model      = $this->getModel('profile');
        $view       = $this->getView('profile', 'html');
        
        $shippings = $model->getShippingsByRegionId($region_id);
        $payments  = $model->getPaymentsByRegionId($region_id);
        
        $view->assignRef('shippings', $shippings);
        $view->assignRef('payments', $payments);
        
        $session->set('user_region', $region_id);
        $view->setLayout('module_shipping');
        $view->display();        
    }
    
    public function setSubscribe(){
        KSUsers::setUserSubscribeGroup(KSUsers::getUser()->id);
    }
    
    public function setUserInfoState(){
        $app  = JFactory::getApplication();
        $data = array('Пупкин');
        
        $app->setUserState('com_ksenmart.order.user', $data);
        $user = $app->getUserState('com_ksenmart.order.data');
        print_r($user);
    }
}