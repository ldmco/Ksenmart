<?php	 		 		 	
defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.viewkmadmin');

class  KsenMartViewUsers extends JViewKMAdmin
{

    function display($tpl = null)
    {
		$this->path->addItem(JText::_('ksm_users'),'index.php?option=com_ksenmart&view=panel&component_type=users');
		$this->path->addItem(JText::_('ksm_users'));	
		switch ($this->getLayout())
		{
			case 'usergroup':
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/usergroup.js');
                $model = $this->getModel();
                $usergroup = $model->getUserGroup();
                $model->form = 'usergroup';
                $form = $model->getForm();
                if($form) $form->bind($usergroup);
                $this->title = JText::_('ksm_users_usergroup_editor');
                $this->form = $form;
				break;
			case 'userfield':
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/userfield.js');
                $model = $this->getModel();
                $userfield = $model->getUserField();
                $model->form = 'userfield';
                $form = $model->getForm();
                if($form) $form->bind($userfield);
                $this->title = JText::_('ksm_users_userfield_editor');
                $this->form = $form;
				break;				
			case 'user':
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/user.js');
                $model = $this->getModel();
                $user = $model->getUser();
                $model->form = 'user';
                $form = $model->getForm();
                if($form) $form->bind($user);
                $this->title = JText::_('ksm_users_user_editor');
                $this->form = $form;
				break;
			 case 'search':
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/jquery.custom.min.js');
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/list.js');
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/listmodule.js');
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/userssearch.js');
				$this->title = JText::_('ksm_users_search');
				$this->items=$this->get('ListItems');
				$this->total=$this->get('Total');
				break;				
			default:
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.custom.min.js');
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/list.js');
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/listmodule.js');
				$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/users.js');
                $this->items = $this->get('ListItems');
                $this->total = $this->get('Total');
		}		
		parent::display($tpl);
    }

}