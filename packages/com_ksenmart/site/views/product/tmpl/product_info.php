<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<div class="ksm-product-info">
	<?php if (!empty($this->product->product_code) || $this->product->in_stock > 0): ?>
		<div class="ksm-product-info-row">
			<?php if($this->product->in_stock > 0): ?>
				<label class="ksm-product-info-stock"><?php echo JText::_('KSM_PRODUCT_IN_STOCK_YES'); ?></label>
			<?php endif; ?>
			<?php if(!empty($this->product->product_code)): ?>
				<div class="ksm-product-info-row-code">
					<?php echo JText::_('KSM_PRODUCT_ARTICLE'); ?>
					&nbsp;
					<?php echo $this->product->product_code; ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php if (!empty($this->product->introcontent)): ?>
		<div class="ksm-product-info-row ksm-product-introtext">
			<?php echo html_entity_decode($this->product->introcontent); ?>
		</div>
	<?php endif; ?>
	<?php echo $this->loadTemplate('properties'); ?>
	<?php if(!empty($this->product->manufacturer)): ?>
		<div class="ksm-product-info-row">
			<label class="ksm-product-info-row-label"><?php echo JText::_('KSM_PRODUCT_MANUFACTURER'); ?></label>
			<div class="ksm-product-info-row-control">
				<a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&manufacturers[]='.$this->product->manufacturer->id.'&Itemid='.KSSystem::getShopItemid())?>"><?php echo $this->product->manufacturer->title?></a>
			</div>
		</div>
	<?php endif; ?>
	<?php if(!empty($this->product->tags->itemTags)): ?>
		<div class="ksm-product-info-row">
			<label class="ksm-product-info-row-label"><?php echo JText::_('KSM_PRODUCT_TAG'); ?></label>
			<div class="ksm-product-info-row-control">
				<?php echo JLayoutHelper::render('joomla.content.tags', $this->product->tags->itemTags); ?>
			</div>
		</div>
	<?php endif; ?>
	<?php if(isset($this->product->manufacturer->country) && count($this->product->manufacturer->country)>0): ?>
		<div class="ksm-product-info-row">
			<label class="ksm-product-info-row-label"><?php echo JText::_('KSM_PRODUCT_COUNTRY'); ?></label>
			<div class="ksm-product-info-row-control">
				<a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&countries[]='.$this->product->manufacturer->country->id.'&Itemid='.KSSystem::getShopItemid())?>"><?php echo $this->product->manufacturer->country->title?></a>
			</div>
		</div>
	<?php endif; ?>
</div>