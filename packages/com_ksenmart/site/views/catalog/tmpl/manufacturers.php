<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-brands ksm-catalog">
    <div class="ksm-brands-pagination">
        <ul>
            <li class="active"><a href="javascript:void(0);" data-letter="all" title="<?php echo JText::_('KSM_CATALOG_MANUFACTURERS_SHOW_ALL'); ?>"><?php echo JText::_('KSM_CATALOG_MANUFACTURERS_SHOW_ALL'); ?></a></li>
            <?php foreach($this->brands as $key => $letter): ?>
            <li><a href="javascript:void(0);" data-letter="<?php echo $key; ?>" title="<?php echo $key; ?>"><?php echo $key; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="ksm-brands-items">
        <?php foreach($this->brands as $key => $letter): ?>
        	<ul class="ksm-brands-item" data-brands-letter="<?php echo $key; ?>">
                <li class="ksm-brands-item-header"><?php echo $key; ?></li>
				<?php foreach($letter as $brand): ?>
				<li class="ksm-brands-item-row">
					<a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&manufacturers[]='.$brand->id); ?>" title="<?php echo $brand->title; ?>">
						<?php echo $brand->title; ?>
					</a>
				</li>
				<?php endforeach; ?>
        	</ul>
        <?php endforeach; ?>
    </div>
</div>