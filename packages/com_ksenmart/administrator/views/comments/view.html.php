<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewksadmin');
class KsenMartViewComments extends JViewKSAdmin {

    function display($tpl = null) {
		$this->path->addItem(JText::_('ksm_users'),'index.php?option=com_ksen&widget_type=users&extension=com_ksenmart');
		$this->path->addItem(JText::_('ksm_comments'));	
        switch ($this->getLayout()) {
			case 'rate':
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/rate.js');
                $model = $this->getModel();
                $rate = $model->getRate();
                $model->form = 'rate';
                $form = $model->getForm();
                if($form) $form->bind($rate);
                $this->title = JText::_('ksm_comments_rate_editor');
                $this->form = $form;
				break;
			case 'comment':
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/comment.js');
                $model = $this->getModel();
                $comment = $model->getComment();
                $model->form = 'comment';
                $form = $model->getForm();
                if($form) $form->bind($comment);
                $this->title = JText::_('ksm_comments_comment_editor');
                $this->form = $form;
				break;
            default:
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.custom.min.js');
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/list.js');
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/listmodule.js');
                $this->items = $this->get('ListItems');
                $this->total = $this->get('Total');
        }
        parent::display($tpl);
    }
}