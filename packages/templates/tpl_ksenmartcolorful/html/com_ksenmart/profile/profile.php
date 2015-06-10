<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="user-panel">
	<div class="tabbable noTransition">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab1" data-toggle="tab"><i class="icon-shopping-cart"></i> <?php echo JText::_('KSM_PROFILE_MY_ORDERS'); ?></a></li>
			<li><a href="#tab5" data-toggle="tab"><i class="icon-heart"></i> <?php echo JText::_('KSM_PROFILE_FAVOURITES'); ?></a></li>
            <li><a href="#tab6" data-toggle="tab"><i class="icon-eye-open"></i> <?php echo JText::_('KSM_PROFILE_WATCHED'); ?></a></li>
            <li><a href="#tab2" data-toggle="tab"><i class="icon-info-sign"></i> <?php echo JText::_('KSM_PROFILE_INFO'); ?></a></li>
			<li><a href="#tab3" data-toggle="tab"><i class="icon-home"></i> <?php echo JText::_('KSM_PROFILE_ADDRESSES'); ?></a></li>
            <li><a href="#tab4" data-toggle="tab"><i class="icon-comment"></i> <?php echo JText::_('KSM_PROFILE_REVIEWS'); ?></a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane orders active" id="tab1">
				<?php echo $this->loadTemplate('orders'); ?>
			</div>
			<div class="tab-pane favorities" id="tab5" data-type="favorities">
				<?php echo $this->loadTemplate('favorities'); ?>
			</div>
			<div class="tab-pane watched" id="tab6" data-type="watched">
				<?php echo $this->loadTemplate('watched'); ?>
			</div>
			<div class="tab-pane" id="tab2">
				<?php echo $this->loadTemplate('info'); ?>
			</div>
			<div class="tab-pane" id="tab3">
				<?php echo $this->loadTemplate('addresses'); ?>
			</div>
			<div class="tab-pane" id="tab4">
				<?php echo $this->loadTemplate('reviews'); ?>
			</div>
		</div>
	</div>
</div>