<?php defined('_JEXEC') or die; ?>
<?php $user = JFactory::getUser(); ?>
<?php if(!empty($discounts)){ ?>
   <?php foreach($discounts as $discount){ ?>
   <div class="km-discounts">
   	   <div class="km-discount">	
		   <h1><?php echo $discount->title; ?></h1>
		   <?php echo $discount->content; ?>
	   </div>
   </div>
   <?php } ?>
<?php } ?>