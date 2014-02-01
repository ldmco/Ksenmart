<?php defined( '_JEXEC' ) or die; ?>
<div class="ksenmart-mainmenu">
	<?php if (!$parent_widget): ?>
	<div id="main-menu">
		<ul>
			<li class="ksenmart-mainmenu-home icon">
				<a class="<?php echo ($current_widget_type=='all'?'active':''); ?>" href="index.php?option=com_ksenmart&view=panel&widget_type=all"><span></span></a>		
			</li>		
			<?php foreach($widget_types as $widget_type):?>
			<li class="ksenmart-mainmenu-<?php echo $widget_type->name;?>">
				<a class="<?php echo ($widget_type->name==$current_widget_type?'active':''); ?>" href="index.php?option=com_ksenmart&view=panel&widget_type=<?php echo $widget_type->name; ?>">
				<?php echo JText::_('ksm_'.$widget_type->name); ?>
				</a>
			</li>
			<?php endforeach;?>
			<div class="clr"></div>
		</ul>
        <a href="index.php?option=com_ksenmart&view=allsettings" class="prefs"><span></span></a>
        <a href="javascript:void(0);" class="info js-info"><span></span></a>
        <a href="javascript:void(0);" class="quest"><span></span></a>
	</div>	
	<?php endif;?>
	<?php if($parent_widget): ?>
	<div id="cat-menu">
		<ul>
			<li class="ksenmart-mainmenu-back">
				<a href="index.php?option=com_ksenmart&view=panel&widget_type=<?php echo $current_widget_type; ?>"></a>		
			</li>		
			<li class="ksenmart-mainmenu-parent-component">
				<a class="<?php echo ($parent_widget->name==$current_widget->name?'active':''); ?>" href="<?php echo $parent_widget->href;?>">
				<?php echo JText::_('ksm_'.$parent_widget->name);?>
				</a>		
			</li>
			<?php foreach($child_widgets as $child_widget):?>
				<li class="ksenmart-mainmenu-child-component">
					<a class="<?php echo ($child_widget->name==$current_widget->name?'active':''); ?>" href="<?php echo $child_widget->href; ?>">
					<?php echo JText::_('ksm_'.$child_widget->name); ?>
					</a>
				</li>
			<?php endforeach;?>
			<div class="clr"></div>
		</ul>
        <a href="index.php?option=com_ksenmart&view=allsettings" class="prefs"><span></span></a>
        <a href="javascript:void(0);" class="info js-info"><span></span></a>
        <a href="javascript:void(0);" class="quest"><span></span></a>
	</div>	
	<?php endif;?>
</div>