<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
class KsenMartControllerProduct extends JControllerLegacy {
    
    public function __construct($config = array()) {
        parent::__construct($config);
        $this->registerTask('add_comment', 'add_comment');
    }
    
    public function add_comment() {
        $app            = JFactory::getApplication();
        $model          = $this->getModel('Product', 'KsenmartModel');
        $comments_model = $this->getModel('Comments', 'KsenmartModel');
        
        $return_url  = JRoute::_('index.php?option=com_ksenmart&view=product&id=' . $model->_id . '&Itemid=' . KSSystem::getShopItemid());
        $requestData = $this->input->post->get('jform', array() , 'array');
        $data        = array();
        
        if (count($requestData)) {
            $model->form = 'review';
            $form = $model->getForm();
            
            if (!$form) {
                JError::raiseError(500, $model->getError());
                
                return false;
            }
            
            $data = $model->validate($form, $requestData);
            
            if ($data === false) {
                $errors = $model->getErrors();
                
                for ($i = 0, $n = count($errors);$i < $n && $i < 3;$i++) {
                    if ($errors[$i] instanceof Exception) {
                        $app->enqueueMessage($errors[$i]->getMessage() , 'warning');
                    } else {
                        $app->enqueueMessage($errors[$i], 'warning');
                    }
                }
                
                $this->setRedirect($return_url);
                return false;
            }
        } else {
            $data = array();
            $data['comment_name'] = $this->input->post->get('comment_name', $user->name, 'string');
            $data['comment_rate'] = $this->input->post->get('comment_rate', 0, 'int');
            $data['comment_comment'] = $this->input->post->get('comment_comment', null, 'string');
            $data['comment_good'] = $this->input->post->get('comment_good', null, 'string');
            $data['comment_name'] = $this->input->post->get('comment_bad', null, 'string');
        }
        $data['product_id'] = $model->_id;
        
        $comments_model->addComment($data);
        if (!isset($_SESSION['rated']) || !is_array($_SESSION['rated'])) {
            $_SESSION['rated'] = array();
        }
        $_SESSION['rated'][$model->_id] = 1;
        
        $this->setMessage('Ваш отзыв принят');
        $this->setRedirect($return_url);
        
        return true;
    }
    
    public function addCommentReply() {
        $jinput = JFactory::getApplication()->input;
        $model  = $this->getModel('Comments');
        
        $id         = $jinput->get('id', 0, 'int');
        $product_id = $jinput->get('product_id', 0, 'int');
        $comment    = $jinput->get('comment', null, 'string');
        
        if ($model->addCommentReply($id, $comment, $product_id)) {
            return true;
        }
        return false;
    }
}
