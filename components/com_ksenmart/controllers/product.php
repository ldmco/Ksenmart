<?php defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
class KsenMartControllerProduct extends JControllerLegacy {
    
    public function __construct($config = array()) {
        parent::__construct($config);
        $this->registerTask('add_comment', 'add_comment');
    }

    public function add_comment() {
        $comments_model = $this->getModel('Comments');
        $comments_model->addComment();
        $id = JRequest::getVar('id', 0);
        if (!isset($_SESSION['rated']) || !is_array($_SESSION['rated'])){
            $_SESSION['rated'] = array();
        }
        $_SESSION['rated'][$id] = 1;
        $app = &JFactory::getApplication();
        $app->enqueueMessage('Ваш отзыв принят');
        parent::display();
    }
    
    public function addCommentReply(){
        $jinput  = JFactory::getApplication()->input;
        $model   = $this->getModel('Comments');
        
        $id             = $jinput->get('id', 0, 'int');
        $product_id     = $jinput->get('product_id', 0, 'int');
        $comment        = $jinput->get('comment', null, 'string');
        
        if($model->addCommentReply($id, $comment, $product_id)){
            return true;
        }
        return false;
    }
}