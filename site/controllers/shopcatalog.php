<?php defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class KsenMartControllerShopCatalog extends JController {
    public function __construct($config = array()) {
        $config['base_path'] = JPATH_ROOT . DS . 'components' . DS . 'com_ksenmart';
        parent::__construct($config);
        $this->registerTask('get_product_links', 'get_product_links');
    }

    function filter_products() {
        $model = $this->getModel('shopcatalog');
        $view  = $this->getView('shopcatalog', 'html');
        $view->setModel($model, true);
        ob_start();
        $view->display();
        $html = ob_get_contents();
        ob_end_clean();
        
        $properties     = $model->getFilterProperties();
        $manufacturers  = $model->getFilterManufacturers();
        $countries      = $model->getFilterCountries();
        
        $response = array(
            'html'          => $html,
            'properties'    => $properties,
            'manufacturers' => $manufacturers,
            'countries'     => $countries
        );
        echo json_encode($response);
        JFactory::getApplication()->close();
    }

    function display() {
        parent::display();
    }
    
    public function setLayoutView(){
        $layout  = JRequest::getVar('layout', 'grid');
        $session = JFactory::getSession();
        $model   = $this->getModel('shopcatalog');
        
        $session->set('layout', $layout);
        $model->setLayoutCatalog($layout);
        
        JFactory::getApplication()->close();
    }
}
