<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<?php if (count($this->customer_fields)): ?>
    <legend><?php echo JText::_('KSM_CART_SOME_MORE_DATA'); ?>:</legend>
	<?php echo $this->loadTemplate('customer_fields'); ?>
<?php endif; ?>
<?php if (count($this->address_fields)): ?>
    <legend><?php echo JText::_('KSM_CART_ADDRESS_FIELDS_TITLE'); ?>:</legend>
	<?php echo $this->loadTemplate('address_fields'); ?>
<?php endif; ?>