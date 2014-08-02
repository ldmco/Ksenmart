<?php

	function modChrome_span3($module, &$params, &$attribs){
		$headerLevel = isset($attribs['headerLevel']) ? (int) $attribs['headerLevel'] : 3;
		if (!empty ($module->content)) { ?>
			<div class="span3<?php echo htmlspecialchars($params->get('moduleclass_sfx')); ?>">
			<?php if ($module->showtitle) { ?> 
				<h<?php echo $headerLevel; ?>><?php echo $module->title; ?></h<?php echo $headerLevel; ?>>
				<?php } ?> 
			<?php echo $module->content; ?>
			</div>
	<?php }
	}

	function modChrome_span4($module, &$params, &$attribs){
		$headerLevel = isset($attribs['headerLevel']) ? (int) $attribs['headerLevel'] : 3;
		if (!empty ($module->content)) { ?>
			<div class="span4<?php echo htmlspecialchars($params->get('moduleclass_sfx')); ?>">
			<?php if ($module->showtitle) { ?> 
				<h<?php echo $headerLevel; ?>><?php echo $module->title; ?></h<?php echo $headerLevel; ?>>
				<?php } ?> 
			<?php echo $module->content; ?>
			</div>
	<?php }
	}
	
	function modChrome_span1($module, &$params, &$attribs){
		$headerLevel = isset($attribs['headerLevel']) ? (int) $attribs['headerLevel'] : 3;
		if (!empty ($module->content)) { ?>
			<div class="span1<?php echo htmlspecialchars($params->get('moduleclass_sfx')); ?>">
			<?php if ($module->showtitle) { ?> 
				<h<?php echo $headerLevel; ?>><?php echo $module->title; ?></h<?php echo $headerLevel; ?>>
				<?php } ?> 
			<?php echo $module->content; ?>
			</div>
	<?php }
	}

	function modChrome_collapse($module, &$params, &$attribs){
		$price_less = JRequest::getVar('price_less', null);
		$headerLevel = isset($attribs['headerLevel']) ? (int) $attribs['headerLevel'] : 3;
		if (!empty ($module->content)) { ?>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse_<?php echo $module->id; ?>">
						<?php echo $module->title; ?>
					</a>
				</div>
				<div id="collapse_<?php echo $module->id; ?>" class="accordion-body collapse<?php if(isset($price_less)){echo' in';}elseif($params->get('collapseIn')){echo' in';} ?>">
					<div class="accordion-inner">
						<?php echo $module->content; ?>
					</div>
				</div>
			</div>
	<?php } 
	}
