<?php	 		 		 	
defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.viewkmadmin');

class  KsenMartViewSendMails extends JViewKMAdmin
{

    function display($tpl = null)
    {
		$this->path->addItem(JText::_('users'),'index.php?option=com_ksenmart&view=panel&component_type=users');
		$this->path->addItem(JText::_('sendmails'));
		switch ($this->getLayout())
		{
			case 'mail':
				$template=$this->get('Template');
				$editor=JFactory::getEditor();
				$this->assignref('template',$template);				
				$this->assignref('editor',$editor);			
				break;
			case 'template':
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/sendmail_template.js');	
				$template=$this->get('Template');
				$editor=JFactory::getEditor();
				$this->assignref('template',$template);				
				$this->assignref('editor',$editor);					
			case 'text':
				break;
			default:
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/sendmails.js');	
				$template=$this->get('Template');
				$editor=JFactory::getEditor();
				$this->assignref('template',$template);				
				$this->assignref('editor',$editor);				
		}	
        parent::display($tpl);
    }

}