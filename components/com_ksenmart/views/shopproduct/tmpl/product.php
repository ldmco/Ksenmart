<?php defined('_JEXEC') or die; ?>
<article class="unit row-fluid">
	<div class="top row-fluid">
		<?php echo $this->loadTemplate('title');?>		
		<?php echo $this->loadTemplate('toplinks');?>
	</div>
	<div class="row-fluid top_prd_block">
        <?php echo $this->loadTemplate('gallery'); ?>
		<div class="info span6">
			<form action="<?php echo $this->product->add_link_cart; ?>" method="post" class="form-horizontal">
				<?php echo $this->loadTemplate('info');?>	
				<?php echo $this->loadTemplate('prices');?>	
    			<input type="hidden" name="price" value="<?php echo $this->product->val_price_wou?>">	
    			<input type="hidden" name="id" value="<?php echo $this->product->id?>">	
    			<input type="hidden" name="product_packaging" class="product_packaging" value="<?php echo $this->product->product_packaging?>">
			</form>
		</div>
	</div>
	<div class="social pull-right">
		<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
		<div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,moimir,gplus"></div> 
	</div>
    <?php echo $this->loadTemplate('tabs'); ?>
    <?php if (count($this->product->sets) > 0) { ?>
        <?php echo $this->loadTemplate('sets'); ?>
	<?php } ?>
</article>
<?php if (count($this->related) > 0){ ?>
    <?php echo $this->loadTemplate('related'); ?>
<?php } ?>