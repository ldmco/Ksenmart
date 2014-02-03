<?php defined('_JEXEC') or die;

class KsenmartHtmlHelper {

    private static $_headAdded = false;

    public static function AddHeadTags() {
        if(self::$_headAdded == true) return;
        $session = JFactory::getSession();
        $document = JFactory::getDocument();
        if(!class_exists('KMSystem')){
            require (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_ksenmart' . DS . 'helpers' . DS . 'common' . DS . 'system.php');
        }
        $params = JComponentHelper::getParams('com_ksenmart');
        if($params->get('include_jquery', 1)) {
            $document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
        }
        $document->addScript(JURI::base() . 'components' . DS . 'com_ksenmart' . DS . 'js' . DS . 'bootstrap.min.js');
        $document->addScript(JURI::base() . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'js' . DS . 'jquery.custom.min.js');
        $document->addScript(JURI::base() . 'components' . DS . 'com_ksenmart' . DS . 'js' . DS . 'common.js');
        
        $document->addStyleSheet(JURI::base() . 'components' . DS . 'com_ksenmart' . DS . 'css' . DS . 'common.css');
        
        if($params->get('include_css', 1)) {
            $document->addStyleSheet(JURI::base() . 'components' . DS . 'com_ksenmart' . DS . 'css' . DS . 'bootstrap.min.css');
            $document->addStyleSheet(JURI::base() . 'components' . DS . 'com_ksenmart' . DS . 'css' . DS . 'bootstrap-responsive.min.css');
            $document->addStyleSheet(JURI::base() . 'components' . DS . 'com_ksenmart' . DS . 'css' . DS . 'template.css');
        }
        
        $js = "
		var URI_ROOT='" . JURI::root() . "';
		var km_cart_link='" . JRoute::_('index.php?option=com_ksenmart&view=shopopencart&Itemid=' . KMSystem::getShopItemid()) . "';
		var shopItemid='" . KMSystem::getShopItemid() . "';
		var order_type='ordering';
		var order_dir='asc';	
		var limit=" . $params->get('site_product_limit', 30) . ";
		var limitstart=0;	
		var use_pagination=" . $params->get('site_use_pagination', 0) . ";
		var order_process=" . $params->get('order_process', 0) . ";
		var cat_id=" . JRequest::getInt('id', 0) . ";
		var user_id=" . JFactory::getUser()->id . ";
		var page=1;
		var session_id='" . $session->getId() . "';
		";
        $document->addScriptDeclaration($js);
        self::$_headAdded = true;
        KMSystem::loadPlugins();
    }
}