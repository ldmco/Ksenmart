<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<?php if(!empty($this->product->content)){ ?>
    <li class="ksm-product-tab-nav active"><a href="#tab1"><?php echo JText::_('KSM_PRODUCT_TABS_TAB1'); ?></a></li>
<?php } ?>
<li class="ksm-product-tab-nav <?php echo empty($this->product->content)?'active':''; ?>"><a href="#tab3"><?php echo JText::_('KSM_PRODUCT_TABS_TAB2'); ?></a></li>
<?php if ($this->params->get('show_comment_form') == 1){ ?>
    <li class="ksm-product-tab-nav"><a href="#tab4"><?php echo JText::_('KSM_PRODUCT_TABS_TAB3'); ?></a></li>
<?php } ?>
