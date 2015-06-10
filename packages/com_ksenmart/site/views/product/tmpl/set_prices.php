<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<?php if ($this->params->get('only_auth_buy', 0) == 0 || ($this->params->get('only_auth_buy', 0) != 0 && JFactory::getUser()->id != 0)):?>
<div class="prices">
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('KSM_PRODUCT_SET_PRICE_LABEL'); ?></label>
		<div class="controls">
			<span class="price">
				<?php echo $this->product->val_price; ?>
				<sup class="text-error"><?php echo $this->product->val_old_price; ?></sup>
			</span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('KSM_PRODUCT_SET_PRICE_ECONOMY'); ?></label>
		<div class="controls">
			<em class="price text-success"><?php echo $this->product->val_diff_price; ?></em>
		</div>
	</div>
</div>
<?php endif;?>