<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JHTML::_( 'behavior.modal' );
?>
<?php echo $this->loadTemplate('items_list_top');?>	
<?php echo $this->loadTemplate('items_list');?>	
<script>
var SeoTextsList=new KMList({
	'view':'seo',
	'object':'SeoTextsList',
	'limit':<?php echo $this->state->get('list.limit');?>,
	'limitstart':<?php echo $this->state->get('list.start');?>,
	'total':<?php echo $this->total;?>,
	'order_type':'<?php echo $this->state->get('order_type');?>',
	'order_dir':'<?php echo $this->state->get('order_dir');?>',
	'table':'seotexts',
	'sortable':false
});
</script>