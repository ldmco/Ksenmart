<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHTML::_('behavior.modal');
?>
<div class="form">
    <div class="heading">
        <h3>
			<?php echo $this->title; ?>
        </h3>
        <div class="save-close">
            <input type="button" value="<?php echo JText::_('ks_add') ?>" class="save">
            <input type="button" class="close" onclick="parent.closePopupWindow();">
        </div>
    </div>
    <div class="edit">
        <div id="cat" class="add-relative">
            <div class="left-column">
                <div id="tree">
                    <form id="list-filters">
                        <ul>
							<?php echo KSSystem::loadModules('km-list-left') ?>
                        </ul>
                    </form>
                </div>
            </div>
            <div class="right-column">
                <div class="right-column-wra">
					<?php echo $this->loadTemplate('items_list'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var DiscountsList = new KMList({
        'view': 'discounts',
        'item_tpl': 'search_item_form',
        'no_items_tpl': 'search_no_items',
        'object': 'DiscountsList',
        'limit':<?php echo $this->state->get('list.limit');?>,
        'limitstart':<?php echo $this->state->get('list.start');?>,
        'total':<?php echo $this->total;?>,
        'order_type': '<?php echo $this->state->get('order_type');?>',
        'order_dir': '<?php echo $this->state->get('order_dir');?>',
        'table': 'discounts',
        'sortable': false
    });
</script>