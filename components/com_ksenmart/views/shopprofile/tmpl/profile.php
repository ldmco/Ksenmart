<?php defined('_JEXEC') or die; ?>
<div class="user-panel">
	<div class="tabbable noTransition">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab1" data-toggle="tab"><i class="icon-shopping-cart"></i> Мои заказы</a></li>
			<li><a href="#tab5" data-toggle="tab"><i class="icon-heart"></i> Избранные</a></li>
            <li><a href="#tab6" data-toggle="tab"><i class="icon-eye-open"></i> Отслеживаемые</a></li>
            <li><a href="#tab2" data-toggle="tab"><i class="icon-info-sign"></i> Информация</a></li>
			<li><a href="#tab3" data-toggle="tab"><i class="icon-home"></i> Адрес доставки</a></li>
            <li><a href="#tab4" data-toggle="tab"><i class="icon-comment"></i> Отзывы</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane orders active" id="tab1">
				<?php require('profile_orders.php'); ?>
			</div>
			<div class="tab-pane favorities" id="tab5" data-type="favorities">
				<?php require('profile_favorities.php'); ?>
			</div>
			<div class="tab-pane watched" id="tab6" data-type="watched">
				<?php require('profile_watched.php'); ?>
			</div>
			<div class="tab-pane" id="tab2">
				<?php require('profile_info.php'); ?>
			</div>
			<div class="tab-pane" id="tab3">
				<?php require('profile_addresses.php'); ?>
			</div>
			<div class="tab-pane" id="tab4">
				<?php require('profile_reviews.php'); ?>
			</div>
		</div>
	</div>
</div>