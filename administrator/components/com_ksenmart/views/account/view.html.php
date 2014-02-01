<?php defined('_JEXEC') or die;

jimport('joomla.application.component.viewkmadmin');

class KsenMartViewAccount extends JViewKMAdmin {

    public function display($tpl = null) {
        
        $model_account  = KMSystem::getModel('account');
        $layout         = $this->getLayout();        
        
        $this->document->addStyleSheet(JURI::base() . 'components/com_ksenmart/css/account.css');

        if(!$model_account->checkAuthorize() && $layout != 'default_login'){
            $app   = JFactory::getApplication();
            $popup = $app->input->get('popup', 'page', 'string');
            
            if($popup == 'popup'){
                exit(json_encode(array('redirect' => 'index.php?option=com_ksenmart&view=account&layout=default_login')));
            }
            
            $app->redirect('index.php?option=com_ksenmart&view=account&layout=default_login');
        }

        $actn   = JRequest::getVar('actn', null);
        
        switch($layout){
            case 'tickets_list':
                $tickets = $this->get('UserTickets');
                $this->assignRef('tickets', $tickets);
            break;
            case 'archived_tickets':
                $archived_tickets = $this->get('ArchivedTickets');
                $this->assignRef('archived_tickets', $archived_tickets);
            break;
            case 'credits_list':
                $credits = $this->get('UserCredits');
                $this->assignRef('credits', $credits);
            break;
            case 'credit_create':
                $users = $this->get('ProfileUsers');
                $this->assignRef('users', $users);
            break;
            case 'credit_create':
                $users = $this->get('ProfileUsers');
                $this->assignRef('users', $users);
            break;
            case 'credit_qiwi_pay':
                $credit = $this->get('Credit');
                $this->assignRef('credit', $credit);
            break;
            case 'vhost_create':
                $users = $this->get('ProfileUsers');
                $this->assignRef('users', $users);
            break;
            case 'domains':
                $domains = $this->get('Domains');
                $this->assignRef('domains', $domains);
            break;
            case 'vhost':
                $vhosts = $this->get('VHost');
                $this->assignRef('vhosts', $vhosts);
            break;
            case 'ticket':
                $ticket = $this->get('UserTicket');
                $actn   = JFactory::getApplication()->input->get('actn', 'static', 'string');
                
                $this->assignRef('ticket', $ticket);
                $this->assignRef('actn', $actn);
            break;
            case 'ticket_create':
                $services = $this->get('Services');
                $this->assignRef('services', $services);
            break;
            case 'profile':
                $user_info = $this->get('UserFullInfo');
                $profile_info = $this->get('ProfileInfo');
                $user_balance = $this->get('UserBalance');

                $this->assignRef('user_info', $user_info);
                $this->assignRef('profile_info', $profile_info);
                $this->assignRef('user_balance', $user_balance);
            break;
            case 'settings':
                $user_info = $this->get('UserFullInfo');
                $profile_info = $this->get('ProfileInfo');
                
                $this->assignRef('user_info', $user_info);
                $this->assignRef('profile_info', $profile_info);
            break;
            case 'avatar_load':
                $user_info = $this->get('UserFullInfo');
                $this->assignRef('user_info', $user_info);
            break;
            case 'domain_create':
                $domain_contacts = $this->get('DomainContacts');
                
                $this->assignRef('domain_contacts', $domain_contacts);
            break;
            case 'domain_rerun':
                $domain_rerun = $this->get('DomainRerunS1');
                
                $this->assignRef('domain_rerun', $domain_rerun);
            break;
            case 'domain_renew':
                $domain_renew = $this->get('DomainRenewS1');
                
                $this->assignRef('domain_renew', $domain_renew);
            break;
            case 'domain_edit':
                $model          = $this->getModel('account');
                $domain_info    = $this->get('DomainEdit');
                $period_id      = $model->getDomainsPeriodId($domain_info->tld);
                
                $this->assignRef('domain_info', $domain_info);
                $this->assignRef('period_id', $period_id);
            break;
            case 'default_domaincontact_create':
                case 'default_login':
                    $countries = $this->get('Countries');
                    
                    $this->assignRef('countries', $countries);
            break;
            default:break;
        }
        
        $model = $this->getModel('account');

        $this->assignRef('layout', $layout);
        $this->assignRef('actn', $actn);
        $this->assignRef('auth', $model->_auth);
        
        parent::display($tpl);
    }

}