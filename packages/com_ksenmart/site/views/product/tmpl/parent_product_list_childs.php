<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<?php foreach($this->childs_groups as $childs_group){ ?>
   <?php if (count($childs_group->products) > 0){ ?>
	<div class="ksm-product-parent-list-childs ksm-catalog">
		<h2><?php echo $childs_group->title?></h2>
		<div class="ksm-catalog-items ksm-catalog-items-grid">
			<?php foreach($childs_group->products as $product) { ?>
				<?php echo $this->loadOtherTemplate('item', 'default', 'catalog', array('product' => $product, 'params' => $this->params)); ?>
			<?php } ?>
		</div>	
	</div>
	<?php } ?>
<?php } ?>	