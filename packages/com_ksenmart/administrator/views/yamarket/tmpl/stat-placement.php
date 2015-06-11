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
</div>
<div id="center" class="yamarket">
    <form class="form-inline" action="index.php?option=com_ksenmart&view=yamarket&layout=stat-placement" method="POST">
            С <?php echo JHTML::_('calendar', $this->fromDate, 'fromDate', 'fromDate', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19', 'required'=>'true')); ?>
            По <?php echo JHTML::_('calendar', $this->toDate, 'toDate', 'toDate', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19', 'required'=>'true')); ?>
            группировать по 
            <?php echo $this->groupByList; ?>
            <input type="submit" value="Показать" class="btn btn-other" />  
    </form>
    <table width="100%" class="dt">
        <tbody>
            <tr>
                <th class="d" rowspan="2" width="10%">Дата</th>
                <th class="d" colspan="5" width="45%">Клики, шт.</th>
                <th class="d" colspan="5" width="45%">Условная стоимость, у.е.</th>
            </tr>
            <tr>
                <th class="d" width="10%">
                    поиск+
                </th>
                <th class="d" width="10%">
                    партнеры
                </th>
                <th class="d" width="10%">
                    карточки моделей
                </th>
                <th class="d" width="10%">
                    маркет кроме карточек
                </th>
                <th class="d" width="10%">
                    всего
                </th>
                <th class="d" width="10%">
                    поиск+
                </th>
                <th class="d" width="10%">
                    партнеры
                </th>
                <th class="d" width="10%">
                    карточки моделей
                </th>
                <th class="d" width="10%">
                    маркет кроме карточек
                </th>
                <th class="d" width="10%">
                    всего
                </th>
            </tr>
            <?php foreach($this->statistic as $key => $dates){ ?>
            <tr>
                <td class="d"><?php echo JText::_($key); ?></td>
                <td class="d"><?php echo $dates->{3}['clicks']; ?></td>
                <td class="d"><?php echo $dates->{6}['clicks']; ?></td>
                <td class="d"><?php echo $dates->{4}['clicks']; ?></td>
                <td class="d"><?php echo $dates->{5}['clicks']; ?></td>
                <td class="d"><?php echo $dates->totalClicks; ?></td>
                <td class="d"><?php echo $dates->{3}['spending']; ?></td>
                <td class="d"><?php echo $dates->{6}['spending']; ?></td>
                <td class="d"><?php echo $dates->{4}['spending']; ?></td>
                <td class="d"><?php echo $dates->{5}['spending']; ?></td>
                <td class="d"><?php echo $dates->totalSpending; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <br />
    * - по данному дню статистика не полная, т.к. день еще не закончился.
</div>