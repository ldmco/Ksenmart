<?php defined('_JEXEC') or die; ?>
<?php
	$is_admin = KSUsers::is_admin();
?>
<?php echo $this->loadTemplate('toplinks', 'product');?>
<?php echo $this->loadTemplate('title', 'product');?>		
<div id="primary_block" class="clearfix">
		<!--ADD CUSTOM CLOUD ZOOM!!!-->
<!-- Call quick start function. -->
<!-- right infos-->
<div class="row">
	<div id="pb-right-column" class="span4">
		<?php echo $this->loadTemplate('gallery', 'product'); ?>
	</div>
	<!-- left infos-->
	<div id="pb-left-column" class="span5">
        <?php echo $this->loadTemplate('info', 'product'); ?>
		<!-- add to cart form-->
		<form action="<?php echo $this->product->add_link_cart; ?>" method="post" id="buy_block" class="form-horizontal">
			<div class="options">
				<?php echo $this->loadTemplate('properties', 'product'); ?>
			</div>
            <?php echo $this->loadTemplate('prices', 'product'); ?>
		</form>
	</div>
</div>
</div>
<?php echo $this->loadTemplate('tabs', 'product'); ?>
<?php echo $this->loadTemplate('sets', 'product'); ?>
<?php if (count($this->related) > 0){ ?>
    <?php echo $this->loadTemplate('related', 'product'); ?>
<?php } ?>