<?php
//no direct accees
defined ('_JEXEC') or die ('restricted access');

$user = JFactory::getUser();
$input 	= JFactory::getApplication()->input;
$view 	= $input->get('view', NULL, 'STRING');
$option = $input->get('option', NULL, 'STRING');
$layout = $input->get('layout', NULL, 'STRING');

if ($user->authorise('core.manage', 'com_ksenmart')) { ?>

	<ul id="sp-pagebuiler-menu" class="nav <?php echo ($layout == 'edit') ? 'disabled': ''; ?>">
		<li class="dropdown <?php echo ($option == 'com_ksenmart' && $layout != 'edit') ? 'active': ''; ?> <?php echo ($layout == 'edit') ? 'disabled': ''; ?> ">

			<?php if($layout == 'edit') { ?>
				<a class="no-dropdown">
					<?php echo JText::_('MOD_MENU_COM_KSENMART');?>
				</a>
			<?php } else{ ?>
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<?php echo JText::_('MOD_MENU_COM_KSENMART');?> <span class="caret"></span>
				</a>
				<ul aria-labelledby="dropdownMenu" role="menu" class="dropdown-menu">
					<li <?php echo ($option == 'com_ksenmart' && ($view == 'orders') ) ? 'class="active"': '';?>>
						<a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=orders'); ?>">
							<?php echo JText::_('KS_ORDERS');?>
						</a>
					</li>
					<li <?php echo ($option == 'com_ksenmart' && ($view == 'catalog') ) ? 'class="active"': '';?>>
						<a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog'); ?>">
							<?php echo JText::_('KS_CATALOG');?>
						</a>
					</li>
					<li <?php echo ($option == 'com_ksenmart' && ($view == 'discounts') ) ? 'class="active"': '';?>>
						<a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=discounts'); ?>">
							<?php echo JText::_('KS_DISCOUNTS');?>
						</a>
					</li>
					<li <?php echo ($option == 'com_ksenmart' && ($view == 'payments') ) ? 'class="active"': '';?>>
						<a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=payments'); ?>">
							<?php echo JText::_('KS_PAYMENTS');?>
						</a>
					</li>
					<li <?php echo ($option == 'com_ksenmart' && ($view == 'shippings') ) ? 'class="active"': '';?>>
						<a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=shippings'); ?>">
							<?php echo JText::_('KS_SHIPPINGS');?>
						</a>
					</li>
					<li <?php echo ($option == 'com_ksen' && ($view == 'extension') ) ? 'class="active"': '';?>>
						<a href="<?php echo JRoute::_('index.php?option=com_ksen&view=settings&extension=com_ksenmart'); ?>">
							<?php echo JText::_('KS_SETTINGS');?>
						</a>
					</li>

				</ul>
			<?php } ?>
		</li>
	</ul>

	<?php
}
