<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<div class="ksm-congratulation-txt">
	<?php echo JText::sprintf($this->params->get('printforms_congritulation_message_template', 'KSM_CART_CONGRATULATION_ORDER_NUMBES'), $this->cart->id, $this->params->get('shop_phone', '')); ?>
</div>
