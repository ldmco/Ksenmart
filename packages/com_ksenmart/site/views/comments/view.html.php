<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewks');
class KsenMartViewComments extends JViewKS {
    function display($tpl = null) {
        $app            = JFactory::getApplication();
        $path           = $app->getPathway();
        $document       = JFactory::getDocument();
        $this->params   = JComponentHelper::getParams('com_ksenmart');
        
        $shop_name = $this->params->get('shop_name');
        $pref      = $this->params->get('path_separator');
        $id        = $app->input->get('id', 0, 'int');
        $layout    = $this->getLayout();
        
        switch ($layout) {

            case 'comments':
                $comments = $this->get('comments');
                $pagination = $this->get('Pagination');
                
                $this->assignRef('pagination', $pagination);
                $this->assignRef('rows', $comments);
            break;

            case 'comment':
                if($id > 0) {
                    if(!JFactory::getConfig()->get('config.caching', 0)) {
                        $path->addItem(JText::_('KSM_REVIEW_ITEM_PATH_TITLE'));
                    }
                    
                    $comment = $this->get('Comment');
                    if(!$comment){
                        JError::raiseError(404, 'Page not found');
                        return false;
                    }
                    
                    $this->assignRef('comment', $comment);
                }else{
                    JError::raiseError(404, 'Page not found');
                    return false;
                }
            break;

            case 'reviews':
				$document->addScript(JURI::base() . 'components/com_ksenmart/js/reviews.js', 'text/javascript', true);
                
                $reviews      = $this->get('ShopReviewsList');
                $user         = KSUsers::getUser();
                $isset_review = KSSystem::issetReview($user->id);
                
                $this->assignRef('reviews', $reviews);
                $this->assignRef('user', $user);
                $this->assignref('show_shop_review', $isset_review);
            break;

            case 'review':
                if($id > 0){
                    $user         = KSUsers::getUser();
                    $this->params = JComponentHelper::getParams('com_ksenmart');
                    $model        = $this->getModel();
                    
                    $review = $model->getShopReviewById($id);
                    if(!$review){
                        JError::raiseError(404, 'Page not found');
                        return false;
                    }
                    $isset_review = KSSystem::issetReview($user->id);

                    $this->assignRef('review', $review);
                    $this->assignRef('user', $user);
                    $this->assignref('show_shop_review', $isset_review);

                    if(!JFactory::getConfig()->get('config.caching', 0)) {
                        $path->addItem(JText::sprintf('KSM_SHOP_REVIEW_PATH_TITLE_TEXT', $review->name, $shop_name));
                    }
                }else{
                    JError::raiseError(404, 'Page not found');
                    return false;
                }
            break;
        }

        parent::display($tpl);
    }
}