<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
class KsenMartControllerComments extends JControllerLegacy {
    
    public $value = null;

    public function add_shop_review(){
        $model = $this->getModel('comments');
        $model->addShopReview();
        $this->setRedirect(JRoute::_($_SERVER["REQUEST_URI"], false));
    }
}