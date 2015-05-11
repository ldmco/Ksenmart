<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<?php JHtml::stylesheet('mod_ksm_news/default.css', false, true, false); ?>
<li class="module_events <?php echo $moduleclass_sfx; ?>">
	<?php foreach($events as $event): ?>
		<div class="event__item">
			<div class="item-title">
				<?php if(isset($event->params->show_title) && $event->params->show_title): ?>
					<?php if(!empty($event->params->url_from_title)): ?>
						<?php $event->link = $event->params->url_from_title; ?>
					<?php endif; ?>
					<a href="<?php echo $event->link; ?>" title="<?php echo $event->title; ?>" target="_blank"><?php echo $event->title; ?></a>
				<?php endif; ?>
			</div>
			<div class="item-desc"><?php echo $event->introtext; ?></div>
		</div>
	<?php endforeach; ?>
</li>