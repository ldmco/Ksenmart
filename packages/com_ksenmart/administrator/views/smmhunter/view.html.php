<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewksadmin');
class KsenMartViewSmmhunter extends JViewKSAdmin {

    function display($tpl = null) {
		$this->path->addItem(JText::_('ks_panel'), 'index.php?option=com_ksen&extension=com_ksenmart');
        $this->path->addItem(JText::_('ks_smmhunter'));
		
		$this->plg_params = $this->get('PlgParams');
		
		if (empty($this->plg_params->login))
		{
			$model = $this->getModel();
			$model->form = 'smmhunter';
			$this->form = $model->getForm();
		}
		
		$this->document->addStyleSheet(JURI::root().'plugins/system/smmhunter/assets/css/smmhunter.css');		
		$this->document->addScript(JURI::root().'plugins/system/smmhunter/assets/js/smmhunter.js');		
		
        parent::display($tpl);
    }

}