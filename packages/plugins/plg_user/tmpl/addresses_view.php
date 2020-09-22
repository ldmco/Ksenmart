<?php
defined('_JEXEC') or die;
?>
<div class="ksm-profile-addresses ksm-block">
	<?php foreach($view->addresses as $address): ?>
	<div class="ksm-profile-addresses-item">
		<span><?php echo $address; ?></span>	
	</div>
	<?php endforeach; ?>
</div>