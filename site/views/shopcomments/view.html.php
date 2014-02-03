<?php defined('_JEXEC') or die;
jimport('joomla.application.component.viewkm');

class KsenMartViewShopComments extends JViewKM {
    function display($tpl = null) {
        $app            = JFactory::getApplication();
        $path           = $app->getPathway();
        $document       = JFactory::getDocument();
        $this->params   = JComponentHelper::getParams('com_ksenmart');
        
        $names_component = $this->params->get('shop_name');
        $pref            = $this->params->get('path_separator');
        $doc_title       = $names_component . $pref . 'Отзывы';
        $id              = JRequest::getVar('id', 0);
        $layout          = $this->getLayout();
        
        $document->setTitle($doc_title);
        
        if($layout == 'shopreviews'){
            if(!JFactory::getConfig()->getValue('config.caching', 0)) {
                $path->addItem(JText::_('KSM_SHOP_REVIEWS_PATH_TITLE'));
            }
            
            $document->addStyleSheet(JURI::base() . 'components/com_ksenmart/css/shop_reviews.css');
            
            $reviews = $this->get('ShopReviewsList');
            $user    = KMUsers::getUser();
            
            $this->assignRef('reviews', $reviews);
            $this->assignRef('user', $user);
            $this->assignref('show_shop_review', KMSystem::issetReview($user->id));
        }elseif($layout == 'shopreview'){
            
            $user           = KMUsers::getUser();
            $this->params   = JComponentHelper::getParams('com_ksenmart');
            $model          = $this->getModel();
            $id            = JRequest::getInt('id', 0, 'get');
            
            $shop_name = $this->params->get('shop_name');
            if(empty($shop_name)){
                $shop_name = 'KsenMart';
            }
            
            $document->addStyleSheet(JURI::base() . 'components/com_ksenmart/css/shop_reviews.css');
            
            $review = $model->getShopReviewById($id);

            $this->assignRef('review', $review);
            $this->assignRef('user', $user);
            $this->assignref('show_shop_review', KMSystem::issetReview($user->id));

            $document->setTitle(JText::sprintf('KSM_SHOP_REVIEW_PATH_TITLE_TEXT', $review->name, $shop_name));
            if(!JFactory::getConfig()->getValue('config.caching', 0)) {
                $path->addItem(JText::_('KSM_SHOP_REVIEWS_PATH_TITLE'), 'index.php?option=com_ksenmart&view=shopcomments&layout=shopreviews');
                $path->addItem(JText::sprintf('KSM_SHOP_REVIEW_PATH_TITLE_TEXT', $review->name, $shop_name));
            }
            
        }elseif($layout != 'shopreviews'){
            if($id == 0) {
                if(!JFactory::getConfig()->getValue('config.caching', 0)){
                    $path->addItem(JText::_('KSM_REVIEWS_LIST_PATH_TITLE'));
                }
                $comments = $this->get('Comments');
                $pagination = $this->get('Pagination');
                
                $this->assignRef('pagination', $pagination);
                $this->assignRef('rows', $comments);
                $this->setLayout('comments');
            }else{
                if(!JFactory::getConfig()->getValue('config.caching', 0)) {
                    $path->addItem(JText::_('KSM_REVIEWS_LIST_PATH_TITLE'), 'index.php?option=com_ksenmart&view=shopcomments&Itemid=' . KMSystem::getShopItemid());
                    $path->addItem(JText::_('KSM_REVIEW_ITEM_PATH_TITLE'));
                }
                
                $comment = $this->get('Comment');
                
                $this->assignRef('comment', $comment);
                $this->setLayout('comment');
    
            }
        }
        parent::display($tpl);
    }
}