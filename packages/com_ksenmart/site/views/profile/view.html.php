<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
 
KSSystem::import('views.viewks');
class KsenMartViewProfile extends JViewKS {
    public function display($tpl = null) {
        $user               = JFactory::getUser();
        $layout             = $this->getLayout();
        if($user->id == 0 && $layout != 'registration' && $layout != 'module_shipping'){
            header('Location:/');
        }
        $app                = JFactory::getApplication();
        $document           = JFactory::getDocument();
        $this->params       = JComponentHelper::getParams('com_ksenmart');
        $path               = $app->getPathway();
        $names_komponent    = $this->params->get('shop_name', '');
        $pref               = $this->params->get('path_separator', '');
        $doc_title          = $names_komponent . $pref . JText::_('KSM_PROFILE_PATHWAY_ITEM');
        
        if(!JFactory::getConfig()->get('config.caching', 0) && $layout != 'registration'){
            $path->addItem(JText::_('KSM_PROFILE_PATHWAY_ITEM'), '');
        }
        $document->setTitle($doc_title);
        $this->assign('params', $this->params);

        switch ($layout) {
            case 'registration':
                $doc_title = $names_komponent . $pref . JText::_('KSM_PROFILE__REGISTRATION_PATHWAY_ITEM');
                $document->setTitle($doc_title);
                if(!JFactory::getConfig()->get('config.caching', 0)){
                    $path->addItem(JText::_('KSM_PROFILE__REGISTRATION_PATHWAY_ITEM'), '');
                }
				$this->fields = KSUsers::getFields();
                $this->setLayout('profile_registration');
            break;
            case 'profile_order':
                $id     = JRequest::getVar('id', 0);
                if(!JFactory::getConfig()->get('config.caching', 0)) {
                    $path->addItem(JText::_('profile_orders'), 'index.php?option=com_ksenmart&view=profile&layout=profile_orders');
                    $path->addItem('Заказ №' . $id);
                }
                
                $document->addScript(JURI::base() . 'components/com_ksenmart/js/profile_order.js', 'text/javascript', false);
                
                $order      = $this->get('Order');
                $fields     = KSUsers::getFields();
                $regions    = $this->get('Regions');
                
                $this->assign('order', $order);
                $this->assign('fields', $fields);
                $this->assign('regions', $regions);
                $this->setLayout('profile_order');
            break;
            case 'module_shipping':
                
            break;
            default:
                $document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.inputmask.js', 'text/javascript', false);
                $document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.bind-first-0.1.min.js', 'text/javascript', false);
                $document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.inputmask-multi.js', 'text/javascript', false);
                $document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.Jcrop.min.js', 'text/javascript', true);
                
                $document->addScript(JURI::base() . 'components/com_ksenmart/js/profile.js', 'text/javascript', false);
                
                $document->addStyleSheet(JURI::base() . 'components/com_ksenmart/css/map.css');
                $document->addStyleSheet(JURI::base() . 'components/com_ksenmart/css/jquery.Jcrop.min.css');

                $model      = $this->getModel('profile');
                
                $user       = KSUsers::getUser();
                $fields     = KSUsers::getFields();
                $regions    = $this->get('Regions');
                $favorities = $this->get('Favorities');
                $watched    = $this->get('Watched');
                $orders     = $this->get('Orders');
                $addresses  = $this->get('Addresses');
                $reviews    = $this->get('Comments');
                $pagination = $this->get('Pagination');

                $user_review = $model->getShopReview($user->id);

                $this->assign('user', $user);
                $this->assign('fields', $fields);
                $this->assign('regions', $regions);
                $this->assign('favorities', $favorities);
                $this->assign('watched', $watched);
                $this->assign('orders', $orders);
                $this->assign('addresses', $addresses);
                $this->assign('reviews', $reviews);
                $this->assign('user_review', $user_review);
                $this->assign('pagination', $pagination);
                $this->assign('model_profile', $model);
                $this->assign('show_shop_review', KSSystem::issetReview($user->id));
                $this->setLayout('profile');
            break;
        }
        parent::display($tpl);
    }
}