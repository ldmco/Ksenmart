<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksenmart-mainmenu">
	<?php if ($is_panel): ?>
        <div id="main-menu">
            <ul>
                <li class="ksenmart-mainmenu-home icon">
                    <a class="<?php echo empty($current_extension)?'active':''; ?>" href="index.php?option=com_ksen&view=panel"><span></span></a>
                </li>			
                <?php foreach($widget_extensions as $widget_extension):?>
                <li class="ksenmart-mainmenu-<?php echo $widget_extension->name;?>">
                    <a class="<?php echo ($widget_extension->extension==$current_extension?'active':''); ?>" href="index.php?option=com_ksen&view=panel&extension=<?php echo $widget_extension->extension; ?>">
                    <?php echo JText::_(strtoupper($widget_extension->name)); ?>
                    </a>
                </li>
                <?php endforeach;?>
                <div class="clr"></div>
            </ul>
	        <?php if (empty($billing) || empty($billing->token)): ?>
                <script>
                    var shop_key = '<?php echo isset($billing->key) ? $billing->key : ''; ?>';
                </script>
                <div class="ksm-update">
                    <p>Не все функции работают - <a class="ksm-show-billing-info" href="#">Исправить</a></p>
                </div>
	        <?php endif; ?>
			<?php if (!empty($current_extension)): ?>
				<a href="index.php?option=com_ksen&view=settings&extension=<?php echo $current_extension; ?>" class="prefs"><span></span></a>
				<a href="javascript:void(0);" class="info js-info" rel="<?php echo $current_extension; ?>"><span></span></a>
				<a href="javascript:void(0);" class="quest"><span></span></a>
	        <?php endif; ?>
        </div>
	<?php endif;?>
	<?php if(!$is_panel): ?>
	<div id="cat-menu">
		<ul>
			<li class="ksenmart-mainmenu-back">
				<a href="index.php?option=com_ksen&view=panel&extension=<?php echo $current_extension; ?>"></a>		
			</li>		
			<?php foreach($widgets as $i => $widget):?>
				<li class="ksenmart-mainmenu-child-component">
					<a class="<?php echo ($widget->name==$current_widget->name?'active':''); ?>" href="<?php echo $widget->href; ?>">
						<?php echo JText::_('ks_' . strtoupper($widget->name)); ?>
					</a>
				</li>
				<?php if ($i > 3 && count($widgets) > 5): ?>
					<li class="ksenmart-mainmenu-more dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<?php echo JText::_('mod_ks_mainmenu_more'); ?>
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu scroll-menu pull-right">
							<?php foreach($widgets as $i => $widget):?>
								<?php if ($i < 5) continue; ?>
								<li><a class="no-dropdown <?php echo ($widget->name==$current_widget->name?'active':''); ?>" href="<?php echo $widget->href; ?>"><?php echo JText::_('ks_' . strtoupper($widget->name)); ?></a></li>
							<?php endforeach;?>
						</ul>
					</li>				
					<?php break; ?>
				<?php endif; ?>
			<?php endforeach;?>
			<div class="clr"></div>
		</ul>
        <a href="index.php?option=com_ksen&view=settings&extension=<?php echo $current_extension; ?>" class="prefs"><span></span></a>
        <a href="javascript:void(0);" class="info js-info" rel="<?php echo $current_extension; ?>"><span></span></a>
	</div>	
	<?php endif;?>
</div>