<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<?php if (!empty($this->product->product_code)): ?>
<div class="control-group">
	<label class="control-label"><?php echo JText::_('KSM_PRODUCT_ARTICLE'); ?></label>
	<div class="controls">
		<span class="article muted"><?php echo $this->product->product_code; ?></span>
	</div>
</div>
<?php endif; ?>
<?php if (!empty($this->product->introcontent)): ?>
<div class="control-group">
	<label class="control-label"><?php echo JText::_('KSM_PRODUCT_MINIDESC'); ?></label>
	<div class="controls">
		<div class="minidesc"><?php echo html_entity_decode($this->product->introcontent)?></div>
	</div>
</div>
<?php endif; ?>
<div class="control-group">
	<label class="control-label"><?php echo $this->childs_title; ?></label>
	<div class="controls">
		<select class="sel" id="property_childs" required="true">
			<option value=""><?php echo JText::_('KSM_PRODUCT_PROPERTY_CHOOSE'); ?></option>
			<?php foreach($this->childs_titles as $childs_title):?>
			<option value="<?php echo JRoute::_('index.php?option=com_ksenmart&view=product&id='.$childs_title->id.':'.$childs_title->alias.'&Itemid=' . KSSystem::getShopItemid()); ?>" <?php echo ($childs_title->id==$this->product->id?'selected':'')?>><?php echo $childs_title->title?></option>
			<?php endforeach;?>
		</select>			
	</div>
</div>
<?php echo $this->loadTemplate('properties','product'); ?>       
<?php if(!empty($this->product->manufacturer)): ?>
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('KSM_PRODUCT_MANUFACTURER'); ?></label>
		<div class="controls">
			<span><a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&manufacturers[]='.$this->product->manufacturer->id.'&Itemid=' . KSSystem::getShopItemid() . '&clicked=manufacturers'); ?>"><?php echo $this->product->manufacturer->title?></a></span>
		</div>
	</div>
<?php endif; ?>
<?php if(!empty($this->product->tag)): ?>
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('KSM_PRODUCT_TAG'); ?></label>
		<div class="controls">
			<span><a href="javascript:void(0);"><?php echo $this->product->tag?></a></span>
		</div>
	</div>
<?php endif; ?>
<?php if(isset($this->product->manufacturer->country) && count($this->product->manufacturer->country) > 0): ?>
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('KSM_PRODUCT_COUNTRY'); ?></label>
		<div class="controls">
			<span><a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&countries[]='.$this->product->manufacturer->country->id.'&Itemid=' . KSSystem::getShopItemid() . '&clicked=countries'); ?>"><?php echo $this->product->manufacturer->country->title?></a></span>
		</div>
	</div>
<?php endif; ?>