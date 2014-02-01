<?php
/**
 * @version     1.0.0
 * @package     com_ksenmart
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Bereza Kirill <takt.bereza@gmail.com> - http://
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Accounts list controller class.
 */
class KsenMartControllerAccount extends JController {

    
    private $_session = null;
    private $_data    = null;
    private $_model   = null;
    
    private $_login         = null;
    private $_password      = null;
    private $_auth          = null;
    
    private $_account_info    = null;
    
    public function __construct(){

        $this->_session = JFactory::getSession();
        $this->_model   = KMSystem::getModel('account');
        
        if(!empty($this->_model->_auth) && !$this->_model->checkIssetAuth()){
            $this->fullTimeAuth();
        }
        
        parent::__construct();
    }
    
    public function getAccountInfo(){
        if($this->checkAuthorize()){
            $this->_account_info->user_info         = $this->_model->getUserInfo();
            $this->_account_info->user_balance_info = $this->_model->getUserBalance();
            $this->_account_info->user_open_tickets = $this->_model->getUserOpenTickets();
        }
        return $this->_account_info;
    }

    public function test123(){
        echo $this->_model->test123();
    }

    public function setUserAnswer(){
        $answer = $this->_model->setUserAnswer();

        if(!isset($answer->error) && $answer->result == 'ok'){
            return true;
        }elseif(isset($answer->error)){
            echo $answer->error->msg;
        }
    }

    public function getUserBalance(){
        $balance = $this->_model->getUserBalance();

        if(!isset($balance->error)){
            return $balance;
        }elseif(isset($balance->error)){
            echo $balance->error->balance;
        }
    }

    public function createTicket(){
        $c_ticket = $this->_model->createTicket();

        if(!isset($c_ticket->error) && $c_ticket->result == 'ok'){
            return true;
        }elseif(isset($c_ticket->error)){
            echo $c_ticket->error->msg;
        }
    }
    
    public function createCredit(){
        $c_credit = $this->_model->createCredit();

        if(!isset($c_credit->error) && $c_credit->result == 'OK'){
            echo $c_credit->{'credit.id'};
            //print_r($c_credit);
            return true;
        }elseif(isset($c_credit->error)){
            echo $c_credit->error->msg;
        }
    }
    
    public function saveSettings(){
        $settings = $this->_model->saveSettings();

        if(!isset($settings->error) && $settings->result == 'OK'){
            return true;
        }elseif(isset($settings->error)){
            echo $settings->error->msg;
        }
    }

    public function loadImages(){

        if($this->_model->loadImages()){
            return true;
        }
        return false;
    }
    
    public function getCurrentUserAvatar(){
        $elid   = $this->_model->getUserInfo()->id;
        if(!empty($elid)){
            $this->_model->getAvatarThumb($elid, true);
            return true;
        }
        return false;
    }
    
    public function createCreditQiwi(){
        $c_credit = $this->_model->createCreditQiwi();

        if(!isset($c_credit->error) && $c_credit->result == 'OK'){
            //echo $c_credit->{'credit.id'};
            
            return true;
        }elseif(isset($c_credit->error)){
            echo $c_credit->error->msg;
        }
    }
    
    public function getUserCredits(){
        return $this->_model->getUserCredits();
    }
    
    public function getCreditPaymentMethod(){
        $elid           = JRequest::getInt('elid', 1, 'post');
        $credits        = $this->getUserCredits();
        $current_credit = array();

        foreach($credits as $credit){
            if($credit->id == $elid){
                $current_credit = $credit;
                break;
            }else{
                continue;
            }
        }
                
        //print_r($current_credit);
        echo strtolower($current_credit->ctype);
        return true;
    }
    
    public function createVHost(){
        $c_vhost = $this->_model->createVHost();

        if(!isset($c_vhost->error) && $c_vhost->result == 'OK'){
            //echo json_encode($c_vhost);
            return true;
        }elseif(isset($c_vhost->error)){
            echo $c_vhost->error->msg;
        }
        return false;
    }
    
    public function createDomainContact(){
        $jinput = JFactory::getApplication()->input;
        
        $params = array(
            'sok'               => 'yes',
            'ctype'             => $jinput->post->get('customertype', 'person', 'string'),
            'cname'             => $jinput->post->get('custname', null, 'string'),
            'lastname_ru'       => $jinput->post->get('lastname_ru', null, 'string'),
            'firstname_ru'      => $jinput->post->get('firstname_ru', null, 'string'),
            'middlename_ru'     => $jinput->post->get('middlename_ru', null, 'string'),
            'la_country'        => $jinput->post->get('la_country', null, 'string'),
            'email'             => $jinput->post->get('email', null, 'string'),
            'phone'             => str_replace('+', '%2B', $jinput->post->get('phone', null, 'string')),
            'mobile'            => str_replace('+', '%2B', $jinput->post->get('mobile', null, 'string')),
            'fax'               => $jinput->post->get('fax', null, 'string'),
            'passport_series'   => $jinput->post->get('passport_series', null, 'string'),
            'passport_org'      => $jinput->post->get('passport_org', null, 'string'),
            'passport_date'     => $jinput->post->get('passport_date', null, 'string'),
            'birthdate'         => $jinput->post->get('birthdate', '1970-12-12', 'string'),
            'inn'               => $jinput->post->get('inn', null, 'string'),
            'private'           => $jinput->post->get('private_i', 'on', 'string'),
            'la_postcode'       => $jinput->post->get('la_postcode', 0, 'int'),
            'la_state'          => $jinput->post->get('la_state', null, 'string'),
            'la_city'           => $jinput->post->get('la_city', null, 'string'),
            'la_address'        => $jinput->post->get('la_address', null, 'string'),
            'pa_postcode'       => $jinput->post->get('pa_postcode', 0, 'int'),
            'pa_state'          => $jinput->post->get('pa_state', null, 'string'),
            'pa_city'           => $jinput->post->get('pa_city', null, 'string'),
            'pa_address'        => $jinput->post->get('pa_address', null, 'string'),
            'pa_addressee'      => $jinput->post->get('pa_addressee', null, 'string'),
            'elid'              => $jinput->post->get('id', null, 'string')
        );
        
        $params['firstname']    = ucfirst(KMFunctions::GenAlias($jinput->post->get('firstname_ru', null, 'string')));
        $params['lastname']     = ucfirst(KMFunctions::GenAlias($jinput->post->get('lastname_ru', null, 'string')));
        $params['middlename']   = ucfirst(substr(KMFunctions::GenAlias($jinput->post->get('middlename_ru', null, 'string')), 0, 1));
        
        $debug          = array();
        $contact_info   = new stdClass;
        $flag           = true;

        if(empty($params['elid'])){
            $c_contact = $this->_model->createDomainContact($params);
        }else{
            $c_contact = $this->_model->editDomainContact($params);
            $flag = false;
            
            $debug['c_contact']  = $c_contact;
            $contact_info->debug = $debug;
            exit(json_encode($contact_info));
        }

        if($flag){
            if((!isset($c_contact->error) && $c_contact->result == 'OK')){
                
                $redirect = parse_url($c_contact->redirect, PHP_URL_QUERY);
                parse_str($redirect, $params);
            
                unset($params['func']);
                unset($params['auth']);
                
                $params['sok']  = 'ok';
                $params['elid'] = $c_contact->{'domaincontact.id'};
                
                $params['phone']  = str_replace('+', '%2B', $params['phone']);
                $params['mobile'] = str_replace('+', '%2B', $params['mobile']);
                $c_contact = $this->_model->editDomainContact($params);
                //print_r($c_contact);
                $debug['params'] = $params;
                
                $contact_info->id       = $params['elid'];
                $contact_info->name     = $params['cname'];
                $contact_info->result   = 'ok';
            }
        }
        
        $contact_info->debug    = $debug;
        exit(json_encode($contact_info));
    }
    
    public function checkDomains(){
        $c_domain = $this->_model->checkDomains();

        if(isset($c_domain->result) && $c_domain->result == 'success'){
            $c_domain = $this->sortDomainsList($c_domain);
            exit(json_encode($c_domain));
        }elseif(isset($c_domain->result)){
            exit($c_domain->error_text);
        }
        return false;
    }
    
    private function sortDomainsList($domains, $reverse = false){
        if(!empty($domains)){
            $errors_domains  = array();
            $success_domains = array();

            foreach($domains->answer->domains as $domain){
                if($domain->result != 'error'){
                    $success_domains[] = $domain;
                }elseif($domain->result == 'error'){
                    $errors_domains[] = $domain;
                }
            }
            
            $domains->answer->domains = null;
            if($reverse){
                $domains->answer->domains = $errors_domains;
                $domains->answer->domains = array_merge($domains->answer->domains, $success_domains);
            }else{
                $domains->answer->domains = $success_domains;
                $domains->answer->domains = array_merge($domains->answer->domains, $errors_domains);
            }
            return $domains;
        }
        return false;
    }
    
    public function getDomainsList(){
        $c_domain = $this->_model->getDomains();

        if($c_domain->result == 'success'){
            echo json_encode($c_domain);
            return true;
        }elseif(isset($c_domain->result)){
            echo $c_domain->error_text;
        }
        return false;
    }
    
    public function setOrderState(){
        JRequest::setVar('sort_by', JRequest::getVar('order_field', null));
    }
    
    public function registerDomains(){
        $r_domain = $this->_model->registerDomains();

        if(!isset($r_domain->error)){
            echo json_encode($r_domain);
            return true;
        }elseif(isset($r_domain->error)){
            echo json_encode($r_domain);
        }
        return false;
    }
    
    public function domainRerunS1(){
        $r_domain = $this->_model->domainRerunS1();

        if(!isset($r_domain->error)){
            echo json_encode($r_domain);
            return true;
        }elseif(isset($r_domain->error)){
            echo json_encode($r_domain);
        }
        return false;
    }
    
    public function domainRerunS2(){
        $r_domain = $this->_model->domainRerunS2();

        if(!isset($r_domain->error)){
            echo json_encode($r_domain);
            return true;
        }elseif(isset($r_domain->error)){
            echo json_encode($r_domain);
        }
        return false;
    }
    
    public function setDomainRenew(){
        $rn_domain = $this->_model->setDomainRenew();

        if(!isset($rn_domain->error)){
            echo json_encode($rn_domain);
            return true;
        }elseif(isset($rn_domain->error)){
            echo json_encode($rn_domain);
        }
        return false;
    }
    
    public function domainEdit(){
        $e_domain = $this->_model->domainEdit();

        if(!isset($e_domain->error)){
            echo json_encode($e_domain);
            return true;
        }elseif(isset($e_domain->error)){
            echo json_encode($e_domain);
        }
        return false;
    }
    
    public function registerDomainsData(){
        $r_domain = $this->_model->registerDomainsData();

        if(!isset($r_domain->error)){
            echo json_encode($r_domain);
            return true;
        }elseif(isset($r_domain->error)){
            echo json_encode($r_domain->error->msg);
        }
        return false;
    }
    
    public function moveToArchive(){
        $data = (object)JRequest::get('post');
        $ids = $data->ids;
        $ids = implode(', ', $ids);
        
        $open_ticket = $this->_model->moveToArchive($ids);

        if(!isset($open_ticket->error) && $open_ticket->result == 'ok'){
            return true;
        }elseif(isset($open_ticket->error)){
            echo $open_ticket->error->msg;
        }
    }
    
    public function removePayments(){
        $data = (object)JRequest::get('post');
        $ids = $data->ids;
        $ids = implode(', ', $ids);
        
        $removed_payments = $this->_model->removePayments($ids);

        if(!isset($removed_payments->error) && $removed_payments->result == 'ok'){
            return true;
        }elseif(isset($removed_payments->error)){
            echo $removed_payments->error->msg;
        }
    }
    
    public function removeVhosts(){
        $data = (object)JRequest::get('post');
        $ids = $data->ids;
        $ids = implode(', ', $ids);
        
        $removed_vhosts = $this->_model->removeVhosts($ids);

        if(!isset($removed_vhosts->error) && $removed_vhosts->result == 'ok'){
            return true;
        }elseif(isset($removed_vhosts->error)){
            echo $removed_vhosts->error->msg;
        }
    }
    
    public function moveFromArchive(){
        $data = (object)JRequest::get('post');
        $ids = $data->ids;
        $ids = implode(', ', $ids);
        
        $archived_ticket = $this->_model->moveFromArchive($ids);

        if(!isset($archived_ticket->error) && $archived_ticket->result == 'ok'){
            return true;
        }elseif(isset($archived_ticket->error)){
            echo $archived_ticket->error->msg;
        }
    }
    
    public function getCountries(){
        return $this->_model->getCountries();
    }
    
    public function checkAuthorize() {
        return $this->_model->checkAuthorize();
    }
    
    public function logout(){
        $this->_session->clear('auth');
        $this->_auth = null;
        $this->_model->removeEncryptAuth();
        $this->setRedirectToIndex('Вы успешно вышли из аккаунта!');
    }
    
    public function setReg(){
        $_data  = (object)JRequest::get('post');

        if(!empty($_data)){
            if($_data->passwd == $_data->confirm){
                
                $_data->passwd  = base64_encode($_data->passwd);
                $_data->confirm = base64_encode($_data->confirm);
                $_data->set     = true;
                $_data->sok     = 'ok';
                $_data->is_reg  = 'ok';
                
                $response = json_decode(KMSystem::getLdmApiData('register', null, $_data));
                
                if(isset($response->result) && $response->result == 'OK'){
                    $this->setAuth($_data->username, base64_decode($_data->passwd));
                    return true;
                }else{
                    echo $response->error->msg;
                }
            }
        }
        return false;
    }
    
    public function setNewPass(){
        $jinput     = JFactory::getApplication()->input;
        //$elid       = $this->_model->getUserId();
        
        $name       = $jinput->get('login', null, 'string');
        $password   = $jinput->get('password', null, 'string');
        $confirm    = $jinput->get('confirm', null, 'string');
        
        if(!empty($name) && !empty($password) && !empty($confirm)){
            if($password == $confirm){
                $_data          = new stdClass;
                $_data->name    = $name;
                $_data->passwd  = base64_encode($password);
                $_data->confirm = base64_encode($confirm);
                $_data->set     = true;
                $_data->task    = 'setNewPass';
                $_data->is_reg  = 'ok';
                
                $response = json_decode(KMSystem::getLdmApiData('register', null, $_data));

                if($response->result == 'OK'){
                    $this->setAuth($_data->name, base64_decode($_data->passwd));
                    return true;
                }else{
                    echo $response->error->msg;
                }
            }
        }
    }
    
    public function setAuth($login = null, $password = null){
        $this->_data = (object)JRequest::get('post');
        
        if(!empty($this->_data->login) && !empty($this->_data->password)){
            $this->_login    = $this->_data->login;
            $this->_password = $this->_data->password;
        }else{
            $this->_login       = $login;
            $this->_password    = $password;
        }
        
        if(!empty($this->_login) && !empty($this->_password)){
            $auth_json = file_get_contents('https://ldmco.ru/manager/billmgr?out=json&username='.$this->_login.'&password='.$this->_password.'&func=auth');
            if(!empty($auth_json)){
                $auth_json = json_decode($auth_json);
                if(isset($auth_json->expirepass)){
                    exit('expirepass');
                }
                if(!isset($auth_json->authfail)){
                    $this->_auth = $auth_json->auth;
                    $this->_session->set('auth', $this->_auth);
                    
                    $this->setEncryptAuth($this->_login, $this->_password);
                                                                                
                    return 1;
                }
            }
        }
        
        echo 'Неверный логин или пароль!';
    }
    
    private function setEncryptAuth(){
        return $this->_model->setEncryptAuth($this->_login, $this->_password, $this->_auth);        
    }
    
    private function fullTimeAuth(){
        $data = $this->_model->getEncryptAuth();
        if($data){
            list($login, $password) = explode(':', $data);
            $this->setAuth($login, $password);
            return true;
        }
        return false;
    }
    
    private function setRedirectToIndex($msg = ''){
        $this->setRedirect('index.php?option=com_ksenmart', $msg);
    }
    
    public function testAPI(){
        $api_key = $this->_model->getUserFullInfo()->api_key;
        $params = array(
            'task'      => 'test',
            'api_key'   => $api_key
        );
        echo KMSystem::getLdmApiData('register', null, $params);
    }
}