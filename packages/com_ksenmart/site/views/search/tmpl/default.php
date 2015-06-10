<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="search_page clearfix">
    <h2 class="title"><?php echo JText::_('ksm_search_results'); ?></h2>
    <?php if($this->products || $this->cat_search || $this->manufacture_search){ ?>
    <div class="search_info lead">
        <?php echo JText::_('ksm_search_results_searched'); ?> 
		<?php echo !empty($this->products) ? JText::sprintf('ksm_search_results_products', count($this->products)) : ''; ?>
		<?php echo !empty($this->cat_search) ? ', '.JText::sprintf('ksm_search_results_categories', count($this->cat_search)) : ''; ?>
		<?php echo !empty($this->manufacture_search) ? ', '.JText::sprintf('ksm_search_results_manufacturers', count($this->manufacture_search)) : ''; ?>
	</div>
    <?php } ?>
    <?php echo $this->loadTemplate('cat_search'); ?>
    <?php echo $this->loadTemplate('manufacture_search'); ?>
    <?php echo $this->loadTemplate('results'); ?>
</div>