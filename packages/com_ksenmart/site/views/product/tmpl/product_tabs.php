<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-product-tabs">
	<ul class="ksm-product-tabs-nav">
		<?php echo $this->loadTemplate('tabs_nav','product');?>
	</ul>
	<div class="ksm-product-tabs-contents">
		<?php echo $this->loadTemplate('tabs_contents','product');?>
	</div>
</div>