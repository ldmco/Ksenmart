<?php defined('_JEXEC') or die;

jimport('joomla.application.component.viewkmadmin');

class KsenMartViewAllSettings extends JViewKMAdmin {

    function display($tpl = null) {
        $this->path->addItem(JText::_('ksm_allsettings'));
        $form = $this->get('Form');
        $component = $this->get('Component');
        foreach ($form as $name => $f) {
            if ($f && $component->params) {
                $form[$name]->bind($component->params);
            }
        }
        $this->form = $form;
        $this->component = $component;
        parent::display($tpl);
    }

}