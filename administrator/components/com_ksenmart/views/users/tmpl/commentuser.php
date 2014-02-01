<?php
defined( '_JEXEC' ) or die;
?>
<div class="position">
	<div class="col1">
		<div class="img"><img alt="" src="<?php echo $this->item->small_img;?>"></div>
		<div class="name"><?php echo $this->item->name;?><br><?php echo $this->item->email;?></div>
	</div>
	<a href="#" class="del"></a>
	<input type="hidden" name="jform[user_id]" id="jformuser_id" value="<?php echo $this->item->id;?>">
</div>