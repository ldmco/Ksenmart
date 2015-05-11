<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
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
<div id="metro-ui" rel="<?php echo $this->state->get('extension');?>">
	<div class="metro-ui-inner">
		<?php foreach($this->widgets_groups as $widgets_group):?>
		<div class="widgets">
			<?php foreach($widgets_group as $widget):?>
			<a class="<?php echo $widget->class;?>" href="<?php echo $widget->href;?>" id="<?php echo $widget->name;?>">
				<img src="<?php echo $widget->image;?>" alt="" />
				<span><?php echo JText::_('ks_'.$widget->name);?></span>
				<div>
					<h3><?php echo JText::_('ks_'.$widget->name);?></h3>
					<?php echo $widget->info;?>
				</div>
			</a>			
			<?php endforeach;?>
		</div>
		<div class="margins"></div>
		<?php endforeach;?>
	</div>
</div>