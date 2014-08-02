<?php defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
class KsenMartControllerManagement extends JControllerLegacy {
    public function updateOrderStatus(){
        $jinput     = JFactory::getApplication()->input;
        $model      = $this->getModel('management');
        $id         = $jinput->get('status_id', 0, 'int');
        $order_id   = $jinput->get('order_id', 0, 'int');
        
        if(!empty($id) && $id > 0){
            if($model->updateOrderStatus($id, $order_id)){
                return true;
            }
        }
        return false;
    }
}