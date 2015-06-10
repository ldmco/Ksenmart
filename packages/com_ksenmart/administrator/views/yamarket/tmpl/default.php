<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JHTML::_('behavior.modal'); 
?>
<div class="clearfix panel">
    <div class="pull-left">
        <?php echo KSSystem::loadModules('ks-top-left'); ?>
    </div>
    <div class="pull-right">
        <?php echo KSSystem::loadModules('ks-top-right'); ?>
    </div>
    <div class="row-fluid">
        <?php echo KSSystem::loadModules('ks-top-bottom'); ?>
    </div>
</div>
<div id="center" class="yamarket">
    <div class="user_shop">
        <span class="us__title"><?php echo $this->params->ya_site; ?> - <?php echo $this->shop_info->state; ?>
            <?php if(!empty($this->shop_info->stateReasons)){
                    echo '<span class="stateReason">(';
                    foreach($this->shop_info->stateReasons as $stateReason){
                        echo $stateReason.'; ';
                    }
                    echo ')</span>';
            } ?>
        </span>
    </div>
    <div class="info_blocks clearfix">
        <div class="item">
            <div class="title">Управление ставками</div>
            <div class="content">
                <div class="prior_clicks"><b>3</b> клика с мест приоритетного размещения</div>
                <div class="saving">Вчера автоброкер сэкономил 0.95 у.е.а</div>
                <div class="bid_management"><a href="javascript:void(0);">перейти к управлению ставками</a></div>
            </div>
        </div>
        <div class="item">
            <div class="title">Ассортимент</div>
            <div class="content">
                <!--<div class="dare_price_load">Прайс-лист загружен <b>18.10.2013</b> в <b>12:44</b>.</div>-->
                <div class="public_offers">Опубликовано <?php echo $this->offers->pager->total; ?> предложений</div>
                <!--<div class="report_indexing"><a href="javascript:void(0);">отчет по индексации</a></div>-->
            </div>
        </div>
        <div class="item">
            <div class="title">Оплата</div>
            <div class="content">
                <div class="balance">Остаток: <b><?php echo $this->balance->balance; ?></b> у.е., хватит на ~<?php echo $this->balance->daysLeft; ?> дней</div>
                <!--<div class="auto_check">
                    <form method="post" action="" class="payment-form total" id="quick-payment">
                        Пополнить счёт<br />на
                        <input type="text" name="sum" value="" size="6" maxlength="8" class="pay" /> &nbsp;<input type="submit" class="btn btn-other" id="payment_button" value="Выписать счет" />
                        <div class="error-info hidden">мин. сумма — <span id="min-payment">10.00</span> у.е</div>
                        <div>
                            <a href="javascript:void(0);" class="l-fake money-set" data-money-set="<?php echo $this->balance->recommendedPayment; ?>">~ на месяц</a>
                            <br />
                        </div>
                        <input type="hidden" value="21100113" name="id1" />
                    </form>
                </div>-->
            </div>
        </div>
    </div>
    <div class="order-statistics">
        <h3 class="statistic-header">
            Статистика за неделю
        </h3>
        <div class="statistic-tab_content">
            <table width="100%" class="dt">
                <thead>
                    <tr>
                        <th class="d">
                        </th>
                        <?php foreach($this->statistic as $statistic){ ?>
                        <th class="d">
                            <?php echo $this->formatStatisticDate($statistic->date); ?>
                        </th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="d">
                            Клики
                        </td>
                        <?php foreach($this->statistic as $statistic){ ?>
                        <td class="d">
                            <?php echo $statistic->clicks; ?>
                        </td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td class="d">
                            Расход
                        </td>
                        <?php foreach($this->statistic as $statistic){ ?>
                        <td class="d">
                            <?php echo $statistic->spending; ?>
                        </td>
                        <?php } ?>
                    </tr>
                    <!--<tr>
                        <td class="d">
                            Предложения
                        </td>
                        <td class="d">
                            <?php //echo $this->offersSstatistic->toOffer; ?>
                        </td>
                    </tr>-->
                </tbody>
            </table>
        </div>
    <div class="statistic-tab_table_item">
        <h3>
            Клики и расход по размещению
            <span class="period">
                (за вчера)
            </span>
        </h3>
        <table class="dt" width="90%">
            <thead>
                <tr>
                    <th class="d">
                        Места размещения
                    </th>
                    <th class="d">
                        Клики
                    </th>
                    <th class="d">
                        Расход, у.е.
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($this->statisticByPlaces as $statistic){ ?>
                <tr>
                    <td class="d"><?php echo $statistic->placeGroup; ?></td>
                    <td class="d"><?php echo $statistic->clicks; ?></td>
                    <td class="d"><?php echo $statistic->spending; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="index.php?option=com_ksenmart&view=yamarket&layout=stat-placement">подробный отчёт</a>
    </div>
    <div class="statistic-tab_table_item">
        <h3>
            Клики и расход по товарам и категориям
            <span class="period">
                (за вчера)
            </span>
        </h3>
        <table class="dt" width="100%">
            <thead>
                <tr>
                    <th class="d">
                        Название
                    </th>
                    <th class="d">
                        Клики
                    </th>
                    <th class="d">
                        Расход, у.е.
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($this->offersStatistic->offerStats as $offerStats){ ?>
                <tr>
                    <td class="d"><?php echo $offerStats->offerName; ?></td>
                    <td class="d"><?php echo $offerStats->clicks; ?></td>
                    <td class="d"><?php echo $offerStats->spending; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="index.php?option=com_ksenmart&view=yamarket&layout=clicks-report-search">подробный отчёт</a>
    </div>
    </div>
</div>