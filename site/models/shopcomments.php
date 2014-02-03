<?php defined('_JEXEC') or die;

jimport('joomla.application.component.modelkmadmin');

class KsenMartModelShopComments extends JModelKMAdmin {
    
    var $_total = null;
    var $_limit = null;
    var $_pagination = null;

    public function __construct() {
        parent::__construct();
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
            ->where('c.published=1')
            ->order('c.date_add')
        ;
        
        $this->_db->setQuery($query, $limitstart, $this->_limit);
        $comments = $this->_db->loadObjectList();
        
        for ($k = 0; $k < count($comments); $k++) {
            $comments[$k]->user = KMUsers::getUser($comments[$k]->user);
            /*$query = "select p.*,(select i.file from #__ksenmart_images as i where i.owner_id=p.id and i.owner_type=p.type order by i.ordering limit 1) as image from #__ksenmart_products as p where p.id='{$comments[$k]->product}'";
            $this->_db->setQuery($query);
            $comments[$k]->product = $this->_db->loadObject();*/
        }
        $this->_pagination = new JPagination($this->_total, $limitstart, $this->_limit);
        
        $this->onExecuteAfter('getComments', array(&$comments));
        return $comments;
    }

    public function getComment() {
        $this->onExecuteBefore('getComment');

        $id = JRequest::getVar('id', 0);
        $comment = KMSystem::getTableByIds(array($id), 'comments', array(
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
        ));
        if(!empty($comment)){
            $comment->user = KMUsers::getUser($comment->user);
            $query = "select p.*,(select i.file from #__ksenmart_images as i where i.owner_id=p.id and i.owner_type=p.type order by i.ordering limit 1) as image from #__ksenmart_products as p where p.id='{$comment->product}'";
            $this->_db->setQuery($query);
            $comment->product = $this->_db->loadObject();
            
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

    public function addComment() {
        $this->onExecuteBefore('addComment');

        $user       = KMUsers::getUser();
        $jinput     = JFactory::getApplication()->input;
        
        $name       = $jinput->get('comment_name', $user->name, 'string');
        $product    = $jinput->get('id', 0, 'int');
        $rate       = $jinput->get('comment_rate', 0, 'int');
        $comment    = $jinput->get('comment_comment', null, 'string');
        $good       = $jinput->get('comment_good', null, 'string');
        $bad        = $jinput->get('comment_bad', null, 'string');
        
        $comment_object = new stdClass();
        $comment_object->user_id    = $user->id;
        $comment_object->product_id = $product;
        $comment_object->name       = $user->name;
        $comment_object->comment    = $comment;
        $comment_object->good       = $good;
        $comment_object->bad        = $bad;
        $comment_object->rate       = $rate;

        try{
            $result = $this->_db->insertObject('#__ksenmart_comments', $comment_object);
            
            $this->onExecuteAfter('addComment', array(&$result));
            return true;
        }catch(Exception $e){}
    }

    public function addShopReview() {
        $this->onExecuteBefore('addShopReview');

        $name   = JRequest::getVar('name', $user->name);
        $review = JRequest::getVar('review', null);
        $rate   = JRequest::getInt('rate', 0, 'post');
        $user   = KMUsers::getUser();

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
            $object->comment = $this->_db->getEscaped($comment);
            
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
        $query->leftjoin('#__ksenmart_users AS kmu ON kmu.id=c.user_id');
        $query->leftjoin('#__users AS u ON kmu.id=u.id');
        $query->leftjoin('#__ksenmart_files AS uf ON uf.owner_id=u.id');
        $query->where("c.type='shop_review'");
        $query->where("c.published=1");
        $query->where('c.user_id='.$uid);
        $query->order('c.date_add DESC');
        echo $query;
        $this->_db->setQuery($query);
        
        $reviews = KMUsers::setAvatarLogoInObject($this->_db->loadObject());
        
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
        $query->leftjoin('#__ksenmart_users AS u ON u.id=c.user_id');
        $query->leftjoin('#__ksenmart_files AS uf ON uf.owner_id=u.id');
        $query->where("c.type='shop_review'");
        $query->where("c.published=1");
        $query->where('c.id='.$id);
        $query->order('c.date_add DESC');
        $this->_db->setQuery($query);
        
        $review = KMUsers::setAvatarLogoInObject($this->_db->loadObject());
        
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
        $query->leftjoin('#__ksenmart_users AS u ON u.id=c.user_id');
        $query->leftjoin('#__ksenmart_files AS uf ON uf.owner_id=u.id');
        $query->where("type='shop_review'");
        $query->where("published=1");
        $query->order('date_add DESC');
        $this->_db->setQuery($query);
        $reviews = KMUsers::setAvatarLogoInObject($this->_db->loadObjectList());
        
        $this->onExecuteAfter('getShopReviewsList', array(&$reviews));
        return $reviews;
    }
    
    public function addCommentReply($id, $comment, $product_id) {
        $this->onExecuteBefore('addCommentReply', array(&$id, &$comment, &$product_id));
        
        if(!empty($id) && $id > 0){
            $user           = KMUsers::getUser();
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