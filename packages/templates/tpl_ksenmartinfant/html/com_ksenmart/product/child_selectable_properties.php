<?
defined( '_JEXEC' ) or die( '=;)' );
foreach($child->properties as $property)
{
	switch($property->type)
	{
		case 'checkbox':
			if (isset($property->values[0]) && $property->values[0]->title==1)
			{
			?>
			<div class="row">
				<div class="prop-name"><?=$property->title?>:</div><div class="prop"><input type="checkbox" name="property_<?=$child->id?>_<?=$property->id?>" value="1"></div>
			</div>	
			<?
			}			
			break;
		case 'select':
			if (count($property->values)>1)
			{
			?>
			<div class="row">
				<div class="prop-name"><?=$property->title?>:<span class="err"><?php echo JText::_('KSM_PRODUCT_SELECTABLE_FETURES_SELECT_VALUE');?></span></div>
				<div class="prop">
					<select class="sel" id="property_<?=$child->id?>_<?=$property->id?>" name="property_<?=$child->id?>_<?=$property->id?>">
					<option value="0">Выбрать</option>
					<?
					foreach($property->values as $value)
					{
					?>
					<option value="<?=$value->id?>"><?=$value->title?></option>
					<?
					}
					?>
					</select>	
					&nbsp;<?=$property->finishing?>	
				</div>
			</div>								
			<?								
			}
			break;
		case 'radio':
			if (count($property->values)>1)
			{
			?>
			<div class="row">
				<div class="prop-name"><?=$property->title?>:<span class="err"><?php echo JText::_('KSM_PRODUCT_SELECTABLE_FETURES_SELECT_VALUE')?></span></div>
				<div class="prop">
					<?
					foreach($property->values as $value)
					{
					?>
					<div class="custom"><input type="radio" name="property_<?=$child->id?>_<?=$property->id?>" value="<?=$value->id?>"><label><?=$value->title?>&nbsp;<?=$property->finishing?></label></div>
					<?
					}
					?>
				</div>
			</div>								
			<?								
			}
			break;								
	}
}
?>