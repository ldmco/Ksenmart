<?php

/**
 * @version     1.0.0
 * @package     com_ksenmart_billing
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Bereza Kirill <takt.bereza@gmail.com> - http://
 */
defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');

define('ACCOUNT_ROOT_MODULE_PATCH', JPATH_ROOT.'/administrator/modules/mod_ks_account/images/avatars/');
define('LDM_API_PATCH', 'http://api.ldmco.ru/');

/**
 * Methods supporting a list of Ksenmart_billing records.
 */
/**
 * KsenMartModelAccount
 * 
 * @package   
 * @author Bereza Kirill
 * @copyright KsenMart
 * @version 2013
 * @access public
 */
class KsenModelAccount extends JModelKSAdmin {

    public $_auth = null;

    /**
     * KsenMartModelAccount::__construct()
     * 
     * @return
     */
    public function __construct() {

        $this->_session = JFactory::getSession();
        $this->_auth = $this->_session->get('auth');

        parent::__construct();
    }

    protected function populateState($ordering = null, $direction = null){
        $this->onExecuteBefore('populateState');
        
        $app = JFactory::getApplication();

        $this->onExecuteAfter('populateState');
    }

    /**
     * KsenMartModelAccount::checkAuthorize()
     * 
     * @return
     */
    public function checkAuthorize() {
        
        $this->onExecuteBefore('checkAuthorize');
        
        $this->_auth = $this->_session->get('auth');

        if (isset($this->_auth) && !empty($this->_auth) && $this->checkIssetAuth()) {
            return true;
        }
        return false;
    }

    /**
     * KsenMartModelAccount::getUserOpenTickets()
     * 
     * @return
     */
    public function getUserOpenTickets() {
        $tickets = $this->getUserTickets();
        if (!empty($tickets)) {
            $open_tickets = $this->getOpenTickets($tickets);
            return count($open_tickets);
        }
        return 0;
    }

    /**
     * KsenMartModelAccount::getOpenTickets()
     * 
     * @param mixed $tickets
     * @return
     */
    private function getOpenTickets($tickets) {
        if (!empty($tickets)) {
            $user_tickets = new stdClass;
            for ($i = 0, $c = count($tickets); $i < $c; $i++) {
                if (!isset($tickets[$i]->unread)) {
                    unset($tickets[$i]);
                }
            }
            arsort($tickets);
            return $tickets;
        }
        return false;
    }

    /**
     * KsenMartModelAccount::getUserInfo()
     * 
     * @return
     */
    public function getUserInfo() {
        $user_json = $this->getBillingData('user');
        if ($user_json) {
            return $user_json[0];
        }
        return new StdClass;
    }

    /**
     * KsenMartModelAccount::getUserFullInfo()
     * 
     * @return
     */
    public function getUserFullInfo() {
        $uid = $this->getUserId();
        if ($uid) {
            $user_json = $this->getBillingData('user.edit', array('elid' => $uid));
            if ($user_json) {
                $this->setUserAvatarThumb($user_json);
                $this->setUserAvatarFull($user_json);
                return $user_json;
            }
        }
        return false;
    }

    /**
     * KsenMartModelAccount::getProfileInfo()
     * 
     * @return
     */
    public function getProfileInfo() {
        $pid = $this->getProfileId();
        if ($pid) {
            $profile_json = $this->getBillingData('profile.edit', array('elid' => $pid));
            if ($profile_json) {
                $profile_json = $this->setUserCountry($profile_json);
                return $profile_json;
            }
        }
        return false;
    }

    /**
     * KsenMartModelAccount::setUserAvatarThumb()
     * 
     * @param mixed $user
     * @return
     */
    public function setUserAvatarThumb($user) {
        $avatar_patch = $this->getAvatarThumb($user->id);

        if ($avatar_patch) {
            $html = '<img src="' . $avatar_patch . '" alt="" class="jcrop-preview" />';
        } else {
            $html = '<img src="'.LDM_API_PATCH.'images/avatars/default_avatar.png" alt="" width="80" />';
        }
        $user->avatar = $html;
        return $user;
    }

    /**
     * KsenMartModelAccount::setUserAvatarFull()
     * 
     * @param mixed $user
     * @return
     */
    private function setUserAvatarFull($user) {
        $avatar_patch = $this->getAvatarFull($user->id);

        if ($avatar_patch) {
            $html = '<img src="' . $avatar_patch . '" alt="" class="target" />';
        } else {
            $html = '<img src="'.LDM_API_PATCH.'images/avatars/default_avatar.png" alt="" />';
        }
        $user->avatar_full          = $html;
        $user->avatar_full_patch    = $avatar_patch;
        return $user;
    }

    /**
     * KsenMartModelAccount::setUserCountry()
     * 
     * @param mixed $user
     * @return
     */
    private function setUserCountry($user) {
        $user->country = $this->getCountry($user->ccountry);
        return $user;
    }

    /**
     * KsenMartModelAccount::getUserId()
     * 
     * @return
     */
    public function getUserId() {
        $user_json = $this->getBillingData('user');
        if (isset($user_json[0]->id)) {
            return $user_json[0]->id;
        }
        return false;
    }

    /**
     * KsenMartModelAccount::getProfileId()
     * 
     * @return
     */
    public function getProfileId() {
        $user_json = $this->getBillingData('profile');
        if (isset($user_json[0]->id)) {
            return $user_json[0]->id;
        }
        return false;
    }

    /**
     * KsenMartModelAccount::getProfileUsers()
     * 
     * @return
     */
    public function getProfileUsers() {
        $users_json = $this->getBillingData('profile');
        if (isset($users_json[0]->id)) {
            return $users_json;
        }
        return false;
    }

    /**
     * KsenMartModelAccount::getUserBalance()
     * 
     * @return
     */
    public function getUserBalance() {
        $user_json = $this->getBillingData('accountinfo');
        if ($user_json) {
            return $user_json[0];
        }
        return new StdClass;
    }

    /**
     * KsenMartModelAccount::getUserTickets()
     * 
     * @return
     */
    public function getUserTickets() {
        $tickets_json = $this->getBillingData('clienttickets');

        if ($tickets_json) {
            //$user_tickets = $this->clearSystemsTickets($tickets_json);
            krsort($tickets_json);
            return $tickets_json;
        }
        return false;
    }
    
    public function getDomainContacts() {
        $domaincontacs_json = $this->getBillingData('domaincontact');
        if($domaincontacs_json){
            return $domaincontacs_json;
        }
        return new stdClass;
    }
    
    public function createDomainContact($params) {
        if(!empty($params)){
            $c_contact = $this->getBillingData('contcat.create.1', $params);
            return $c_contact;
        }
        return false;
    }
    
    public function editDomainContact($params) {
        if(!empty($params)){
            $c_contact = $this->getBillingData('domaincontact.edit', $params);
            return $c_contact;
        }
        return false;
    }

    /**
     * KsenMartModelAccount::getArchivedTickets()
     * 
     * @return
     */
    public function getArchivedTickets() {
        $archived_tickets_json = $this->getBillingData('archivedclienttickets');

        if ($archived_tickets_json) {
            krsort($archived_tickets_json);
            return $archived_tickets_json;
        }
        return false;
    }

    /**
     * KsenMartModelAccount::getUserCredits()
     * 
     * @return
     */
    public function getUserCredits() {
        $credits_json = $this->getBillingData('credit');

        if ($credits_json) {
            krsort($credits_json);
            return $this->setCreditStatuses($credits_json);
        }
        return false;
    }

    /**
     * KsenMartModelAccount::setCreditStatuses()
     * 
     * @param mixed $credits
     * @return
     */
    private function setCreditStatuses($credits) {
        foreach ($credits as $credit) {
            switch ($credit->state) {
                case 1:
                    $credit->state = 'Не оплачен';
                    break;
                case 2:
                    $credit->state = 'Оплачивается';
                    break;
                case 4:
                    $credit->state = 'Оплачен';
                    break;
            }
        }
        return $credits;
    }

    /**
     * KsenMartModelAccount::setVHostStatuses()
     * 
     * @param mixed $vhosts
     * @return
     */
    private function setVHostStatuses($vhosts) {
        foreach ($vhosts as $vhost) {
            switch ($vhost->status) {
                case 1:
                    $vhost->status = 'Не оплачен';
                    break;
                case 2:
                    $vhost->status = 'Активен';
                    break;
                case 4:
                    $vhost->status = 'Оплачен';
                    break;
                case 5:
                    $domain->domainstatus = 'Обрабатывается';
                    break;
            }
        }
        return $vhosts;
    }

    /**
     * KsenMartModelAccount::setDomainStatuses()
     * 
     * @param mixed $domains
     * @return
     */
    private function setDomainStatuses($domains) {
        foreach ($domains as $domain) {
            switch ($domain->domainstatus) {
                case 1:
                    $domain->domainstatus = 'Не оплачен';
                    break;
                case 2:
                    $domain->domainstatus = 'Делегирован (Активен)';
                    break;
                case 3:
                    $domain->domainstatus = 'Зарегистрирован (Неделегирован)';
                    break;
                case 4:
                    $domain->domainstatus = 'Удален';
                    break;
                case 5:
                    $domain->domainstatus = 'Обрабатывается (На регистрации)';
                    break;
                case 7:
                    $domain->domainstatus = 'Обрабатывается (На продлении)';
                    break;
            }
        }
        return $domains;
    }

    /**
     * KsenMartModelAccount::getVHost()
     * 
     * @return
     */
    public function getVHost() {
        $vhost_json = $this->getBillingData('vhost');

        if ($vhost_json) {
            krsort($vhost_json);
            return $this->setVHostStatuses($vhost_json);
        }
        return false;
    }

    /**
     * KsenMartModelAccount::getCredit()
     * 
     * @param mixed $id
     * @return
     */
    public function getCredit($id = null) {
        $elid = JRequest::getInt('elid', 1, 'post');
        $credits = $this->getUserCredits();
        $current_credit = array();

        foreach ($credits as $credit) {
            if ($credit->id == $elid) {
                $current_credit = $credit;
                break;
            } else {
                continue;
            }
        }
        return $current_credit;
    }

    /**
     * KsenMartModelAccount::getDomains()
     * 
     * @return
     */
    public function getDomains() {

        $session = &JFactory::getSession();
        $flag = $session->get('sort_flag', false);

        if ($flag) {
            $sort_by = 'ASC';
            $session->set('sort_flag', false);
        } else {
            $sort_by = 'DESC';
            $session = &JFactory::getSession();
            $session->set('sort_flag', true);
        }

        $sort_method = 'sortBy' . $sort_by;
        $domains_json = $this->getBillingData('domain');

        if ($domains_json) {
            if (method_exists($this, $sort_method)) {
                uasort($domains_json, array($this, $sort_method));
            } else {
                JRequest::setVar('sort_by', 'id');
                uasort($domains_json, array($this, 'sortByASC'));
            }
            return $this->setDomainStatuses($domains_json);
        }
        return false;
    }

    /**
     * KsenMartModelAccount::sortByASC()
     * 
     * @param mixed $f1
     * @param mixed $f2
     * @return
     */
    private function sortByASC($f1, $f2) {
        $tmp_sort = JRequest::getVar('order_field', null);
        if (isset($tmp_sort['by']) && !empty($tmp_sort['by'])) {
            $sort_by = $tmp_sort['by'];
        } else {
            $sort_by = 'id';
        }

        if (is_numeric($f1->$sort_by) && is_numeric($f2->$sort_by)) {
            if ($f1->$sort_by < $f2->$sort_by) return - 1;
            elseif ($f1->$sort_by > $f2->$sort_by) return 1;
            else  return 0;
        } else {
            return strcmp($f1->$sort_by, $f2->$sort_by);
        }
    }

    /**
     * KsenMartModelAccount::sortByDESC()
     * 
     * @param mixed $f1
     * @param mixed $f2
     * @return
     */
    private function sortByDESC($f1, $f2) {
        $tmp_sort = JRequest::getVar('order_field', null);
        if (isset($tmp_sort['by']) && !empty($tmp_sort['by'])) {
            $sort_by = $tmp_sort['by'];
        } else {
            $sort_by = 'id';
        }

        if (is_numeric($f1->$sort_by) && is_numeric($f2->$sort_by)) {
            if ($f1->$sort_by > $f2->$sort_by) return - 1;
            elseif ($f1->$sort_by < $f2->$sort_by) return 1;
            else  return 0;
        } else {
            if ($f1->$sort_by == $f2->$sort_by) return 0;
            elseif ($f1->$sort_by > $f2->$sort_by) return - 1;
            else  return 1;
        }
    }

    /**
     * KsenMartModelAccount::getDomainRerunS1()
     * 
     * @return
     */
    public function getDomainRerunS1() {
        $elid = JRequest::getInt('elid', 1, 'post');

        $params = array('elid' => $elid);

        $domain_rerun = $this->getBillingData('domain.rerun', $params);
        return $domain_rerun;
    }

    /**
     * KsenMartModelAccount::getDomainRenewS1()
     * 
     * @return
     */
    public function getDomainRenewS1() {
        $elid = JRequest::getInt('elid', 1, 'post');

        $params = array('elid' => $elid);

        $domain_rerun = $this->getBillingData('domain.renew', $params);
        return $domain_rerun;
    }

    /**
     * KsenMartModelAccount::setDomainRenew()
     * 
     * @return
     */
    public function setDomainRenew() {
        $domain_info = $this->getDomainEdit();
        //$domain_name_parse = $this->getDomainZone($domain_info->name);
        $elid = JRequest::getInt('elid', 1, 'post');

        $params = array(
            'sok' => 'ok',
            'set_autoperiod' => 'on',
            'autoperiod' => $domain_info->autoperiod,
            'elid' => $elid);

        $domain_renew = $this->getBillingData('domain.renew', $params);
        return $domain_renew;
    }

    /**
     * KsenMartModelAccount::getDomainEdit()
     * 
     * @param bool $elid
     * @return
     */
    public function getDomainEdit($elid = false) {
        if ($elid) {

        }
        $elid = JRequest::getInt('elid', 1, 'post');

        $params = array('elid' => $elid);

        $domain_edit = $this->getBillingData('domain.edit', $params);
        return $domain_edit;
    }

    /**
     * KsenMartModelAccount::domainEdit()
     * 
     * @return
     */
    public function domainEdit() {
        $elid = JRequest::getInt('elid', 1, 'post');
        $ns0  = JRequest::getVar('ns0', null);
        $ns1  = JRequest::getVar('ns1', null);
        $ns2  = JRequest::getVar('ns2', null);
        $ns3  = JRequest::getVar('ns3', null);
        $autoperiod = JRequest::getVar('autoperiod', null);

        $params = array(
            'sok' => 'ok',
            'elid' => $elid,
            'ns0' => $ns0,
            'ns1' => $ns1,
            'ns2' => $ns2,
            'ns3' => $ns3,
            'autoperiod' => $autoperiod);

        $domain_rerun = $this->getBillingData('domain.edit', $params);
        return $domain_rerun;
    }

    /**
     * KsenMartModelAccount::domainRerunS2()
     * 
     * @return
     */
    public function domainRerunS2() {
        $elid = JRequest::getInt('elid', 1, 'post');
        $cost = JRequest::getVar('cost', null);

        $params = array(
            'sok' => 'ok',
            'elid' => $elid,
            'cost' => $cost,
            'promocode' => '');

        $domain_rerun = $this->getBillingData('domain.rerun', $params);
        return $domain_rerun;
    }

    /**
     * KsenMartModelAccount::registerDomains()
     * 
     * @return
     */
    public function registerDomains() {
        $reg_data = JRequest::getVar('reg_data', null);

        $params = array('sok' => 'yes');

        $params = array_merge($params, $reg_data);

        $c_ticket = $this->getBillingData('domain.order.4', $params);
        return $c_ticket;
    }

    /**
     * KsenMartModelAccount::registerDomainsData()
     * 
     * @return
     */
    public function registerDomainsData() {
        $domain_names = JRequest::getVar('selected_domains', null);
        $promocode = JRequest::getVar('promocode', null);
        $customer = JRequest::getVar('customer', null);

        $params = array(
            'countdomain' => count($domain_names),
            'promocode' => $promocode,
            'customer' => $customer,
            'subjnic' => $customer,
            'operation' => 'register',
            'promocode' => '',
            'projectns' => 'ns1.optimall.ru ns2.optimall.ru');

        $params = array_merge($params, $this->setDomains2Reg($domain_names));

        $c_ticket = $this->getBillingData('domain.order.4', $params);
        return $c_ticket;
    }

    /**
     * KsenMartModelAccount::setDomains2Reg()
     * 
     * @param mixed $domain_names
     * @return
     */
    private function setDomains2Reg($domain_names) {
        if (!empty($domain_names)) {
            $params = array();
            $params['domain'] = null;
            foreach ($domain_names as $key => $domain_name) {
                $expl_domain = $this->getDomainZone($domain_name);
                $params['domain'] .= ' ' . $expl_domain['domain'];
                $params['domainname_' . $key] = $expl_domain['domain'];
                $params['nslist_' . $key] = 'ns1.optimall.ru ns2.optimall.ru';
                $params['pricelist_' . $key] = $this->getDomainsZoneId($expl_domain['zone']);
                $params['period_' . $key] = $this->getDomainsPeriodId($expl_domain['zone']);
            }
            $params['domain'] = trim($params['domain']);
            return $params;
        }
        return false;
    }

    /**
     * KsenMartModelAccount::getDomainsZoneId()
     * 
     * @param mixed $zone
     * @return
     */
    private function getDomainsZoneId($zone) {
        if (!empty($zone)) {
            $zones = array(
                'net.ru',
                'com',
                'com.ru',
                'info',
                'me',
                'msk.ru',
                'net',
                'ru',
                'su',
                'рф',
                'xxx');
            $zones_code = array(
                180,
                32,
                51,
                53,
                54,
                50,
                38,
                28,
                36,
                30,
                52);

            return str_replace($zones, $zones_code, $zone);
        }
        return false;
    }

    /**
     * KsenMartModelAccount::getDomainsPeriodId()
     * 
     * @param mixed $zone
     * @return
     */
    public function getDomainsPeriodId($zone) {
        if (!empty($zone)) {
            $zones = array(
                'net.ru',
                'com',
                'com.ru',
                'info',
                'me',
                'msk.ru',
                'net',
                'ru',
                'su',
                'рф',
                'xxx');
            $zones_code = array(
                194,
                37,
                77,
                82,
                86,
                73,
                61,
                21,
                53,
                29,
                81);

            return str_replace($zones, $zones_code, $zone);
        }
        return false;
    }

    /**
     * KsenMartModelAccount::checkDomains()
     * 
     * @return
     */
    public function checkDomains() {
        $domain_name = JRequest::getVar('domain_name', null);
        $domains_zone = JRequest::getVar('domains_zone', null);

        //$domains_zone    = $this->getClearDomainZone($domain_name, $domains_zone);
        $domains_variant = $this->createJSONVaruantDomains($domain_name, $domains_zone);

        $params = array(
            'domain_name' => $domain_name,
            'input_data' => $domains_variant,
            'input_format' => 'json'
        );

        $c_credit = json_decode(KSSystem::getLdmApiData('domains', 'check', $params));
        return $this->returnReSortDomains($c_credit, $domain_name);
    }

    /**
     * KsenMartModelAccount::returnReSortDomains()
     * 
     * @param mixed $domains
     * @param mixed $domain_name
     * @return
     */
    private function returnReSortDomains($domains, $domain_name) {
        if (!empty($domains->answer->domains)) {
            $domain_tmp = array();
            for ($i = 0, $c = count($domains->answer->domains); $i < $c; $i++) {
                if ($domain_name == $domains->answer->domains[$i]->dname) {
                    $domain_tmp = $domains->answer->domains[$i];
                    unset($domains->answer->domains[$i]);
                    break;
                }
            }
            //print_r($domain_tmp);
            array_unshift($domains->answer->domains, $domain_tmp);
            //print_r($domains);
            return $domains;
        }
        return false;
    }

    /**
     * KsenMartModelAccount::createJSONVaruantDomains()
     * 
     * @param mixed $domain_name
     * @param mixed $domains_zone
     * @return
     */
    private function createJSONVaruantDomains($domain_name, $domains_zone) {
        $domain = $this->getDomainZone($domain_name);

        $variants = new stdClass();
        $variants->domains = array();

        $i = 0;
        foreach ($domains_zone as $domain_zone) {
            $variants->domains[$i] = new stdClass();
            if ($domain_zone != 'рф') {
                $variants->domains[$i]->dname = $this->translitIt($domain['domain']) . '.' . $domain_zone;
            } else {
                $variants->domains[$i]->dname = $this->translitIt($domain['domain'], true) . '.' .
                    $domain_zone;
            }
            $i++;
        }

        return json_encode($variants);
    }

    /**
     * KsenMartModelAccount::getClearDomainZone()
     * 
     * @param mixed $domain_name
     * @param mixed $domains_zone
     * @return
     */
    private function getClearDomainZone($domain_name, $domains_zone) {

        $zone = $this->getDomainZone($domain_name);

        for ($i = 0, $c = count($domains_zone); $i < $c; $i++) {
            if ($domains_zone[0] != $zone['zone']) {
                continue;
            } else {
                unset($domains_zone[0]);
                break;
            }
        }
        sort($domains_zone);
        return $domains_zone;
    }

    /**
     * KsenMartModelAccount::getDomainZone()
     * 
     * @param mixed $domain
     * @return
     */
    private function getDomainZone($domain) {
        if (!empty($domain)) {
            $domain = explode('.', $domain, 2);
            if (isset($domain[1]) && !empty($domain[1])) {
                return array('domain' => $domain[0], 'zone' => $domain[1]);
            }
        }
        return false;
    }

    /**
     * KsenMartModelAccount::translitIt()
     * 
     * @param mixed $text
     * @param bool $reverse
     * @return
     */
    public function translitIt($text, $reverse = false) {
        $rus = array(
            'а',
            'б',
            'в',
            'г',
            'д',
            'е',
            'ё',
            'ж',
            'з',
            'и',
            'й',
            'к',
            'л',
            'м',
            'н',
            'о',
            'п',
            'р',
            'с',
            'т',
            'у',
            'ф',
            'х',
            'ц',
            'ч',
            'ш',
            'щ',
            'ъ',
            'ы',
            'ь',
            'э',
            'ю',
            'я',
            ' ');
        $lat = array(
            'a',
            'b',
            'v',
            'g',
            'd',
            'e',
            'e',
            'gh',
            'z',
            'i',
            'y',
            'k',
            'l',
            'm',
            'n',
            'o',
            'p',
            'r',
            's',
            't',
            'u',
            'f',
            'h',
            'c',
            'ch',
            'sh',
            'sch',
            'y',
            'y',
            'y',
            'e',
            'yu',
            'ya',
            'a',
            'b',
            'v',
            'g',
            'd',
            'e',
            'e',
            'gh',
            'z',
            'i',
            'y',
            'k',
            'l',
            'm',
            'n',
            'o',
            'p',
            'r',
            's',
            't',
            'u',
            'f',
            'h',
            'c',
            'ch',
            'sh',
            'sch',
            'y',
            'y',
            'y',
            'e',
            'yu',
            'ya',
            ' ');

        if ($reverse) {
            return str_replace($lat, $rus, $text);
        } else {
            return str_replace($rus, $lat, $text);
        }
    }

    /**
     * KsenMartModelAccount::getUserTicket()
     * 
     * @return
     */
    public function getUserTicket() {
        $elid = JRequest::getInt('elid', 1, 'post');
        $ticket_json = $this->getBillingData('clienttickets.edit', array('elid' => $elid));

        if ($ticket_json) {
            return $ticket_json;
        }
        return new StdClass;
    }

    /**
     * KsenMartModelAccount::getServices()
     * 
     * @return
     */
    public function getServices() {
        $item_json = $this->getBillingData('item');

        if ($item_json) {
            return $item_json;
        }
        return new StdClass;
    }

    /**
     * KsenMartModelAccount::setUserAnswer()
     * 
     * @return
     */
    public function setUserAnswer() {
        $elid = JRequest::getInt('elid', 1, 'post');
        $text = JRequest::getVar('text', null);
        $answer = $this->getBillingData('clienttickets.edit', array(
            'sok' => 'yes',
            'elid' => $elid,
            'text' => $text));
        return $answer;
    }

    /**
     * KsenMartModelAccount::createTicket()
     * 
     * @return
     */
    public function createTicket() {
        $category = JRequest::getInt('category', 1, 'post');
        $product = JRequest::getInt('product', 1, 'post');
        $subject = JRequest::getVar('subject', null);
        $text = JRequest::getVar('text', null);

        $params = array(
            'sok' => 'yes',
            'category' => $category,
            'product' => $product,
            'subject' => $subject,
            'text' => $text);

        $c_ticket = $this->getBillingData('clienttickets.edit', $params);
        return $c_ticket;
    }

    /**
     * KsenMartModelAccount::createCredit()
     * 
     * @return
     */
    public function createCredit() {
        $sender = JRequest::getInt('sender', 1, 'post');
        $type = JRequest::getInt('type', 1, 'post');
        $amount = JRequest::getVar('amount', null);
        $paycurrency = JRequest::getVar('paycurrency', null);

        $params = array(
            'sok' => 'yes',
            'sender' => $sender,
            'type' => $type,
            'amount' => $amount,
            'paycurrency' => $paycurrency);

        $c_credit = $this->getBillingData('credit.add.4', $params);
        return $c_credit;
    }

    /**
     * KsenMartModelAccount::createCreditQiwi()
     * 
     * @return
     */
    public function createCreditQiwi() {
        $phoneid    = JRequest::getVar('phoneid', null);
        $amount     = JRequest::getVar('amount', null);
        $elid       = JRequest::getInt('elid', 1, 'post');
        $alertsms   = JRequest::getVar('alertsms', null);

        $params = array(
            'sok' => 'ok',
            'phoneid' => $phoneid,
            'elid' => $elid,
            'alertsms' => $alertsms,
            'amount' => $amount
        );

        $c_credit = $this->getBillingData('credit.pay.iqiwi', $params);
        return $c_credit;
    }

    /**
     * KsenMartModelAccount::existsAvatarLdmServer()
     * 
     * @param mixed $img
     * @return
     */
    private function existsAvatarLdmServer($img) {
        $url = LDM_API_PATCH.'images/avatars/' . $img;
        $headers = @get_headers($url);

        if (preg_match("|200|", $headers[0])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * KsenMartModelAccount::getAvatarThumb()
     * 
     * @param mixed $elid
     * @param bool $view
     * @return
     */
    public function getAvatarThumb($elid, $view = false) {

        //$abs_patch_img = ACCOUNT_ROOT_MODULE_PATCH . $elid;
        $patch_img = LDM_API_PATCH . 'images/avatars/'. $elid;
        if ($this->existsAvatarLdmServer($elid.'.jpg')) {
            $patch_img = $patch_img . '.jpg';
        } elseif ($this->existsAvatarLdmServer($elid.'.png')) {
            $patch_img = $patch_img . '.png';
        } elseif ($this->existsAvatarLdmServer($elid.'.gif')) {
            $patch_img = $patch_img . '.gif';
        } else {
            return false;
        }
        if ($view) {
            echo '<img alt="" src="' . $patch_img . '" />';
            return true;
        }

        return $patch_img;
    }

    /**
     * KsenMartModelAccount::getAvatarFull()
     * 
     * @param mixed $elid
     * @param bool $view
     * @return
     */
    public function getAvatarFull($elid, $view = false) {

        //$abs_patch_img = ACCOUNT_ROOT_MODULE_PATCH . $elid;
        $patch_img = LDM_API_PATCH . 'images/avatars/full/'.$elid;
        if ($this->existsAvatarLdmServer($elid.'.jpg')) {
            $patch_img = $patch_img . '.jpg';
        } elseif ($this->existsAvatarLdmServer($elid.'.png')) {
            $patch_img = $patch_img . '.png';
        } elseif ($this->existsAvatarLdmServer($elid.'.gif')) {
            $patch_img = $patch_img . '.gif';
        } else {
            return false;
        }
        if ($view) {
            echo '<img alt="" src="' . $patch_img . '" />';
            return true;
        }

        return $patch_img;
    }

    /**
     * KsenMartModelAccount::moveTmpImg()
     * 
     * @param mixed $name
     * @param bool $resize
     * @return
     */
    public function moveTmpImg($name, $resize = false) {
        
        $elid = JRequest::getInt('elid', 0, 'post');
        $x1   = JRequest::getInt('x1', 0, 'post');
        $y1   = JRequest::getInt('y1', 0, 'post');
        $w    = JRequest::getInt('w', 0, 'post');
        $h    = JRequest::getInt('h', 0, 'post');

        $boundx   = JRequest::getInt('boundx', 0, 'post');
        $boundy   = JRequest::getInt('boundy', 0, 'post');

        if($resize){
            foreach ($_FILES as $key => $value) {
                
                $size = getimagesize($value['tmp_name']);

                if($value["size"] > 1024*8*1024){
                    return false;
                }

                if(empty($name)){
                    $value['name'] = $this->generateRandomString(15);
                }else{
                    $value['name'] = $name;
                }
                switch ($value['type']) {
                    case 'image/jpeg':
                        $value['name'] .= '.jpg';
                    break;
                    case 'image/png':
                        $value['name'] .= '.png';
                    break;
                    case 'image/gif':
                        $value['name'] .= '.gif';
                    break;
                    default:
                        $value['name'] .= '.jpg';
                    break;
                }

                if(!is_uploaded_file($value['tmp_name'])){
                    return false;
                }

                $this->resizeImage($value['tmp_name'], 0, 0, $w, $h, $boundx, $boundy, true);
                $this->loadAvatar2FTP($value['name'], $value['tmp_name'], 'full');

                $this->resizeImage($value['tmp_name'], $x1, $y1, $w, $h, $boundx, $boundy);
                $this->loadAvatar2FTP($value['name'], $value['tmp_name'], '.');
                return true;
            }
        }else{
            $avatar_full   = JRequest::getVar('avatar_full', null);
            if(!empty($avatar_full)){
                $patch_info = pathinfo($avatar_full);
                $this->resizeAvatarOnLDM($patch_info['basename'], $x1, $y1, $w, $h, $boundx, $boundy);
                return true;
            }
        }

        return false;
    }

    /**
     * KsenMartModelAccount::resizeAvatarOnLDM()
     * 
     * @param mixed $tmp_name
     * @param mixed $x1
     * @param mixed $y1
     * @param mixed $w
     * @param mixed $h
     * @param mixed $boundx
     * @param mixed $boundy
     * @return
     */
    private function resizeAvatarOnLDM($tmp_name, $x1, $y1, $w, $h, $boundx, $boundy){

        $params = array(
            'name'  => $tmp_name,
            'x'         => $x1,
            'y'         => $y1,
            'w'         => $w,
            'h'         => $h,
            'boundx'    => $boundx,
            'boundy'    => $boundy
        );

        $response = json_decode(KSSystem::getLdmApiData('avatars', null, $params));
        return $response;
    }

    /**
     * KsenMartModelAccount::loadAvatar2FTP()
     * 
     * @param mixed $name
     * @param mixed $tmp_name
     * @param mixed $dir
     * @return
     */
    private function loadAvatar2FTP($name, $tmp_name, $dir){
        $connect = $this->FTPOpen();
        if($connect){
            if($this->login2FTP($connect)){
                if($this->loadFile2FTP($connect, $name, $tmp_name, $dir)){
                    $this->FTPClose($connect);
                    return true;
                }
            }
        }
    }
    
    /**
     * KsenMartModelAccount::FTPOpen()
     * 
     * @return
     */
    private function FTPOpen(){
        $host    = '89.108.111.59';
        $connect = ftp_connect($host);
        if($connect){
            return $connect;
        }else{
            return false;
        }
    }
    
    /**
     * KsenMartModelAccount::login2FTP()
     * 
     * @param mixed $connect
     * @return
     */
    private function login2FTP($connect){
        if(!empty($connect)){
            $user       = 'avatar_load';
            $password   = 's5MHoMCh';
            return ftp_login($connect, $user, $password);
        }
        return false;
    }
    
    /**
     * KsenMartModelAccount::FTPClose()
     * 
     * @param mixed $connect
     * @return
     */
    private function FTPClose($connect){
        if(!empty($connect)){
            return ftp_quit($connect);
        }
        return false;
    }
    
    /**
     * KsenMartModelAccount::loadFile2FTP()
     * 
     * @param mixed $connect
     * @param mixed $remote_file
     * @param mixed $file
     * @param mixed $dir
     * @return
     */
    private function loadFile2FTP($connect, $remote_file, $file, $dir){
        if(!empty($connect)){
            if(ftp_chdir($connect, $dir)){
                return ftp_put($connect, $remote_file, $file, FTP_BINARY);
            }
        }
        return false;
    }

    /**
     * KsenMartModelAccount::loadImages()
     * 
     * @return
     */
    public function loadImages() {
        $elid   = JRequest::getInt('elid', 0, 'post');
        $resize = (bool)JRequest::getVar('resize', false);
        $flag   = (bool)JRequest::getVar('flag', false);

        if(!empty($elid)){
            if($flag){
                $this->moveTmpImg($elid);
            }else{
                $this->moveTmpImg($elid, true);
            }
            return true;
        }
        return false;
    }

    /**
     * KsenMartModelAccount::resizeImage()
     * 
     * @param mixed $tmp_name
     * @param mixed $x1
     * @param mixed $y1
     * @param mixed $w
     * @param mixed $h
     * @param mixed $boundx
     * @param mixed $boundy
     * @param bool $resize
     * @return
     */
    private function resizeImage($tmp_name, $x1, $y1, $w, $h, $boundx, $boundy, $resize = false){

        $iWidth = $iHeight = 80; // desired image result dimensions
        $iJpgQuality = 90;

                    
        if(file_exists($tmp_name) && filesize($tmp_name) > 0) {
            $aSize = getimagesize($tmp_name); // try to obtain image info

            if (!$aSize) {
                return;
            }

            // check for image type
            switch($aSize[2]) {
                case IMAGETYPE_JPEG:
                    $sExt = '.jpg';
                    $vImg = imagecreatefromjpeg($tmp_name);
                    break;
                case IMAGETYPE_PNG:
                    $sExt = '.png';
                    $vImg = imagecreatefrompng($tmp_name);
                    break;
                default:
                return;
            }

            if(!$resize){

                $vDstImg = imagecreatetruecolor($iWidth, $iHeight);

                $proX = $aSize[0]/$boundx;
                $proY = $aSize[1]/$boundy;

                if($x1+$w >= $aSize[0]){
                    imagecopyresampled($vDstImg, $vImg, 0, 0, $x1, $y1, $iWidth, $iHeight, $w, $h);
                }else{
                    imagecopyresampled($vDstImg, $vImg, 0, 0, $x1*$proX, $y1*$proY, $iWidth, $iHeight, $w*$proX, $h*$proY);
                }
            }else{
                $koe    = $aSize[0]/1000;
                $new_h  = ceil ($aSize[1]/$koe);

                $vDstImg = imagecreatetruecolor(1000, $new_h);

                imagecopyresampled($vDstImg, $vImg, 0, 0, 0, 0, 1000, $new_h, $aSize[0], $aSize[1]);
            }

            // define a result image filename
            $sResultFileName = $tmp_name;

            // output image to file
            imagejpeg($vDstImg, $sResultFileName, $iJpgQuality);

            return true;
        }
    }

    /**
     * KsenMartModelAccount::saveSettings()
     * 
     * @return
     */
    public function saveSettings() {

        $elid = JRequest::getInt('elid', 1, 'post');
        $email = JRequest::getVar('email', null);
        $name = JRequest::getVar('name', null);
        $realname = JRequest::getVar('realname', null);
        $superuser = JRequest::getVar('superuser', null);
        $useavatar = JRequest::getVar('useavatar', null);

        $params = array(
            'sok'       => 'yes',
            'email'     => $email,
            'name'      => $name,
            'realname'  => $realname,
            'superuser' => $superuser,
            'useavatar' => $useavatar,
            'elid'      => $elid
        );

        $c_credit = $this->getBillingData('user.edit', $params);
        return $c_credit;
    }

    /**
     * KsenMartModelAccount::createVHost()
     * 
     * @return
     */
    public function createVHost() {
        $price      = JRequest::getInt('price', 1, 'post');
        $period     = JRequest::getInt('period', 1, 'post');
        $promocode  = JRequest::getVar('promocode', null);
        $domain     = JRequest::getVar('domain', null);
        $addon_11    = JRequest::getInt('addon_11', 3000, 'post');
        $addon_13    = JRequest::getInt('addon_13', 0, 'post');
        $addon_14    = JRequest::getInt('addon_14', 2, 'post');
        $addon_15    = JRequest::getInt('addon_15', 2, 'post');
        $addon_16    = JRequest::getInt('addon_16', 1000, 'post');
        $addon_17    = JRequest::getInt('addon_17', 100, 'post');
        $autoprolong = JRequest::getInt('autoprolong', 5, 'post');
        $payfrom     = JRequest::getVar('account1', null);

        $params = array(
            'sok' => 'yes',
            'price' => $price,
            'period' => $period,
            'promocode' => $promocode,
            'domain' => $domain,
            'addon_11' => $addon_11,
            'addon_13' => $addon_13,
            'addon_14' => $addon_14,
            'addon_15' => $addon_15,
            'addon_16' => $addon_16,
            'addon_17' => $addon_17,
            'autoprolong' => $autoprolong,
            'payfrom' => $payfrom
        );

        $c_credit = $this->getBillingData('vhost.order.5', $params);
        return $c_credit;
    }

    /**
     * KsenMartModelAccount::moveToArchive()
     * 
     * @param mixed $ids
     * @return
     */
    public function moveToArchive($ids) {
        $params = array('elid' => $ids);

        $open_ticket = $this->getBillingData('archivedclienttickets.open', $params);
        return $open_ticket;
    }

    /**
     * KsenMartModelAccount::moveFromArchive()
     * 
     * @param mixed $ids
     * @return
     */
    public function moveFromArchive($ids) {
        $params = array('elid' => $ids);

        $open_ticket = $this->getBillingData('clienttickets.archive', $params);
        return $open_ticket;
    }

    /**
     * KsenMartModelAccount::removePayments()
     * 
     * @param mixed $ids
     * @return
     */
    public function removePayments($ids) {
        $params = array('elid' => $ids);

        $open_ticket = $this->getBillingData('credit.delete', $params);
        return $open_ticket;
    }

    /**
     * KsenMartModelAccount::removeVhosts()
     * 
     * @param mixed $ids
     * @return
     */
    public function removeVhosts($ids) {
        $params = array('elid' => $ids);

        $open_ticket = $this->getBillingData('vhost.delete', $params);
        return $open_ticket;
    }

    /**
     * KsenMartModelAccount::getCountry()
     * 
     * @param mixed $id
     * @return
     */
    public function getCountry($id) {
        $countries = $this->getCountries();
        if (isset($countries[$id])) {
            return $countries[$id];
        }
        return false;
    }

    /**
     * KsenMartModelAccount::getCountries()
     * 
     * @return
     */
    public function getCountries() {
        $countries = array(
            1 => 'Afghanistan',
            2 => 'Aland Islands',
            3 => 'Albania',
            4 => 'Algeria',
            5 => 'American Samoa',
            6 => 'Andorra',
            7 => 'Angola',
            8 => 'Anguilla',
            9 => 'Antarctica',
            10 => 'Antigua and Barbuda',
            11 => 'Argentina',
            12 => 'Armenia',
            13 => 'Aruba',
            14 => 'Australia',
            15 => 'Austria',
            16 => 'Azerbaijan',
            17 => 'Bahamas',
            18 => 'Bahrain',
            19 => 'Bangladesh',
            20 => 'Barbados',
            21 => 'Belarus',
            22 => 'Belgium',
            23 => 'Belize',
            24 => 'Benin',
            25 => 'Bermuda',
            26 => 'Bhutan',
            27 => 'Bolivia',
            28 => 'Bosnia and Herzegovina',
            29 => 'Botswana',
            30 => 'Bouvet Island',
            31 => 'Brazil',
            32 => 'British Indian Ocean Territory',
            33 => 'Brunei Darussalam',
            34 => 'Bulgaria',
            35 => 'Burkina Faso',
            36 => 'Burundi',
            37 => 'Cambodia',
            38 => 'Cameroon',
            39 => 'Canada',
            40 => 'Cape Verde',
            41 => 'Cayman Islands',
            42 => 'Central African Republic',
            43 => 'Chad',
            44 => 'Chile',
            45 => 'China',
            46 => 'Christmas Island',
            47 => 'Cocos (keeling) Islands',
            48 => 'Colombia',
            49 => 'Comoros',
            50 => 'Congo',
            51 => 'Congo, The Democratic Republic of The',
            52 => 'Cook Islands',
            53 => 'Costa Rica',
            54 => 'Cote Divoire',
            55 => 'Croatia',
            56 => 'Cuba',
            57 => 'Cyprus',
            58 => 'Czech Republic',
            59 => 'Denmark',
            60 => 'Djibouti',
            61 => 'Dominica',
            62 => 'Dominican Republic',
            63 => 'Ecuador',
            64 => 'Egypt',
            65 => 'El Salvador',
            66 => 'Equatorial Guinea',
            67 => 'Eritrea',
            68 => 'Estonia',
            69 => 'Ethiopia',
            70 => 'Falkland Islands (malvinas)',
            71 => 'Faroe Islands',
            72 => 'Fiji',
            73 => 'Finland',
            74 => 'France',
            75 => 'French Guiana',
            76 => 'French Polynesia',
            77 => 'French Southern Territories',
            78 => 'Gabon',
            79 => 'Gambia',
            80 => 'Georgia',
            81 => 'Germany',
            82 => 'Ghana',
            83 => 'Gibraltar',
            84 => 'Greece',
            85 => 'Greenland',
            86 => 'Grenada',
            87 => 'Guadeloupe',
            88 => 'Guam',
            89 => 'Guatemala',
            90 => 'Guernsey',
            91 => 'Guinea',
            92 => 'Guinea-bissau',
            93 => 'Guyana',
            94 => 'Haiti',
            95 => 'Heard Island and Mcdonald Islands',
            96 => 'Holy See (vatican City State)',
            97 => 'Honduras',
            98 => 'Hong Kong',
            99 => 'Hungary',
            100 => 'Iceland',
            101 => 'India',
            102 => 'Indonesia',
            103 => 'Iran, Islamic Republic of',
            104 => 'Iraq',
            105 => 'Ireland',
            106 => 'Isle of Man',
            107 => 'Israel',
            108 => 'Italy',
            109 => 'Jamaica',
            110 => 'Japan',
            111 => 'Jersey',
            112 => 'Jordan',
            113 => 'Kazakhstan',
            114 => 'Kenya',
            115 => 'Kiribati',
            116 => 'Korea, Democratic People\'s Republic of',
            117 => 'Korea, Republic of',
            118 => 'Kuwait',
            119 => 'Kyrgyzstan',
            120 => 'Lao People\'s Democratic Republic',
            121 => 'Latvia',
            122 => 'Lebanon',
            123 => 'Lesotho',
            124 => 'Liberia',
            125 => 'Libyan Arab Jamahiriya',
            126 => 'Liechtenstein',
            127 => 'Lithuania',
            128 => 'Luxembourg',
            129 => 'Macao',
            130 => 'Macedonia, The Former Yugoslav Republic of',
            131 => 'Madagascar',
            132 => 'Malawi',
            133 => 'Malaysia',
            134 => 'Maldives',
            135 => 'Mali',
            136 => 'Malta',
            137 => 'Marshall Islands',
            138 => 'Martinique',
            139 => 'Mauritania',
            140 => 'Mauritius',
            141 => 'Mayotte',
            142 => 'Mexico',
            143 => 'Micronesia, Federated States of',
            144 => 'Moldova, Republic of',
            145 => 'Monaco',
            146 => 'Mongolia',
            147 => 'Montenegro',
            148 => 'Montserrat',
            149 => 'Morocco',
            150 => 'Mozambique',
            151 => 'Myanmar',
            152 => 'Namibia',
            153 => 'Nauru',
            154 => 'Nepal',
            155 => 'Netherlands',
            156 => 'Netherlands Antilles',
            157 => 'New Caledonia',
            158 => 'New Zealand',
            159 => 'Nicaragua',
            160 => 'Niger',
            161 => 'Nigeria',
            162 => 'Niue',
            163 => 'Norfolk Island',
            164 => 'Northern Mariana Islands',
            165 => 'Norway',
            166 => 'Oman',
            167 => 'Pakistan',
            168 => 'Palau',
            169 => 'Palestinian Territory, Occupied',
            170 => 'Panama',
            171 => 'Papua New Guinea',
            172 => 'Paraguay',
            173 => 'Peru',
            174 => 'Philippines',
            175 => 'Pitcairn',
            176 => 'Poland',
            177 => 'Portugal',
            178 => 'Puerto Rico',
            179 => 'Qatar',
            180 => 'Reunion',
            181 => 'Romania',
            182 => 'Российская Федерация',
            183 => 'Rwanda',
            184 => 'Saint Barthelemy',
            185 => 'Saint Helena',
            186 => 'Saint Kitts and Nevis',
            187 => 'Saint Lucia',
            188 => 'Saint Martin',
            189 => 'Saint Pierre and Miquelon',
            190 => 'Saint Vincent and The Grenadines',
            191 => 'Samoa',
            192 => 'San Marino',
            193 => 'Sao Tome and Principe',
            194 => 'Saudi Arabia',
            195 => 'Senegal',
            196 => 'Serbia',
            197 => 'Seychelles',
            198 => 'Sierra Leone',
            199 => 'Singapore',
            200 => 'Slovakia',
            201 => 'Slovenia',
            202 => 'Solomon Islands',
            203 => 'Somalia',
            204 => 'South Africa',
            205 => 'South Georgia and The South Sandwich Islands',
            206 => 'Spain',
            207 => 'Sri Lanka',
            208 => 'Sudan',
            209 => 'Suriname',
            210 => 'Svalbard and Jan Mayen',
            211 => 'Swaziland',
            212 => 'Sweden',
            213 => 'Switzerland',
            214 => 'Syrian Arab Republic',
            215 => 'Taiwan, Province of China',
            216 => 'Tajikistan',
            217 => 'Tanzania, United Republic of',
            218 => 'Thailand',
            219 => 'Timor-leste',
            220 => 'Togo',
            221 => 'Tokelau',
            222 => 'Tonga',
            223 => 'Trinidad and Tobago',
            224 => 'Tunisia',
            225 => 'Turkey',
            226 => 'Turkmenistan',
            227 => 'Turks and Caicos Islands',
            228 => 'Tuvalu',
            229 => 'Uganda',
            230 => 'Ukraine',
            231 => 'United Arab Emirates',
            232 => 'United Kingdom',
            233 => 'United States',
            234 => 'United States Minor Outlying Islands',
            235 => 'Uruguay',
            236 => 'Uzbekistan',
            237 => 'Vanuatu',
            238 => 'Venezuela',
            239 => 'Viet Nam',
            240 => 'Virgin Islands, British',
            241 => 'Virgin Islands, U.s.',
            242 => 'Wallis and Futuna',
            243 => 'Western Sahara',
            244 => 'Yemen',
            245 => 'Zambia',
            246 => 'Zimbabwe');

        return $countries;
    }

    /**
     * KsenMartModelAccount::clearSystemsTickets()
     * 
     * @param mixed $tickets
     * @return
     */
    private function clearSystemsTickets($tickets) {
        if (!empty($tickets)) {
            $user_tickets = new stdClass;
            for ($i = 0, $c = count($tickets); $i < $c; $i++) {
                if ($tickets[$i]->category == 'on') {
                    unset($tickets[$i]);
                }
            }
            arsort($tickets);
            return $tickets;
        }
        return false;
    }

    /**
     * KsenMartModelAccount::checkIssetAuth()
     * 
     * @return
     */
    public function checkIssetAuth() {
        $dom = new domDocument("1.0", "utf-8");
        $dom->load("https://ldmco.ru/manager/billmgr?out=xml&auth=" . $this->_auth .
            "&func=user");
        $root = $dom->documentElement;
        $childs = $root->childNodes;

        $user = $childs->item(0);
        $lp = $user->childNodes;
        if ($user->tagName == 'error') {
            return false;
        }
        return true;
    }
    
    /**
     * KsenMartModelAccount::getBillingData()
     * 
     * @param mixed $func
     * @param mixed $params
     * @return
     */
    private function getBillingData($func, $params = array()) {
        if (!empty($func) && !empty($this->_auth)) {
            $link = 'https://ldmco.ru/manager/billmgr?out=json&auth=' . $this->_auth .
                '&func=' . $func . '&lang=ru';

            if (!empty($params)) {
                foreach ($params as $key => $param) {
                    $param = str_replace(' ', '%20', $param);
                    $link .= '&' . $key . '=' . $param;
                }
            }

            //echo $link;

            $data_json = file_get_contents($link);
            if (strlen($data_json) > 4) {
                $data_json = json_decode($data_json);
                if (!empty($params)) {
                    return $data_json;
                }
                return $data_json->elem;
            }
        }
        return false;
    }

    /**
     * KsenMartModelAccount::setEncryptAuth()
     * 
     * @param mixed $login
     * @param mixed $password
     * @param mixed $auth
     * @return
     */
    public function setEncryptAuth($login, $password, $auth) {

        $auth = $this->_session->get('auth');
        $data_string = $login . ':' . $password;
        $salt = 'poka_testovay';

        $query = $this->_db->getQuery(true);

        $columns = array('data', 'sessid');
        $values = array('AES_ENCRYPT(' . $this->_db->quote($data_string) . ', ' . $this->
                _db->quote($salt) . ')', $this->_db->quote($auth));

        $query->insert(KSDb::quoteName('#__ksen_billing_data'))->columns($this->
            _db->quoteName($columns))->values(implode(',', $values));

        $this->_db->setQuery($query);

        try {
            $result = $this->_db->query();
            if ($this->_db->getAffectedRows()) {
                return true;
            }
        }
        catch (exception $e) {

        }
        return false;
    }

    /**
     * KsenMartModelAccount::getEncryptAuth()
     * 
     * @return
     */
    public function getEncryptAuth() {
        $query = $this->_db->getQuery(true);
        $query->select("AES_DECRYPT(`data`, 'poka_testovay') AS data")->from('#__ksen_billing_data')->
            where('sessid=' . $this->_db->quote($this->_auth));

        $this->_db->setQuery($query, 0, 1);
        $data = $this->_db->loadObject();
        if (isset($data->data) && !empty($data->data)) {
            return $data->data;
        }
        return false;
    }

    /**
     * KsenMartModelAccount::removeEncryptAuth()
     * 
     * @return
     */
    public function removeEncryptAuth() {

        $query = $this->_db->getQuery(true);

        $conditions = array('sessid=' . $this->_auth);

        $query->delete(KSDb::quoteName('#__ksen_billing_data'));
        $query->where($conditions);

        $this->_db->setQuery($query);

        try {
            $result = $this->_db->query();
        }
        catch (exception $e) {

        }
    }

    /**
     * KsenMartModelAccount::getISPAuthKey()
     * 
     * @return
     */
    public function getISPAuthKey() {
        $key = $this->generateRandomString(32);
        $link = 'https://78.46.70.46/manager/ispmgr?out=json&func=session.newkey&username=vasya&key=' .
            $key;
        $result = file_get_contents($link);
        $result = json_decode($result);
        if (isset($result->ok)) {
            return $key;
        }

        return false;
    }

    /**
     * KsenMartModelAccount::generateRandomString()
     * 
     * @param integer $length
     * @return
     */
    function generateRandomString($length = 17) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}