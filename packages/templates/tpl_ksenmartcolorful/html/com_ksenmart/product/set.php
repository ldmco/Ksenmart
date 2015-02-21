<?php defined( '_JEXEC' ) or die; ?>
<article class="unit sets row-fluid">
	<div class="top row-fluid">
		<?php echo $this->loadTemplate('title','product');?>	
		<?php echo $this->loadTemplate('toplinks','product');?>	
	</div>
	<div class="row-fluid top_prd_block">
		<?php echo $this->loadTemplate('gallery','product');?>	
		<div class="info" id="info">
			<form class="form-horizontal">
				<?php echo $this->loadTemplate('info');?>	
				<?php echo $this->loadTemplate('prices');?>
				<?php echo $this->loadTemplate('buylink');?>
			</form>
		</div>
	</div>
	<?php echo $this->loadTemplate('related');?>
	<?php echo $this->loadTemplate('tabs','product');?>	
</article>