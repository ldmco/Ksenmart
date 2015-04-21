<?php defined('_JEXEC') or die; ?>

<?php if (!empty($this->pagination)): ?>
	<div class="pagination pagination-centered">
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
<?php endif; ?>