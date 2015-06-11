<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<?php if (($this->params->get('only_auth_buy',0)==0 || ($this->params->get('only_auth_buy',0)!=0 && JFactory::getUser()->id!=0)) && $this->params->get('catalog_mode',0) == 0):?>
<div class="to-order lead text-center">
	<a href="javascript:void(0);" class="link_b_border lrg"><?php echo JText::_('KSM_PRODUCT_SET_BYLINK_TEXT'); ?></a>
</div>
<?php endif;?>