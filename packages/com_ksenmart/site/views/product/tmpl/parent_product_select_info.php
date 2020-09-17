<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<div class="ksm-product-info">
	<?php if (!empty($this->product->introcontent)): ?>
		<div class="ksm-product-info-row">
			<?php echo html_entity_decode($this->product->introcontent); ?>
		</div>
	<?php endif; ?>
	<?php if (!empty($this->product->product_code)): ?>
		<div class="ksm-product-info-row">
			<label class="ksm-product-info-row-label"><?php echo JText::_('KSM_PRODUCT_ARTICLE'); ?></label>
			<div class="ksm-product-info-row-control">
				<?php echo $this->product->product_code; ?>
			</div>
		</div>
	<?php endif; ?>
	<div class="ksm-product-info-row">
		<label class="ksm-product-info-row-label"><?php echo $this->childs_title; ?></label>
		<div class="ksm-product-info-row-control">
			<select id="ksm-product-parent-childs-property" required="true">
				<option value=""><?php echo JText::_('KSM_PRODUCT_PROPERTY_CHOOSE'); ?></option>
				<?php foreach($this->childs_titles as $childs_title):?>
					<option value="<?php echo JRoute::_('index.php?option=com_ksenmart&view=product&id='.$childs_title->id.':'.$childs_title->alias.'&Itemid=' . KSSystem::getShopItemid()); ?>" <?php echo ($childs_title->id==$this->product->id?'selected':'')?>><?php echo $childs_title->title?></option>
				<?php endforeach;?>
			</select>			
		</div>
	</div>
	<?php echo $this->loadTemplate('properties','product'); ?>       
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