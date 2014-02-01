<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.viewkmadmin');

class KsenMartViewComments extends JViewKMAdmin {

    function display($tpl = null) {
		$this->path->addItem(JText::_('ksm_users'),'index.php?option=com_ksenmart&view=panel&component_type=users');
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