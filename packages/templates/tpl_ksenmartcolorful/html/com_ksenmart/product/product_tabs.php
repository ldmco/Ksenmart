<?php defined('_JEXEC') or die; ?>
<aside class="tabbable noTransition descs">
	<ul class="nav nav-tabs">
		<?php if(!empty($this->product->content)): ?>
		<li class="active"><a href="#tab1" data-toggle="tab"><?php echo JText::_('KSM_PRODUCT_TABS_TAB1'); ?></a></li>
		<?php endif; ?>
		<li <?php echo empty($this->product->content)?' class="active"':''; ?>><a href="#tab3" data-toggle="tab"><?php echo JText::_('KSM_PRODUCT_TABS_TAB2'); ?></a></li>
		<?php if ($this->params->get('show_comment_form', 1)): ?>
		<li><a href="#tab4" data-toggle="tab"><?php echo JText::_('KSM_PRODUCT_TABS_TAB3'); ?></a></li>
		<?php endif; ?>
	</ul>
	<div class="tab-content">
		<?php if(!empty($this->product->content)): ?>
		<div class="tab-pane active" id="tab1">
			<?php echo html_entity_decode($this->product->content, ENT_QUOTES, "UTF-8"); ?>
		</div>
		<?php endif; ?>
		<div class="tab-pane reviews<?php echo empty($this->product->content)?' active' : ''; ?>" id="tab3">
			<?php echo $this->loadTemplate('comments','product'); ?>			
		</div>
		<div class="tab-pane" id="tab4">
		<?php if ($this->params->get('show_comment_form', 1)): ?>
			<?php echo $this->loadTemplate('comment_form','product'); ?>			
		<?php endif; ?>
		</div>
	</div>
</aside>