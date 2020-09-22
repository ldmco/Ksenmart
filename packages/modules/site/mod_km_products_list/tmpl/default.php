<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-module-products-list ksm-block <?php echo $class_sfx?>">
	<h3><?php echo $module->title?></h3>
	<div class="ksm-module-products-list-items">
        <?php foreach($products as $product): ?>
    		<?php echo KSSystem::loadTemplate(array('product' => $product, 'params' => $km_params)); ?>
    	<?php endforeach; ?>
    </div>
</div>