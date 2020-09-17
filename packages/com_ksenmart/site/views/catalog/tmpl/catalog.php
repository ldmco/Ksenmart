<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$menu   = JFactory::getApplication()->getMenu();
$active = $menu->getActive();
if (!empty($active) && !empty($active->title)) {
	$title = $active->title;
} else {
	$title = JText::_('KSM_CATALOG_TITLE');
}
?>
<div class="ksm-catalog ksm-block">
    <h2><?php echo $title; ?></h2>
	<?php if ($this->params->get('show_categories_from_catalog') && !empty($this->categories)): ?>
		<?php echo $this->loadTemplate('categories', 'default'); ?>
	<?php endif; ?>
	<?php echo $this->loadTemplate('sortlinks', 'default'); ?>
    <div class="ksm-catalog-items ksm-catalog-items-<?php echo $this->layout_view; ?>"
         data-layout="<?php echo $this->layout_view; ?>">
		<?php if (!empty($this->rows)): ?>
			<?php foreach ($this->rows as $product): ?>
				<?php echo $this->loadTemplate('item', 'default', array('product' => $product, 'params' => $this->params)); ?>
			<?php endforeach; ?>
		<?php else: ?>
			<?php echo $this->loadTemplate('noproducts', 'default'); ?>
		<?php endif; ?>
    </div>
	<?php echo $this->loadTemplate('pagination', 'default'); ?>
</div>