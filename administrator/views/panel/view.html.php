<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.viewkmadmin');

class KsenMartViewPanel extends JViewKMAdmin {

    function display($tpl = null) {
		$widget_type=$this->state->get('widget_type');
		if ($widget_type!='all')
			$this->path->addItem(JText::_('ksm_'.$widget_type));
        $this->document->addScript(JURI::base().'components/com_ksenmart/js/jquery.mousewheel.min.js');
        $this->document->addScript(JURI::base().'components/com_ksenmart/js/jquery-ui.js');
        $this->document->addScript(JURI::base().'components/com_ksenmart/js/panel.js');
        $this->document->addStyleSheet(JURI::base().'components/com_ksenmart/css/ui-lightness/jquery-ui-1.8.20.custom.css');
        $this->widgets_groups = $this->get('Widgets');
        parent::display($tpl);
    }
}