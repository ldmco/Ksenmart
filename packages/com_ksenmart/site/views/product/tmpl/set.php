<?php defined( '_JEXEC' ) or die; ?>
<article>
	<div class="top row-fluid">
		<?php echo $this->loadTemplate('title','product');?>	
		<?php echo $this->loadTemplate('toplinks','product');?>	
	</div>
	<div class="row-fluid unit top_prd_block">
		<?php echo $this->loadTemplate('gallery','product');?>	
		<div class="info span6 form-horizontal top100">
			<?php echo $this->loadTemplate('info');?>	
			<?php echo $this->loadTemplate('prices');?>
			<?php echo $this->loadTemplate('buylink');?>
		</div>
	</div>
	<?php echo $this->loadTemplate('social','product');?>
	<?php echo $this->loadTemplate('related');?>
	<?php echo $this->loadTemplate('tabs','product');?>	
</article>