<?php defined('_JEXEC') or die; ?>
<?php
	$user          = KSUsers::getUser();
    $profile_link  = JRoute::_('index.php?option=com_ksenmart&view=profile&Itemid='.KSSystem::getShopItemid());
?>
<div class="accordion catalog-menu" id="dropdownCat">
	<h3><?php echo $module->title; ?></h3>
	<div class="user-info">
		<div class="avatar">
			<img src="<?php echo $user->logo_thumb; ?>" alt="<?php echo $user->name; ?>" class="border_ksen" />
		</div>
		<div class="user_name"><?php echo $user->name; ?></div>
		<br clear="both">
	</div>
	<div class="left-menu">
		<ul class="nav nav-list">
			<li><a href="<?php echo $profile_link; ?>#tab1">Мои заказы</a></li>
			<li><a href="<?php echo $profile_link; ?>#tab5">Избранные товары</a></li>
			<li><a href="<?php echo $profile_link; ?>#tab6">Отслеживаемые товары</a></li>
			<li><a href="<?php echo $profile_link; ?>#tab2">Информация обо мне</a></li>
			<li><a href="<?php echo $profile_link; ?>#tab3">Мои адреса</a></li>
			<li><a href="<?php echo $profile_link; ?>#tab4">Мои отзывы</a></li>
		</ul>
	</div>
</div>