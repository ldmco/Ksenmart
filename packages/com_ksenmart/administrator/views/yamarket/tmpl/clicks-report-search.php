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
    <form class="form-inline" action="index.php?option=com_ksenmart&view=yamarket&layout=clicks-report-search" method="POST">
            Предложения за 
            <?php echo $this->groupByList; ?>
            <input type="submit" value="Показать" class="btn btn-other" />  
    </form>
    <table width="100%">
        <thead>
            <tr>
                <th class="d">
                    Наименование предложения
                </th>
                <th class="d">
                    Количество кликов
                </th>
                <th class="d">
                    Расход, у.е.
                </th>
            </tr>
            <tr>
                <th class="d">
                    Итого
                </th>
                <th class="d">
                    <strong><?php echo $this->offersStatistic->total->clicks; ?></strong>
                </th>
                <th class="d">
                    <strong><?php echo $this->offersStatistic->total->spending; ?></strong>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($this->offersStatistic->offerStats as $offers){ ?>
            <tr>
                <td class="d name"><?php echo $offers->offerName; ?></td>
                <td class="d"><?php echo $offers->clicks; ?></td>
                <td class="d"><?php echo $offers->spending; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>