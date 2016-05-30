<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-comments">
	<h2><?php echo JText::_('KSM_REVIEWS_LIST_PATH_TITLE'); ?></h2>
	<div class="ksm-comments-items">
		<?php if (!empty($this->rows)):?>
			<?php foreach($this->rows as $comment):?>
				<?php echo $this->loadTemplate('item', null, array('review' => $comment)); ?>
			<?php endforeach;?>
		<?php else:?>
			<?php $this->loadTemplate('no_comments');?>
		<?php endif;?>
	</div>
	<?php if (!empty($this->pagination)):?>
		<div class="ksm-pagination">
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>	
	<?php endif;?>
</div>