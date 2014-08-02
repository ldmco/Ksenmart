<?php defined('_JEXEC') or die; ?>
<?php JHtml::stylesheet('mod_ksm_news/default.css', false, true, false); ?>
<li class="module_events <?php echo $moduleclass_sfx; ?>">
	<?php foreach($events as $event): ?>
		<div class="event__item">
			<div class="item-title">
				<a href="<?php echo $event->link; ?>" title="<?php echo $event->title; ?>"><?php echo $event->title; ?></a>
			</div>
			<div class="item-desc"><?php echo $event->introtext; ?></div>
		</div>
	<?php endforeach; ?>
</li>