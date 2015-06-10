<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewksadmin');
class KsenMartViewReports extends JViewKSAdmin {
	
	function display($tpl = null) {
		$this->path->addItem(JText::_('ksm_trade') ,'index.php?option=com_ksen&widget_type=trade&extension=com_ksenmart');
		$this->path->addItem(JText::_('ksm_reports'));
		$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.custom.min.js');
		$this->document->addScript(JURI::base() . 'components/com_ksenmart/js/jquery.ui.datepicker-ru.js');
		$this->report = $this->state->get('report');
		
		switch ($this->report) {
			case 'favoritesReport':
			case 'watchedReport':
			case 'productsReport':
				$this->items = $this->get('ListItems');
				$this->total = $this->get('Total');
			break;
			case 'ordersReport':
				$this->items = $this->get('ListItems');
				$this->total = $this->get('Total');
				$this->total_cost = $this->get('TotalCost');
			break;
		}
		parent::display($tpl);
	}
}