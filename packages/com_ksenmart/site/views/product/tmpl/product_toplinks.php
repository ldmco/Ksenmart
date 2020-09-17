<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<a data-prd_id="<?php echo $this->product->id; ?>" class="ksm-product-to-fav"><?php echo JText::_('KSM_PRODUCT_TOFAV_TITLE'); ?></a>
<a href="<?php echo $this->product->links[0]; ?>" class="ksm-product-prev-link"></a>
<a href="<?php echo $this->product->links[1]; ?>" class="ksm-product-next-link"></a>
