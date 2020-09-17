<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<?php if (count($this->related) > 0){ ?>
<div class="ksm-product-related ksm-catalog ksm-block">
	<h2><?php echo JText::_('KSM_RELATED_PRODUCT_TITLE'); ?></h2>
	<div class="ksm-catalog-items ksm-catalog-items-grid">
		<?php foreach($this->related as $product){ ?>
            <?php echo $this->loadOtherTemplate('item', 'default', 'catalog', array('product' => $product, 'params' => $this->params)); ?>
		<?php } ?>
	</div>	
</div>
<?php } ?>