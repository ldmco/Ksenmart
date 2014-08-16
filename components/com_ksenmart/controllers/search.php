<?php defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
class KsenMartControllerSearch extends JControllerLegacy {
    
    public $value = null;

    public function productSearch(){
        $model = $this->getModel('search');
        $view = $this->getView('search', 'html');
        $view->assignRef('model', $model);
        $view->display();
        
        return false;
    }
}