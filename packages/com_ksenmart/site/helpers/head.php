<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class KsenmartHtmlHelper {

    private static $_headAdded = false;

    public static function AddHeadTags() {
        if(self::$_headAdded == true) return;
        $session = JFactory::getSession();
        $document = JFactory::getDocument();

        JDispatcher::getInstance()->trigger('onLoadKsen', array('ksenmart.KSM', array('common'), array(), array('angularJS' => 0)));
        KSLoader::loadLocalHelpers(array('common'));

        $params = JComponentHelper::getParams('com_ksenmart');

        $document->addScript(JURI::base() . 'administrator/components/com_ksenmart/js/jquery.custom.min.js');
        $document->addScript(JURI::base() . 'components/com_ksenmart/js/common.js');
        
        $document->addStyleSheet(JURI::base() . 'components/com_ksenmart/css/common.css');

        if($params->get('include_css', 1)) {
            $document->addStyleSheet(JURI::base() . 'components/com_ksenmart/css/template.css');
        }
        
        $js = "
        var URI_ROOT='" . JURI::root() . "';
        var km_cart_link='" . JRoute::_('index.php?option=com_ksenmart&view=cart&Itemid=' . KSSystem::getShopItemid()) . "';
        var shopItemid='" . KSSystem::getShopItemid() . "';
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
        KSSystem::loadPlugins();
    }
}
