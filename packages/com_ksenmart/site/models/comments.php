<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelkslist');
class KsenMartModelComments extends JModelKSList {
    
    var $_total = null;
    var $_limit = null;
    var $_pagination = null;

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    public function getComments() {
        $this->onExecuteBefore('getComments');

        jimport('joomla.html.pagination');
        $params         = JComponentHelper::getParams('com_ksenmart');
        $limitstart     = JRequest::getVar('limitstart', 0);
        $this->_limit   = $params->get('site_product_limit', 10);
        
        $query = $this->_db->getQuery(true);
        $query
            ->select('
                c.id,
                c.parent_id,
                c.user_id AS user,
                c.product_id AS product,
                c.name,
                c.comment,
                c.good,
                c.bad,
                c.rate,
                c.date_add,
                c.type
            ')
            ->from('#__ksenmart_comments AS c')
            ->where('c.type="review"')
            ->where('c.published=1')
            ->order('c.date_add')
        ;
        
        $this->_db->setQuery($query, $limitstart, $this->_limit);
        $comments = $this->_db->loadObjectList();
        
        for ($k = 0; $k < count($comments); $k++) {
            $comments[$k]->user = KSUsers::getUser($comments[$k]->user);
			if(isset($comments[$k]->product) && $comments[$k]->product > 0){
				$comments[$k]->product = KSMProducts::getProduct($comments[$k]->product);
			}
        }
        $this->_pagination = new JPagination($this->_total, $limitstart, $this->_limit);
        
        $this->onExecuteAfter('getComments', array(&$comments));
        return $comments;
    }

    public function getComment() {
        $this->onExecuteBefore('getComment');

        $id = JRequest::getInt('id', 0);
        $comment = KSSystem::getTableByIds(array($id), 'comments', array(
                't.id',
                't.parent_id',
                't.user_id AS user',
                't.product_id AS product',
                't.name',
                't.comment',
                't.good',
                't.bad',
                't.rate',
                't.date_add',
                't.type'
        ), true, false, true);
        if(!empty($comment)){
            $comment->user = KSUsers::getUser($comment->user);
			if(isset($comment->product) && $comment->product > 0){
				$comment->product = KSMProducts::getProduct($comment->product);
			}
            
            $this->onExecuteAfter('getComment', array(&$comment));
            return $comment;
        }
        return new stdClass;
    }

    public function getPagination() {
        $this->onExecuteBefore('getPagination', array(&$this));

        if (empty($this->_pagination) || $this->_total <= $this->_limit) {
            return null;
        }
        
        $this->onExecuteAfter('getPagination', array(&$this->_pagination));
        return $this->_pagination;
    }

    public function getRates() {
        $this->onExecuteBefore('getRates');

        $query = $this->_db->getQuery(true);
        $query
            ->select('
                cr.id,
                cr.title
            ')
            ->from('#__ksenmart_comment_rates AS cr')
            ->order('cr.ordering')
        ;
        $this->_db->setQuery($query);
        $rates = $this->_db->loadObjectList();
        
        $this->onExecuteAfter('getRates', array(&$rates));
        return $rates;
    }

    public function addComment($data) {
        $this->onExecuteBefore('addComment');

        $user       = KSUsers::getUser();
        $jinput     = JFactory::getApplication()->input;
        $params     = JComponentHelper::getParams('com_ksenmart');
        
        $comment_object = new stdClass();
        $comment_object->user_id    = $user->id;
        $comment_object->product_id = $data['product_id'];
        $comment_object->name       = $data['comment_name'];
        $comment_object->comment    = $data['comment_comment'];
        $comment_object->good       = $data['comment_good'];
        $comment_object->bad        = $data['comment_bad'];
        $comment_object->rate       = $data['comment_rate'];

        if($params->get('review_moderation', false)){
            $comment_object->published       = 0;
        }

        try{
            $result = $this->_db->insertObject('#__ksenmart_comments', $comment_object);
            
			if($params->get('review_notice', false)){
				$mail = JFactory::getMailer();
				$sender = array($params->get('shop_email'), $params->get('shop_name'));
				$content = KSSystem::loadTemplate(array('comment' => $comment_object), 'comments', 'default', 'mail');
				
				$mail->isHTML(true);
				$mail->setSender($sender);
				$mail->Subject = 'Новый отзыв';
				$mail->Body = $content;
				$mail->AddAddress($params->get('shop_email'), $params->get('shop_name'));
				$mail->Send();
			}
			
            $this->onExecuteAfter('addComment', array(&$result));
            return true;
        }catch(Exception $e){}
    }

    public function addShopReview() {
        $this->onExecuteBefore('addShopReview');

        $name   = JRequest::getVar('name', $user->name);
        $review = JRequest::getVar('review', null);
        $rate   = JRequest::getInt('rate', 0, 'post');
        $user   = KSUsers::getUser();

        $new_review = new stdClass();
        $new_review->user_id    = $user->id;
        $new_review->name       = $name;
        $new_review->comment    = $review;
        $new_review->type       = 'shop_review';
        $new_review->rate       = $rate;

        try{
            $result = $this->_db->insertObject('#__ksenmart_comments', $new_review);
            
            $this->onExecuteAfter('addShopReview', array(&$result));
            return true;
        }catch(Exception $e){}
    }
    
    public function editShopReview($id, $comment, $rate) {
        $this->onExecuteBefore('editShopReview', array(&$id, &$comment, &$rate));

        if(!empty($id) && $id > 0){
            $object = new stdClass();

            $object->id      = $id;
            $object->comment = $this->_db->escape($comment);
            
            try {
                $result = $this->_db->updateObject('#__ksenmart_comments', $object, 'id');
                
                $this->onExecuteAfter('editShopReview', array(&$result));
                return true;
            }catch(Exception $e) {
                
            }
        }
        return false;
    }
    
    public function getShopReview($uid){
        $this->onExecuteBefore('getShopReview', array(&$uid));

        $this->_db = JFactory::getDbo();

        $query = $this->_db->getQuery(true);
        $query->select('
            c.id,
            c.user_id AS user, 
            c.comment, 
            c.date_add, 
            c.rate,
            uf.filename AS logo,
            u.name
        ');
        $query->from('#__ksenmart_comments AS c');
        $query->leftjoin('#__ksen_users AS kmu ON kmu.id=c.user_id');
        $query->leftjoin('#__users AS u ON kmu.id=u.id');
        $query->leftjoin('#__ksenmart_files AS uf ON uf.owner_id=u.id');
        $query->where("c.type='shop_review'");
        $query->where("c.published=1");
        $query->where('c.user_id=' . $this->_db->q($uid));
        $query->order('c.date_add DESC');

        $this->_db->setQuery($query);
        
        $reviews = KSUsers::setAvatarLogoInObject($this->_db->loadObject());
        
        $this->onExecuteAfter('getShopReview', array(&$reviews));
        return $reviews;
    }
    
    public function getShopReviewById($id){
        $this->onExecuteBefore('getShopReviewById', array(&$id));

        $this->_db = JFactory::getDbo();

        $query = $this->_db->getQuery(true);
        $query->select('
            c.id,
            c.user_id AS user, 
            c.name, 
            c.comment, 
            c.date_add, 
            c.rate,
            uf.filename AS logo
        ');
        $query->from('#__ksenmart_comments AS c');
        $query->leftjoin('#__ksen_users AS u ON u.id=c.user_id');
        $query->leftjoin('#__ksenmart_files AS uf ON uf.owner_id=u.id');
        $query->where("c.type='shop_review'");
        $query->where("c.published=1");
        $query->where('c.id=' . $this->_db->q($id));
        $query->order('c.date_add DESC');
        $this->_db->setQuery($query);
        $review = $this->_db->loadObject();
        
        KSUsers::setAvatarLogoInObject($review);
        
        $this->onExecuteAfter('getShopReviewById', array(&$review));
        return $review;
    }
    
    public function getShopReviewsList(){
        $this->onExecuteBefore('getShopReviewsList');

        $this->_db = JFactory::getDbo();

        $query = $this->_db->getQuery(true);
        $query->select('
            c.id,
            c.user_id AS user, 
            c.name, 
            c.comment, 
            c.date_add, 
            c.rate,
            uf.filename AS logo
        ');
        $query->from('#__ksenmart_comments AS c');
        $query->leftjoin('#__ksen_users AS u ON u.id=c.user_id');
        $query->leftjoin('#__ksenmart_files AS uf ON uf.owner_id=u.id');
        $query->where("type='shop_review'");
        $query->where("published=1");
        $query->order('date_add DESC');
        $this->_db->setQuery($query);
        $reviews = $this->_db->loadObjectList();
        KSUsers::setAvatarLogoInObject($reviews);
        
        $this->onExecuteAfter('getShopReviewsList', array(&$reviews));
        return $reviews;
    }
    
    public function addCommentReply($id, $comment, $product_id) {
        $this->onExecuteBefore('addCommentReply', array(&$id, &$comment, &$product_id));
        
        if(!empty($id) && $id > 0){
            $user           = KSUsers::getUser();
            $reply          = new stdClass();
            
            $reply->parent_id   = $id;
            $reply->user_id     = $user->id;
            $reply->name        = $user->name;
            $reply->product_id  = $product_id;
            $reply->comment     = $comment;
    
            try{
                $result = $this->_db->insertObject('#__ksenmart_comments', $reply);
                
                $this->onExecuteAfter('addCommentReply', array(&$result));
                return true;
            }catch(Exception $e){}
        }
        return false;
    }
}