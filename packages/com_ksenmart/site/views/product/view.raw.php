<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewks');
class KsenMartViewProduct extends JViewKS {
	
    public function display($tpl = null) {
	    $this->params = JComponentHelper::getParams('com_ksenmart');
        $model        = $this->getModel();
        $this->state  = $this->get('State');
        
        if ($model->_id) {
            $this->product = $model->getProduct();

            if (!$this->product){
                $this->setLayout('no_product');
            }
        }

        parent::display($tpl);
    }
}
