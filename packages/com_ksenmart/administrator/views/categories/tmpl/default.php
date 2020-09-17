<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHTML::_('behavior.modal');
?>
<div class="clearfix panel">
    <div class="pull-left">
		<?php echo KSSystem::loadModules('ks-top-left'); ?>
    </div>
    <div class="pull-right">
		<?php echo KSSystem::loadModules('ks-top-right'); ?>
    </div>
    <div class="row-fluid">
		<?php echo KSSystem::loadModules('ks-top-bottom'); ?>
    </div>
</div>
<div id="center">
    <div id="cat">
        <div id="content">
			<?php echo $this->loadTemplate('items_list_top'); ?>
			<?php echo $this->loadTemplate('items_list'); ?>
        </div>
    </div>
</div>
<script>
    var CategoriesList = new KMList({
        'view': 'categories',
        'object': 'CategoriesList',
        'limit':999,
        'limitstart':0,
        'total':<?php echo $this->total;?>,
        'order_type': '<?php echo $this->state->get('order_type');?>',
        'order_dir': '<?php echo $this->state->get('order_dir');?>',
        'table': 'categories',
        'copy_button': true,
        'childs': true
    });
</script>