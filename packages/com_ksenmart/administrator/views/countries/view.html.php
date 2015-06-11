<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewksadmin');
class  KsenMartViewCountries extends JViewKSAdmin
{

    function display($tpl = null)
    {
		$this->path->addItem(JText::_('ksm_trade'),'index.php?option=com_ksen&widget_type=trade&extension=com_ksenmart');
		$this->path->addItem(JText::_('ksm_countries'));
		switch ($this->getLayout())
		{
            case 'region':
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/region.js');
                $model = $this->getModel();
                $region = $model->getRegion();
				$model->form='region';
                $form = $model->getForm();
                if ($form) $form->bind($region);
                $this->title = JText::_('ksm_countries_region_editor');
                $this->form=$form;
                break;		
            case 'country':
                $this->document->addScript(JURI::base() . 'components/com_ksenmart/js/country.js');
                $model = $this->getModel();
                $country = $model->getCountry();
				$model->form='country';
                $form = $model->getForm();
                if ($form) $form->bind($country);
                $this->title = JText::_('ksm_countries_country_editor');
                $this->form=$form;
                break;		
			default:
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/jquery.custom.min.js');
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/list.js');
				$this->document->addScript(JURI::base().'components/com_ksenmart/js/listmodule.js');
				$this->items=$this->get('ListItems');
				$this->total=$this->get('Total');
		}	
        parent::display($tpl);
    }

}