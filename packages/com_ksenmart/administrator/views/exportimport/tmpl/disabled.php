<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
?>
<div class="disabled-form">
	<div class="save-close">
		<input type="button" class="close" onclick="parent.closePopupWindow();">
	</div>
	<div class="disabled-text">
		<h3>Внимание! время действия  <br>демонстрационного режима <br>закончилось</h3>
		<h2>Все функции программы <br>доступны в PRO-версии </h2>
		<br>
		<a class="buy-pro">Купить</a>
		<a class="info-pro">Подробнее</a>
	</div>
</div>