<?php	 		 		 	
defined( '_JEXEC' ) or die;
jimport( 'joomla.application.component.viewkmadmin');

class  KsenMartViewReports extends JViewKMAdmin
{

    function display($tpl = null)
    {
		$this->path->addItem(JText::_('ksm_trade'),'index.php?option=com_ksenmart&view=panel&component_type=trade');
		$this->path->addItem(JText::_('ksm_reports'));
		$this->document->addScript(JURI::base().'components/com_ksenmart/js/jquery.custom.min.js');
		$this->document->addScript(JURI::base().'components/com_ksenmart/js/jquery.ui.datepicker-ru.js');
		$this->document->addStyleSheet(JURI::base().'components/com_ksenmart/css/ui-lightness/jquery-ui-1.8.20.custom.css');		
		$this->document->addScript(JURI::base().'components/com_ksenmart/js/list.js');
		$this->document->addScript(JURI::base().'components/com_ksenmart/js/listmodule.js');		
		$this->report=$this->state->get('report');
		switch ($this->report)
		{
			case 'favoritesReport':	
			case 'watchedReport':	
			case 'productsReport':	
				$this->items=$this->get('ListItems');
				$this->total=$this->get('Total');			
				break;			
			case 'ordersReport':	
				$this->items=$this->get('ListItems');
				$this->total=$this->get('Total');	
				$this->total_cost=$this->get('TotalCost');	
				break;
		}	
        parent::display($tpl);
    }

}