<?php defined( '_JEXEC' ) or die; ?>
<div class="clearfix panel">
    <div class="pull-left">
        <?php echo KMSystem::loadModules('km-top-left'); ?>
    </div>
    <div class="pull-right">
        <?php echo KMSystem::loadModules('km-top-right'); ?>
    </div>
    <div class="row-fluid">
        <?php echo KMSystem::loadModules('km-top-bottom'); ?>
    </div>
</div>
<div id="metro-ui">
	<div class="metro-ui-inner">
		<?php foreach($this->widgets_groups as $widgets_group):?>
		<div class="widgets">
			<?php foreach($widgets_group as $widget):?>
			<a class="<?php echo $widget->class;?>" href="<?php echo $widget->href;?>" id="<?php echo $widget->name;?>">
				<img src="<?php echo $widget->image;?>" alt="" />
				<span><?php echo JText::_('ksm_'.$widget->name);?></span>
				<div>
					<h3><?php echo JText::_('ksm_'.$widget->name);?></h3>
					<?php echo $widget->info;?>
				</div>
			</a>			
			<?php endforeach;?>
		</div>
		<div class="margins"></div>
		<?php endforeach;?>
	</div>
</div>