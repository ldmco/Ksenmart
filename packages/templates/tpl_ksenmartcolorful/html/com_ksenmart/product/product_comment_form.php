<?php
defined( '_JEXEC' ) or die( '=;)' );

$user = JFactory::getUser();
?>
<form method="post" id="comment_form" class="form-horizontal">
	<div class="form">
		<div class="control-group">
			<?php echo $this->reviewform->getLabel('comment_name'); ?>
			<div class="controls">
				<?php echo $this->reviewform->getInput('comment_name'); ?>
			</div>
		</div>
		<div class="control-group">
			<?php echo $this->reviewform->getLabel('comment_rate'); ?>
			<div class="controls">
				<?php echo $this->reviewform->getInput('comment_rate'); ?>
			</div>
		</div>	
		<div class="control-group">
			<?php echo $this->reviewform->getLabel('comment_comment'); ?>
			<div class="controls">
				<?php echo $this->reviewform->getInput('comment_comment'); ?>
			</div>
		</div>			
		<div class="control-group">
			<?php echo $this->reviewform->getLabel('comment_good'); ?>
			<div class="controls">
				<?php echo $this->reviewform->getInput('comment_good'); ?>
			</div>
		</div>	
		<div class="control-group">
			<?php echo $this->reviewform->getLabel('comment_bad'); ?>
			<div class="controls">
				<?php echo $this->reviewform->getInput('comment_bad'); ?>
			</div>
		</div>	
		<?php if (!$this->reviewform->getField('captcha')->hidden): ?>
		<div class="control-group">
			<?php echo $this->reviewform->getLabel('captcha'); ?>
			<div class="controls">
				<?php echo $this->reviewform->getInput('captcha'); ?>
			</div>
		</div>	
		<?php endif; ?>
		<div class="control-group">
			<div class="controls">
				<input type="submit" class="btn btn-success" value="<?php echo JText::_('KSM_PRODUCT_POST_A_REVIEW_BUTTON_TEXT'); ?>" />
			</div>
		</div>
	</div>
	<input type="hidden" name="task" value="product.add_comment" />
</form>	
