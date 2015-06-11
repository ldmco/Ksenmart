<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<?php
	$user          = KSUsers::getUser();
    $profile_link  = JRoute::_('index.php?option=com_ksenmart&view=profile');
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
			<li><a href="<?php echo $profile_link; ?>#tab1"><?php echo JText::_('MOD_KM_PROFILE_INFO_MY_ORDERS'); ?></a></li>
			<li><a href="<?php echo $profile_link; ?>#tab5"><?php echo JText::_('MOD_KM_PROFILE_INFO_FAVOURITES'); ?></a></li>
			<li><a href="<?php echo $profile_link; ?>#tab6"><?php echo JText::_('MOD_KM_PROFILE_INFO_WATCHED'); ?></a></li>
			<li><a href="<?php echo $profile_link; ?>#tab2"><?php echo JText::_('MOD_KM_PROFILE_INFO_MY_INFO'); ?></a></li>
			<li><a href="<?php echo $profile_link; ?>#tab3"><?php echo JText::_('MOD_KM_PROFILE_INFO_MY_ADDRESSES'); ?></a></li>
			<li><a href="<?php echo $profile_link; ?>#tab4"><?php echo JText::_('MOD_KM_PROFILE_INFO_MY_REVIEWS'); ?></a></li>
		</ul>
	</div>
</div>