<? defined( '_JEXEC' ) or die( '=;)' ); ?>
<dl class="dl-horizontal">
<?php 
foreach($this->product->properties as $property) {
	switch($property->type) {
		case 'text':
			if(isset($property->values[0]->title) && $property->values[0]->title != ''){ ?>
			<dt><?php echo $property->title; ?></dt>
            <dd><?php echo $property->values[0]->title; ?>&nbsp;<?php echo $property->finishing; ?></dd>								
			<? }
			break;
		case 'select':
			if(count($property->values) == 1) {
			$value = array_pop($property->values); ?>
			<dt><?php echo $property->title; ?></dt>
            <dd><?php echo $value->title; ?>&nbsp;<?php echo $property->finishing; ?></dd>								
			<? }
			break;
		case 'radio':
			if(count($property->values) == 1) {
			$value = array_pop($property->values); ?>
			<dt><?php echo $property->title; ?></dt>
            <dd><?php echo $value->title; ?>&nbsp;<?php echo $property->finishing; ?></dd>								
			<? }
			break;								
	}
}
?>
</dl>