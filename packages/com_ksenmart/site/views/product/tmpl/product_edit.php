<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$user   = JFactory::getUser();
$isroot = $user->authorise('core.admin');
?>
<?php if ($isroot): ?>
	<div class="ksm-product-edit well">
		<a class="ksm-product-edit-link km-modal" rel='{"x":"90%","y":"90%"}'
		   href="/administrator/index.php?option=com_ksenmart&view=catalog&layout=product&id=<?php echo $this->product->id; ?>&tmpl=component"><span class="icon-pencil-2"></span><?php echo JText::_('KSM_PRODUCT_EDIT'); ?></a>
	</div>
<?php endif; ?>
