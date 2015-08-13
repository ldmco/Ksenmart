<?php defined('_JEXEC') or die; ?>
<?php if ($category->parent_id > 0): ?>
	<?php $childrens[$category->id] = $category->id; ?>
<?php endif; ?>
<div class="accordion-group">
	<div class="accordion-heading">
		<a class="accordion-toggle collapsed" title="<?php echo $category->title; ?>" data-toggle="collapse" data-parent="#dropdownCat" href="#collapse_<?php echo $category->id; ?>"><?php echo $category->title; ?><b class="caret pull-right"></b></a>
	</div>
	<div id="collapse_<?php echo $category->id; ?>" class="accordion-body collapse">
		<div class="accordion-inner">
			<?php if ($category->children): ?>
				<?php showCategory($category->children); ?>
			<?php endif; ?>
			</ul>
		</div>
	</div>
</div>