<?php defined('_JEXEC') or die;

KSSystem::import('views.viewks');
class KsenMartViewManagement extends JViewKS {
    public function display($tpl = null) {
        
        $app             = JFactory::getApplication();
        $document        = JFactory::getDocument();
        $this->params    = JComponentHelper::getParams('com_ksenmart');
        $profile_model   = KSSystem::getModel('profile');
        $path            = $app->getPathway();
        $names_komponent = $this->params->get('shop_name');
        $pref            = $this->params->get('path_separator');
        $doc_title       = $names_komponent . $pref . JText::_('KSM_PROFILE_MANAGER_PATHWAY_ITEM');
        $this->state     = $this->get('State');

        if(!JFactory::getConfig()->get('config.caching', 0)){
            $path->addItem(JText::_('KSM_PROFILE_MANAGER_PATHWAY_ITEM'));
        }
        
        $document->setTitle($doc_title);
        $document->addScript(JURI::base() . 'components/com_ksenmart/js/profile_manager.js', 'text/javascript', false);
        
        $orders     = $profile_model->getOrders();
        $statuses   = $this->get('OrdersStatuses');
        
        $this->assignRef('orders', $orders);
        $this->assignRef('statuses', $statuses);
        
        parent::display();
    }
}