<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelksadmin');
jimport('joomla.http.transport.curl');

class KsenMartModelYaMarket extends JModelKSAdmin{
    
    private $_sid = null;
    
    public function __construct($config = array()) {
		parent::__construct($config);
	}
    
    public function getUserParams($uid)
    {
        $this->onExecuteBefore('getUserParams', array(&$uid));
        
        if(!empty($uid) & $uid > 0){
            $params = array(
                'task' => 'params',
                'uid' => $uid
            );

            $params = json_decode(KSSystem::getLdmApiData('user', null, $params));
            
            $this->onExecuteAfter('getUserParams', array(&$uid));
            
            if(!isset($params->error)){
                return $params;
            }elseif(isset($params->error)){
                return $params->error;
            }
        }
        
        return new JObject;
    }
    
    public function getUserShopInfo($sid)
    {
        $this->onExecuteBefore('getUserShopInfo', array(&$sid));
        
        if(!empty($sid) & $sid > 0){
            $this->_sid = $sid;
            $function = array(
                'campaigns',
                $this->_sid
            );
            $shop_info = KSSystem::getYaMarketData($function);
            
            if($shop_info){
                $shop_info               = $this->setShopState($shop_info->campaign);
                if(isset($shop_info->stateReasons)){
                    $shop_info->stateReasons = $this->setShopStateReasons($shop_info->stateReasons);
                }
                
                $this->onExecuteAfter('getUserShopInfo', array(&$shop_info));
                return $shop_info;
            }
        }
        
        return new JObject;
    }
    
    private function setShopState($shop_info)
    {
        $this->onExecuteBefore('setShopState', array(&$shop_info));
        
        if(!empty($shop_info)){
            switch ($shop_info->state) {
                case 1:
                    $shop_info->state = 'Компания включена';
                break;
                case 2:
                    $shop_info->state = 'Компания выключена';
                break;
                case 3:
                    $shop_info->state = 'Компания включается';
                break;
                case 4:
                    $shop_info->state = 'Компания выключается';
                break;
            }
        }
        
        $this->onExecuteAfter('setShopState', array(&$shop_info));
        return $shop_info;
    }
    
    private function setShopStateReasons($stateReasons)
    {
        $this->onExecuteBefore('setShopStateReasons', array(&$stateReasons));
        
        if(!empty($stateReasons)){
            foreach($stateReasons as &$stateReason)
            switch ($stateReason) {
                case 5:
                    $stateReason = 'кампания проверяется';
                break;
                case 6:
                    $stateReason = 'требуется проверка кампании';
                break;
                case 7:
                    $stateReason = 'кампания выключена или выключается менеджером';
                break;
                case 9:
                    $stateReason = 'кампания выключена или выключается из-за финансовых проблем';
                break;
                case 11:
                    $stateReason = 'кампания выключена или выключается из-за ошибок в прайс-листе кампании';
                break;
                case 12:
                    $stateReason = 'кампания выключена или выключается пользователем';
                break;
                case 13:
                    $stateReason = 'кампания выключена или выключается за неприемлемое качество';
                break;
                case 15:
                    $stateReason = 'кампания выключена или выключается из-за обнаружения дублирующих витрин';
                break;
                case 16:
                    $stateReason = 'кампания выключена или выключается из-за прочих проблем качества';
                break;
                case 20:
                    $stateReason = 'кампания выключена или выключается по расписанию';
                break;
                case 21:
                    $stateReason = 'кампания выключена или выключается, так как сайт кампании временно недоступен';
                break;
                case 24:
                    $stateReason = 'кампания выключена или выключается за недостаток информации о магазине';
                break;
                case 25:
                    $stateReason = 'кампания выключена или выключается из-за неактуальности информации';
                break;
            }
        }
        
        $this->onExecuteAfter('setShopStateReasons', array(&$stateReasons));
        return $stateReasons;
    }
    
    public function getShopOffers()
    {
        $this->onExecuteBefore('getShopOffers');
        
        if(!empty($this->_sid) && $this->_sid > 0){
            $function = array(
                'campaigns',
                $this->_sid,
                'offers'
            );

            $offers = KSSystem::getYaMarketData($function);
            
            $this->onExecuteAfter('getShopOffers', array(&$offers));
            return $offers;
        }
        return new JObject;
    }
    
    public function getShopBalance()
    {
        $this->onExecuteBefore('getShopBalance');
        
        if(!empty($this->_sid) && $this->_sid > 0){
            $function = array(
                'campaigns',
                $this->_sid,
                'balance'
            );
            $balance = KSSystem::getYaMarketData($function);
            
            $this->onExecuteAfter('getShopBalance', array(&$balance->balance));
            return $balance->balance;
        }
        return new JObject;
    }
    
    public function getShopStatistic()
    {
        $this->onExecuteBefore('getShopStatistic');
        
        if(!empty($this->_sid) && $this->_sid > 0){
            $date = new DateTime();
            $date->modify('-7 day');
            
            $function = array(
                'campaigns',
                $this->_sid,
                'stats',
                'main'
            );
            $params = array(
                'fromDate' => $date->format('d-m-Y')
            );
            
            $statistic = KSSystem::getYaMarketData($function, 'GET', $params);
            rsort($statistic->mainStats);
            
            $this->onExecuteAfter('getShopStatistic', array(&$statistic->mainStats));
            return $statistic->mainStats;
        }
        return new JObject;
    }
    
    public function getShopOffersStatistic($groupBy = '-1', $total = false)
    {
        $this->onExecuteBefore('getShopOffersStatistic', array(&$groupBy, &$total));
        
        if(!empty($this->_sid) && $this->_sid > 0){
            $date = new DateTime();
            $date->modify($groupBy.' day');
            
            $function = array(
                'campaigns',
                $this->_sid,
                'stats',
                'offers'
            );
            $params = array(
                'fromDate' => $date->format('d-m-Y')
            );
            
            $statistic = KSSystem::getYaMarketData($function, 'GET', $params);
            if($statistic){
                if($total){
                    $total = new stdClass;
                    $total->clicks   = 0;
                    $total->spending = 0;
                    foreach($statistic->offersStats->offerStats as $stat){
                        $total->clicks   += $stat->clicks;
                        $total->spending += $stat->spending;
                    }
                    $statistic->offersStats->total = $total;
                }
                
                $this->onExecuteAfter('getShopOffersStatistic', array(&$statistic->offersStats));
                return $statistic->offersStats;
            }
        }
        return new JObject;
    }
    
    public function getShopStatisticByPlaces()
    {
        $this->onExecuteBefore('getShopStatisticByPlaces');
        
        if(!empty($this->_sid) && $this->_sid > 0){
            $date = new DateTime();
            $date->modify('-1 day');
            
            $function = array(
                'campaigns',
                $this->_sid,
                'stats',
                'main'
            );
            $params = array(
                'fromDate' => $date->format('d-m-Y'),
                'toDate' => $date->format('d-m-Y'),
                'byPlaces' => 1
            );
            
            $statistic = KSSystem::getYaMarketData($function, 'GET', $params);
            if($statistic){
                
                $this->onExecuteAfter('getShopStatisticByPlaces', array(&$statistic->mainStats));
                return $this->setShopStatisticPlaces($statistic->mainStats);;
            }

        }
        return new JObject;
    }
    
    private function setShopStatisticPlaces($statisticByPlaces)
    {
        $this->onExecuteBefore('setShopStatisticPlaces', array(&$statisticByPlaces));
        
        if(!empty($statisticByPlaces)){
            foreach($statisticByPlaces as $statistic){
                switch ($statistic->placeGroup) {
                    case 3:
                        $statistic->placeGroup = 'Поиск Яндекса';
                    break;
                    case 4:
                        $statistic->placeGroup = 'Карточка модели';
                    break;
                    case 6:
                        $statistic->placeGroup = 'Рекламная сеть Яндекса';
                    break;
                    case 5:
                        $statistic->placeGroup = 'Маркет (кроме карточек)';
                    break;
                }
            }
        }
        
        $this->onExecuteAfter('setShopStatisticPlaces', array(&$statisticByPlaces));
        return $statisticByPlaces;
    }
    
    public function getShopStatisticByPlacesFull($fromDate, $toDate, $groupBy)
    {
        $this->onExecuteBefore('getShopStatisticByPlacesFull', array(&$fromDate, &$toDate, &$groupBy));
        
        if(!empty($this->_sid) && $this->_sid > 0){
            
            $fromDate = new DateTime($fromDate);
            $fromDate = $fromDate->format('d-m-Y');
            $toDate   = new DateTime($toDate);
            $toDate   = $toDate->format('d-m-Y');
            
            $function = array(
                'campaigns',
                $this->_sid,
                'stats',
                'main-'.$groupBy
            );
            $params = array(
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'byPlaces' => 1
            );
            
            $statistic = KSSystem::getYaMarketData($function, 'GET', $params);
            if($statistic && count($statistic->mainStats) > 0){
                //$statistic          = $this->setShopStatisticPlaces($statistic->mainStats);
                $statistic_group    = new stdClass;
                $total->{3}['clicks']   = 0;
                $total->{3}['spending'] = 0;
                $total->{4}['clicks']   = 0;
                $total->{4}['spending'] = 0;
                $total->{5}['clicks']   = 0;
                $total->{5}['spending'] = 0;
                $total->{6}['clicks']   = 0;
                $total->{6}['spending'] = 0;
                
                foreach ($statistic->mainStats as $stat){
                    $date = date("d.m.Y", strtotime($stat->date));
                    $statistic_group->{$date}->{$stat->placeGroup}['clicks']   = $stat->clicks;
                    $statistic_group->{$date}->{$stat->placeGroup}['spending'] = $stat->spending;
                    
                    $total->{$stat->placeGroup}['clicks']   += $stat->clicks;
                    $total->{$stat->placeGroup}['spending'] += $stat->spending;
                    
                    if(!isset($statistic_group->{$date}->{3})){
                        $statistic_group->{$date}->{3}['clicks']      = 0;
                        $statistic_group->{$date}->{3}['spending']    = 0;
                    }
                    if(!isset($statistic_group->{$date}->{4})){
                        $statistic_group->{$date}->{4}['clicks']      = 0;
                        $statistic_group->{$date}->{4}['spending']    = 0;
                    }
                    if(!isset($statistic_group->{$date}->{5})){
                        $statistic_group->{$date}->{5}['clicks']      = 0;
                        $statistic_group->{$date}->{5}['spending']    = 0;
                    }
                    if(!isset($statistic_group->{$date}->{6})){
                        $statistic_group->{$date}->{6}['clicks']      = 0;
                        $statistic_group->{$date}->{6}['spending']    = 0;
                    }

                    $statistic_group->{$date}->totalClicks    += $stat->clicks;
                    $statistic_group->{$date}->totalSpending  += $stat->spending;
                    $total->totalSpending          += $stat->spending;
                    $total->totalClicks            += $stat->clicks;
                }
                $statistic_group->km_total = $total;
                
                $this->onExecuteAfter('getShopStatisticByPlacesFull', array(&$statistic_group));
                return $statistic_group;
            }
        }
        return new stdClass;
    }
    
    public function getTest()
    {
        $this->onExecuteBefore('getTest');
        
        if(!empty($this->_sid) && $this->_sid > 0){
            $function = array(
                'campaigns',
                $this->_sid,
                'bids'
            );
            $params = array(
                'target' => 'search'
            );

            $test = KSSystem::getYaMarketData2($function);
            
            $this->onExecuteAfter('getTest', array(&$test));
            return $test;
        }
        return new stdClass;
    }
}