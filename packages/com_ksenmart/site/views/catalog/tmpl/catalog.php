<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-catalog">
	<h2><?php echo JText::_('KSM_CATALOG_TITLE');?></h2>
    <?php echo $this->loadTemplate('sortlinks', 'default'); ?>
    <div class="ksm-catalog-items ksm-catalog-items-<?php echo $this->layout_view; ?>" data-layout="<?php echo $this->layout_view; ?>">
        <?php if(!empty($this->rows)): ?>
			<?php foreach($this->rows as $product): ?>
				<?php echo $this->loadTemplate('item', 'default', array('product' => $product, 'params' => $this->params)); ?>
			<?php endforeach; ?>
        <?php else: ?>
			<?php echo $this->loadTemplate('noproducts', 'default'); ?>
        <?php endif; ?>
    </div>
    <?php echo $this->loadTemplate('pagination', 'default'); ?>
</div>