<?php defined( '_JEXEC' ) or die; ?>
<div id="short_description_block" class="form-horizontal">
	<div id="short_description_content" class="rte align_justify">
		<?php if ($this->product->introcontent != '') {?>
		<div class="control-group">
			<h1>Коротко о главном:</h1>
			<div>
				<?php echo $this->product->introcontent; ?>
			</div>
		</div>
		<?php } ?>			
		<? if ($this->product->product_code!=''){ ?>
		<div class="control-group">
			<label class="control-label">Артикул:</label>
			<div class="controls">
				<label class="control-label"><?php echo $this->product->product_code; ?></label>
			</div>
		</div>
		<? } ?>
		<?php if (count($this->product->manufacturer) > 0){?>
			<div class="control-group">
				<label class="control-label">Производитель:</label>
				<div class="controls">
					<label class="control-label"><a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&manufacturers[]='.$this->product->manufacturer->id.'&Itemid='.KSSystem::getShopItemid().'&clicked=manufacturers')?>"><?php echo $this->product->manufacturer->title?></a></label>
				</div>
			</div>
		<?}?>
		<?php if ($this->product->tag != ''){?>
			<div class="control-group">
				<label class="control-label">Назначение:</label>
				<div class="controls">
					<label class="control-label"><?php echo $this->product->tag?></a></label>
				</div>
			</div>
		<? } ?>	
		<? if(isset($this->product->manufacturer->country) && count($this->product->manufacturer->country)>0){ ?>
			<div class="control-group">
				<label class="control-label">Страна:</label>
				<div class="controls">
					<label class="control-label"><a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&countries[]='.$this->product->manufacturer->country->id.'&Itemid='.KSSystem::getShopItemid().'&clicked=countries')?>"><?php echo $this->product->manufacturer->country->title?></a></label>
				</div>
			</div>
		<? } ?>
	</div>
</div>