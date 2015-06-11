<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewksadmin');
class KsenViewPanel extends JViewKSAdmin {

    public function display($tpl = null) {
		$widget_type=$this->state->get('widget_type');
		if ($widget_type!='all')
			$this->path->addItem(JText::_('ks_'.$widget_type));
        $this->document->addScript(JURI::base().'components/com_ksen/assets/js/jquery.mousewheel.min.js');
        $this->document->addScript(JURI::base().'components/com_ksen/assets/js/jquery-ui.js');
        $this->document->addScript(JURI::base().'components/com_ksen/assets/js/panel.js');
        $this->widgets_groups = $this->get('Widgets');
        parent::display($tpl);
    }
}