<?php
/**
 * @copyright   Copyright (C) 2016. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if (!empty($this->category->edit) && $this->category->edit) { ?>
	<div class="ksm-catalog-edit well">
		<a class="ksm-catalog-edit-link km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo $this->category->editlink; ?>"><span class="icon-pencil-2"></span><?php echo JText::_('KSM_CATEGORY_EDIT') ?></a>
		<a class="ksm-catalog-add-category-link km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo $this->category->addcategorylink; ?>"><span class="icon-plus "></span><?php echo JText::_('KSM_CATEGORY_ADD') ?></a>
		<a class="ksm-catalog-add-product-link km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo $this->category->addproductlink; ?>"><span class="icon-plus "></span><?php echo JText::_('KSM_PRODUCT_ADD') ?></a>
	</div>
<?php } ?>

