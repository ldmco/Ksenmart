<?
defined( '_JEXEC' ) or die( '=;)' );
foreach($product->properties as $property)
{
	switch($property->type)
	{
		case 'checkbox':
			if (isset($property->values[0]) && $property->values[0]->title==1)
			{
			?>
			<p>
				<span><?=$property->title?>:</span><input type="checkbox" name="property_<?=$product->id?>_<?=$property->id?>" value="1">
			</p>	
			<?
			}			
			break;
		case 'select':
			if (count($property->values)>1)
			{
			?>
			<p class="row">
				<span style="margin-top: 5px;"><?=$property->title?>:<span class="err"><?php echo JText::_('KSM_PRODUCT_SELECTABLE_FETURES_SELECT_VALUE');?></span></span>
				<select class="sel" id="property_<?=$product->id?>_<?=$property->id?>" name="property_<?=$product->id?>_<?=$property->id?>">
				<option value="0">Выбрать</option>
				<?
				foreach($property->values as $value)
				{
				?>
				<option value="<?=$value->value_id?>"><?=$value->title?></option>
				<?
				}
				?>
				</select>	
				&nbsp;<?=$property->finishing?>	
			</p>								
			<?								
			}
			break;
		case 'radio':
			if (count($property->values)>1)
			{
			?>
			<p class="row">
				<span><?=$property->title?>:<span class="err"><?php echo JText::_('KSM_PRODUCT_SELECTABLE_FETURES_SELECT_VALUE')?></span></span>
				<?
				foreach($property->values as $value)
				{
				?>
				<div class="custom"><input type="radio" name="property_<?=$product->id?>_<?=$property->id?>" value="<?=$value->value_id?>"><label><?=$value->title?>&nbsp;<?=$property->finishing?></label></div>
				<?
				}
				?>
			</p>								
			<?								
			}
			break;								
	}
}
?>