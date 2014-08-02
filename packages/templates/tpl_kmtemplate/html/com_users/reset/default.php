<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.5
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<div class="reset<?php echo $this->pageclass_sfx?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php endif; ?>

	<form id="user-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=reset.request'); ?>" method="post" class="form-validate form-horizontal">
		<?php foreach ($this->form->getFieldsets() as $fieldset){ ?>
		<p><?php echo JText::_($fieldset->label); ?></p>
			<?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field){//print_r($field); ?>
                <?php if($field->label !== ''){ ?>
            	<div class="control-group">
                    <div class="control-label">
                        <?php echo $field->label; ?>
                    </div>
            		<div class="controls">
            			<?php echo $field->input; ?>
            		</div>
            	</div>
                <?php } ?>
            <?php } ?>
		<?php } ?>
		<div class="control-group">
            <div class="controls">
    			<button type="submit" class="validate btn btn-success"><?php echo JText::_('JSUBMIT'); ?></button>
    			<?php echo JHtml::_('form.token'); ?>
            </div>
		</div>
	</form>
</div>