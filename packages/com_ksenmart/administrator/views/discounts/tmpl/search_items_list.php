<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="top clearfix">
    <div class="drag">
        <form id="add-form">
            <div class="drop"><?php echo JText::_('ksm_discounts_add_search_string')?></div>
            <input type="hidden" name="items_tpl" value="<?php echo $this->state->get('items_tpl');?>">
            <input type="hidden" name="items_to" value="<?php echo $this->state->get('items_to');?>">
            <input type="hidden" name="task" value="discounts.get_search_items_html">
        </form>
    </div>
</div>
<table class="sortable-helper" cellspacing="0">
</table>
<table class="cat" width="100%" cellspacing="0">
    <thead>
    <tr>
        <th class="name stretch sort-handler" align="left"><span class="sort_field" rel="title"><?php echo JText::_('ksm_discounts_discount_name')?></span></th>
        <th class="discount_type"><?php echo JText::_('ksm_discounts_discount_type')?></th>
        <th class="sort"><span class="sort_field" rel="ordering"><?php echo JText::_('ksm_ordering')?></span></th>
        <th class="stat"><?php echo JText::_('ksm_status')?></th>
        <th class="add"></th>
    </tr>
    </thead>
    <tbody>
    <?php if (count($this->items)>0):?>
        <?php foreach($this->items as $item):?>
            <?php $this->item=&$item;?>
            <?php echo $this->loadTemplate('item_form');?>
        <?php endforeach;?>
    <?php else:?>
        <?php echo $this->loadTemplate('no_items');?>
    <?php endif;?>
    </tbody>
</table>
<div class="pagi"></div>