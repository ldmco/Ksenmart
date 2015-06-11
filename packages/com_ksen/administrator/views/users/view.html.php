<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewksadmin');
class KsenViewUsers extends JViewKSAdmin {
    
    function display($tpl = null) {
        $this->path->addItem(JText::_('ks_users'));
        
        switch ($this->getLayout()) {
            case 'usergroup':
                $this->document->addScript(JURI::base() . 'components/com_ksen/assets/js/usergroup.js');
                $model = $this->getModel();
                $usergroup = $model->getUserGroup();
                $model->form = 'usergroup';
                $form = $model->getForm();
                if ($form) $form->bind($usergroup);
                $this->title = JText::_('ks_users_usergroup_editor');
                $this->form = $form;
                
                break;
            case 'userfield':
                $this->document->addScript(JURI::base() . 'components/com_ksen/assets/js/userfield.js');
                $model = $this->getModel();
                $userfield = $model->getUserField();
                $model->form = 'userfield';
                $form = $model->getForm();
                if ($form) $form->bind($userfield);
                $this->title = JText::_('ks_users_userfield_editor');
                $this->form = $form;
                
                break;
            case 'user':
                $this->document->addScript(JURI::base() . 'components/com_ksen/assets/js/user.js');
                $model = $this->getModel();
                $user = $model->getUser();
                $model->form = 'user';
                $form = $model->getForm();
                if ($form) $form->bind($user);
                $this->title = JText::_('ks_users_user_editor');
                $this->form = $form;
                
                break;
            case 'search':
                $this->document->addScript(JURI::base() . 'components/com_ksen/assets/js/userssearch.js');
                $this->title = JText::_('ks_users_search');
                $this->items = $this->get('ListItems');
                $this->total = $this->get('Total');
                
                break;
            default:
                $this->document->addScript(JURI::base() . 'components/com_ksen/assets/js/users.js');
                $this->items = $this->get('ListItems');
                $this->total = $this->get('Total');
        }
        parent::display($tpl);
    }
}