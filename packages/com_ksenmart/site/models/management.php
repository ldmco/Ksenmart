<?php defined('_JEXEC') or die;

KSSystem::import('models.modelkslist');
class KsenMartModelManagement extends JModelKSList {
    
    var $_db        = null;
    var $_params    = null;
    
    public function __construct() {
        $this->_params = JComponentHelper::getParams('com_ksenmart');
        parent::__construct();
    }
    
    public function getOrdersStatuses() {

        $query = $this->_db->getQuery(true);
        $query->select('os.id, os.title');
        $query->from('#__ksenmart_order_statuses AS os');

        $this->_db->setQuery($query);
        $orders_statuses = $this->_db->loadObjectList();

        return $orders_statuses;
    }
    
    public function updateOrderStatus($id, $order_id){
        if(!empty($order_id) && $order_id > 0){
            
            $order            = new stdClass();
            $order->id        = $this->_db->escape($order_id);
            $order->status_id = $this->_db->escape($id);

            try {
                $result = $this->_db->updateObject('#__ksenmart_orders', $order, 'id');
                return true;
            }catch(Exception $e) {
                
            }
        }
        return false;
    }
}