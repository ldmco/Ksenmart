<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-catalog-sortlinks-layouts">
	<div class="ksm-catalog-sortlinks">
		<label><?php echo JText::_('KSM_SORT_BY'); ?></label>
		<?php foreach($this->sort_links as $type => $sort_link): ?>
		<div class="ksm-catalog-sortlink ksm-catalog-sortlink-<?php echo $type; ?>"><?php echo $sort_link['link']; ?></div>
		<?php endforeach; ?>
	</div>
	<div class="ksm-catalog-layouts">
        <div class="ksm-catalog-layout ksm-catalog-layout-grid <?php echo $this->layout_view == 'grid' ? ' active' : ''; ?>">
            <a data-layout="grid"></a>
        </div>
        <div class="ksm-catalog-layout ksm-catalog-layout-list_ext <?php echo $this->layout_view == 'list_ext' ? ' active' : ''; ?>">
            <a data-layout="list_ext"></a>
        </div>
        <div class="ksm-catalog-layout ksm-catalog-layout-list <?php echo $this->layout_view == 'list' ? ' active' : ''; ?>">
            <a data-layout="list"></a>
        </div>		
    </div>
</div>