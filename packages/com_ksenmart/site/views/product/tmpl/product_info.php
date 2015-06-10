<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<? if (!empty($this->product->product_code)){ ?>
<div class="control-group">
	<label class="control-label"><?php echo JText::_('KSM_PRODUCT_ARTICLE'); ?></label>
	<div class="controls">
		<span class="article muted"><?php echo $this->product->product_code; ?></span>
	</div>
</div>
<? } ?>
<?php if (!empty($this->product->introcontent)) {?>
<div class="control-group">
	<label class="control-label"><?php echo JText::_('KSM_PRODUCT_MINIDESC'); ?></label>
	<div class="controls">
		<div class="minidesc"><?php echo html_entity_decode($this->product->introcontent)?></div>
	</div>
</div>
<?php } ?>
<?php echo $this->loadTemplate('properties'); ?>       
<?php if(!empty($this->product->manufacturer)){?>
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('KSM_PRODUCT_MANUFACTURER'); ?></label>
		<div class="controls">
			<span><a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&manufacturers[]='.$this->product->manufacturer->id.'&Itemid='.KSSystem::getShopItemid())?>"><?php echo $this->product->manufacturer->title?></a></span>
		</div>
	</div>
<?php } ?>
<?php if(!empty($this->product->tags->itemTags)){ ?>
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('KSM_PRODUCT_TAG'); ?></label>
		<div class="controls">
			<?php echo JLayoutHelper::render('joomla.content.tags', $this->product->tags->itemTags); ?>
		</div>
	</div>
<?php } ?>
<?php if(isset($this->product->manufacturer->country) && count($this->product->manufacturer->country)>0){ ?>
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('KSM_PRODUCT_COUNTRY'); ?></label>
		<div class="controls">
			<span><a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&countries[]='.$this->product->manufacturer->country->id.'&Itemid='.KSSystem::getShopItemid())?>"><?php echo $this->product->manufacturer->country->title?></a></span>
		</div>
	</div>
<?php } ?>	