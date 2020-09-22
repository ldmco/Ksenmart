<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-module-filter-selecteds" style="display:<?php echo (!empty($selected) ? 'block' : 'none'); ?>">
    <?php if (!empty($selected['manufacturers'])): ?>
        <div class="ksm-module-filter-selected-block">
            <h4>Производитель</h4>
            <?php foreach ($selected['manufacturers'] as $manufacturer): ?>
                <a class="ksm-module-filter-selected"
                   data-type="manufacturers"
                   data-item_id="<?php echo $manufacturer->id; ?>"
                   href="#"><?php echo $manufacturer->title; ?></a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($selected['properties'])): ?>
        <?php foreach ($selected['properties'] as $property): ?>
            <?php $count = 0; ?>
            <?php foreach($property->values as $value){ ?>
                <?php if(!$count): ?>
                    <div class="ksm-module-filter-selected-block">
                        <h4><?php echo $property->title; ?></h4>
                <?php endif; ?>
                        <a class="ksm-module-filter-selected"
                           data-type="property"
                           data-item_id="<?php echo $value->id; ?>"
                           href="#"><?php echo $value->title; ?></a>

                <?php $count++; ?>
            <?php } ?>
            <?php if ($count): ?>
                    </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <a class="ksm-module-filter-button-clear" href="#" ><?php echo JText::_('MOD_KM_FILTER_SELECTED_CLEAR')?></a>
</div>