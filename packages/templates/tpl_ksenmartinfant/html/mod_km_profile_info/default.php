<?php defined('_JEXEC') or die; ?>
<?php
	$user          = KSUsers::getUser();
    $profile_link  = JRoute::_('index.php?option=com_ksenmart&view=profile');
?>
	<h4><?php echo $module->title; ?></h4>
	<ul class="list-footer toggle_content clearfix">
		<li><a href="<?php echo $profile_link; ?>#tab1" rel="nofollow"><i class="icon-file-alt"></i>Мои заказы</a></li>
		<li><a href="<?php echo $profile_link; ?>#tab6" rel="nofollow"><i class="icon-save"></i>Отслеживаемые товары</a></li>
		<li><a href="<?php echo $profile_link; ?>#tab5" rel="nofollow"><i class="icon-heart"></i>Избранные товары</a></li>
		<li><a href="<?php echo $profile_link; ?>#tab2" rel="nofollow"><i class="icon-user"></i>Информация обо мне</a></li>
		<li><a href="<?php echo $profile_link; ?>#tab3" rel="nofollow"><i class="icon-home"></i>Мои адреса</a></li>
		<li><a href="<?php echo $profile_link; ?>#tab4" rel="nofollow"><i class="icon-comment"></i>Мои отзывы</a></li>
	</ul>
