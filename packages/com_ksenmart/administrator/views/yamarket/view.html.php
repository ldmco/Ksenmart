<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewksadmin');
class KsenMartViewYaMarket extends JViewKSAdmin {

    public function display($tpl = null) {

        $_session       = JFactory::getSession();
        $model_account  = KSSystem::getModel('account');
        $controller     = KSSystem::getController('account');
        $this->title    = JText::_('KM_CATALOG_YA_MARKET');
        $model          = $this->getModel('yamarket');
        $app            = JFactory::getApplication();
        
        $this->document->addScript(JURI::base().'components/com_ksenmart/js/yamarket.js');
        $this->document->addStyleSheet(JURI::base() . 'components/com_ksenmart/css/yamarket.css');
        
        if(!$model_account->checkAuthorize()){
            $app->redirect('index.php?option=com_ksenmart&view=account&layout=default_login');
        }else{
            $uid                = $model_account->getUserId();
            if(!empty($uid) && $uid > 0){
                
                $layout      = $this->getLayout();
                $params      = $model->getUserParams($uid);
                
                if(empty($params->ya_site_id)){
                    $app->redirect('index.php?option=com_ksenmart', 'KM_YA_MARKET_ACCESS_DENIED');
                    exit();
                }
                $shop_info   = $model->getUserShopInfo($params->ya_site_id);
                
                switch($layout){
                    case 'stat-placement':
                        $jinput      = $app->input;
                        $fromDate    = $jinput->get('fromDate', date('Y-m-d'), 'string');
                        $toDate      = $jinput->get('toDate', date('Y-m-d'), 'string');
                        $groupBy     = $jinput->get('groupBy', 'daily', 'string');
                        
                        $this->path->addItem(JText::_('KM_YA_MARKET'), 'index.php?option=com_ksenmart&view=yamarket');
                        $this->path->addItem(JText::_('KM_YA_HITS_FOR_PLACEMENTS'));
                        
                        $statistic = $model->getShopStatisticByPlacesFull($fromDate, $toDate, $groupBy);
                        
                        $groupByList = JHTML::_(
                            'select.genericlist' /* тип элемента формы */,
                            array(
                                'daily'     => 'дням',
                                'weekly'    => 'неделям',
                                'monthly'   => 'месяцам'
                            ) /* массив, каждый элемент которого содержит value и текст */, 
                            'groupBy' /* id и name select`a формы */,
                            'class="inputbox"' /* другие атрибуты элемента select */, 
                            'value' /* название поля в массиве объектов содержащего ключ */,
                            'text' /* название поля в массиве объектов содержащего значение */,
                            $groupBy /* value элемента, который должен быть выбран (selected) по умолчанию */
                        );
                        
                        $this->assignRef('statistic', $statistic);
                        $this->assignRef('fromDate', $fromDate);
                        $this->assignRef('toDate', $toDate);
                        $this->assignRef('groupByList', $groupByList);
                    break;
                    case 'clicks-report-search':
                        $jinput      = $app->input;
                        $groupBy     = $jinput->get('groupBy', '-1', 'string');
                        
                        $this->path->addItem(JText::_('KM_YA_MARKET'), 'index.php?option=com_ksenmart&view=yamarket');
                        $this->path->addItem(JText::_('KM_YA_CLICKS_REPORT_SEARCH'));
                        
                        $offersStatistic    = $model->getShopOffersStatistic($groupBy, true);
 
                        $groupByList = JHTML::_(
                            'select.genericlist' /* тип элемента формы */,
                            array(
                                '-1'  => 'вчера',
                                '-7'  => '7 дней',
                                '-30' => '30 дней'
                            ) /* массив, каждый элемент которого содержит value и текст */, 
                            'groupBy' /* id и name select`a формы */,
                            'class="inputbox"' /* другие атрибуты элемента select */, 
                            'value' /* название поля в массиве объектов содержащего ключ */,
                            'text' /* название поля в массиве объектов содержащего значение */,
                            $groupBy /* value элемента, который должен быть выбран (selected) по умолчанию */
                        );
                        
                        $this->assignRef('offersStatistic', $offersStatistic);
                        $this->assignRef('groupByList', $groupByList);
                    break;
                    default:
                        $offers             = $this->get('ShopOffers');
                        $balance            = $this->get('ShopBalance');
                        $statistic          = $this->get('ShopStatistic');
                        $statisticByPlaces  = $this->get('ShopStatisticByPlaces');
                        $offersStatistic    = $this->get('ShopOffersStatistic');
                        
                        /*$test    = $this->get('Test');
                        print_r($test);*/
                        
            
                        $this->assignRef('params', $params);
                        $this->assignRef('offers', $offers);
                        $this->assignRef('balance', $balance);
                        $this->assignRef('statistic', $statistic);
                        $this->assignRef('statisticByPlaces', $statisticByPlaces);
                        $this->assignRef('offersStatistic', $offersStatistic);
                        $this->assignRef('shop_info', $shop_info);
                    break;
                }
            }
        }
        parent::display($tpl);
    }
    
    public function formatStatisticDate($date){
        if(!empty($date)){
            
            list($year, $mounth, $day) = explode('-', $date);
            $day    = date("D", strtotime($date));
            $c_date = date('Y-m-d');
            
            if(strtotime($date) == strtotime($c_date)){
                return 'сегодня';
            }
            switch($day){
                case 'Mon':
                    $day = 'пн';
                break;
                case 'Sun':
                    $day = 'вс';
                break;
                case 'Sat':
                    $day = 'сб';
                break;
                case 'Fri':
                    $day = 'пт';
                break;
                case 'Thu':
                    $day = 'чт';
                break;
                case 'Wed':
                    $day = 'ср';
                break;
                case 'Tue':
                    $day = 'вт';
                break;
            }
            
            return $day.' '.date("d.m", strtotime($date));
        }
        return false;
    }
}