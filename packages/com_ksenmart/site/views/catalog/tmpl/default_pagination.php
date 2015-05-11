<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<?php if (!empty($this->pagination)): ?>
	<div class="pagination pagination-centered">
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
<?php endif; ?>