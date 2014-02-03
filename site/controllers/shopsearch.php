<?php defined('_JEXEC') or die('=;)');
jimport('joomla.application.component.controller');

class KsenMartControllerShopSearch extends JController {
    
    public $value = null;
    
    public function __construct(){
        parent::__construct();
    }
    
    public function display(){
        parent::display();
    }
    
    public function productSearch(){
        $model = $this->getModel('shopsearch');
        $view = $this->getView('shopsearch', 'html');
        $view->assignRef('model', $model);
        $view->display();
        
        return false;
    }
}