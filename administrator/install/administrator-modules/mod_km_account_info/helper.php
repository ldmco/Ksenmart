<?php defined('_JEXEC') or die('Restricted access');

class modAccountInfo {
    
    private $_session = null;
    
    public function __construct(){
        $this->_session     = JFactory::getSession();
        $this->_session->km = new stdClass();
    }
    
    public function checkAuthorize() {
        if(isset($this->_session->km->auth) && !empty($this->_session->km->auth)){
            return true;
        }
        return false;
    }
}