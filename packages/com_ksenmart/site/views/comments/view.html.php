<?php defined('_JEXEC') or die;

KSSystem::import('views.viewks');
class KsenMartViewComments extends JViewKS {
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
		$document->addStyleSheet(JURI::base() . 'components/com_ksenmart/css/shop_reviews.css');
        
        if($layout == 'reviews'){
            if(!JFactory::getConfig()->get('config.caching', 0)) {
                $path->addItem(JText::_('KSM_SHOP_REVIEWS_PATH_TITLE'));
            }
            
            $reviews        = $this->get('ShopReviewsList');
            $user           = KSUsers::getUser();
            $isset_review   = KSSystem::issetReview($user->id);
            
            $this->assignRef('reviews', $reviews);
            $this->assignRef('user', $user);
            $this->assignref('show_shop_review', $isset_review);
        }elseif($layout == 'review'){
            
            $user           = KSUsers::getUser();
            $this->params   = JComponentHelper::getParams('com_ksenmart');
            $model          = $this->getModel();
            
            $shop_name = $this->params->get('shop_name');
            if(empty($shop_name)){
                $shop_name = 'KsenMart';
            }
            
            $review         = $model->getShopReviewById($id);
            $isset_review   = KSSystem::issetReview($user->id);

            $this->assignRef('review', $review);
            $this->assignRef('user', $user);
            $this->assignref('show_shop_review', $isset_review);

            $document->setTitle(JText::sprintf('KSM_SHOP_REVIEW_PATH_TITLE_TEXT', $review->name, $shop_name));
            if(!JFactory::getConfig()->get('config.caching', 0)) {
                $path->addItem(JText::_('KSM_SHOP_REVIEWS_PATH_TITLE'), 'index.php?option=com_ksenmart&view=Comments&layout=shopreviews');
                $path->addItem(JText::sprintf('KSM_SHOP_REVIEW_PATH_TITLE_TEXT', $review->name, $shop_name));
            }
            
        }elseif($layout != 'reviews'){
            if($id == 0) {
                if(!JFactory::getConfig()->get('config.caching', 0)){
                    $path->addItem(JText::_('KSM_REVIEWS_LIST_PATH_TITLE'));
                }
                $comments = $this->get('Comments');
                $pagination = $this->get('Pagination');
                
                $this->assignRef('pagination', $pagination);
                $this->assignRef('rows', $comments);
                $this->setLayout('comments');
            }else{
                if(!JFactory::getConfig()->get('config.caching', 0)) {
                    $path->addItem(JText::_('KSM_REVIEWS_LIST_PATH_TITLE'), 'index.php?option=com_ksenmart&view=Comments&Itemid=' . KSSystem::getShopItemid());
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