<?php defined( '_JEXEC' ) or die; ?>
<div class="row <?php echo $element['name'];?>">
	<div class="ksm-slidemodule-<?php echo $element['type'];?> slide_module <?php echo $element['class'];?>">
		<div class="module-head">
			<label><?php echo JText::_($element['label']);?></label>
			<a class="show_module_content" onclick="shSlideModuleContent(this);return false;"></a>
		</div>
		<div class="module-content no-favorites">
			<div class="lists">
				<div class="row">
					<?php echo $html;?>
				</div>
			</div>
		</div>
	</div>
</div>