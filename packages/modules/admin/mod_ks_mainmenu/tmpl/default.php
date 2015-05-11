<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksenmart-mainmenu">
	<?php if (!$parent_widget): ?>
	<div id="main-menu">
		<ul>
			<li class="ksenmart-mainmenu-home icon">
				<a class="<?php echo ($current_widget_type=='all'?'active':''); ?>" href="index.php?option=com_ksen&view=panel&widget_type=all&extension=<?php echo $extension; ?>"><span></span></a>		
			</li>		
			<?php foreach($widget_types as $widget_type):?>
			<li class="ksenmart-mainmenu-<?php echo $widget_type->name;?>">
				<a class="<?php echo ($widget_type->name==$current_widget_type?'active':''); ?>" href="index.php?option=com_ksen&view=panel&widget_type=<?php echo $widget_type->name; ?>&extension=<?php echo $extension; ?>">
				<?php echo JText::_('ks_' . strtoupper($widget_type->name)); ?>
				</a>
			</li>
			<?php endforeach;?>
			<div class="clr"></div>
		</ul>
        <a href="index.php?option=com_ksen&view=settings&extension=<?php echo $extension; ?>" class="prefs"><span></span></a>
        <a href="javascript:void(0);" class="info js-info" rel="<?php echo $extension; ?>"><span></span></a>
        <a href="javascript:void(0);" class="quest"><span></span></a>
	</div>	
	<?php endif;?>
	<?php if($parent_widget): ?>
	<div id="cat-menu">
		<ul>
			<li class="ksenmart-mainmenu-back">
				<a href="index.php?option=com_ksen&view=panel&widget_type=<?php echo $current_widget_type; ?>&extension=<?php echo $extension; ?>"></a>		
			</li>		
			<li class="ksenmart-mainmenu-parent-component">
				<a class="<?php echo ($parent_widget->name==$current_widget->name?'active':''); ?>" href="<?php echo $parent_widget->href;?>">
				<?php echo JText::_('ks_' . strtoupper($parent_widget->name));?>
				</a>		
			</li>
			<?php foreach($child_widgets as $child_widget):?>
				<li class="ksenmart-mainmenu-child-component">
					<a class="<?php echo ($child_widget->name==$current_widget->name?'active':''); ?>" href="<?php echo $child_widget->href; ?>">
					<?php echo JText::_('ks_' . strtoupper($child_widget->name)); ?>
					</a>
				</li>
			<?php endforeach;?>
			<div class="clr"></div>
		</ul>
        <a href="index.php?option=com_ksen&view=settings&extension=<?php echo $extension; ?>" class="prefs"><span></span></a>
        <a href="javascript:void(0);" class="info js-info" rel="<?php echo $extension; ?>"><span></span></a>
	</div>	
	<?php endif;?>
</div>