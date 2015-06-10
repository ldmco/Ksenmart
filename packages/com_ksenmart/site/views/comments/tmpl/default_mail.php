<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<table class="cellpadding">
	<tr>
		<td colspan="2"><?php echo JText::_('KSM_PRODUCT_POST_A_REVIEW_INFO_LBL');?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('KSM_PRODUCT_POST_A_REVIEW_NAME');?>:</td>
		<td><?php echo $this->comment->name; ?></td>
	</tr>	
	<tr>
		<td><?php echo JText::_('KSM_COMMENTS_LABEL_TEXT');?>:</td>
		<td><?php echo $this->comment->comment; ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('KSM_PRODUCT_POST_A_REVIEW_HIGHS_LBL');?>:</td>
		<td><?php echo $this->comment->good; ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('KSM_PRODUCT_POST_A_REVIEW_LOWS_LBL');?>:</td>
		<td><?php echo $this->comment->bad; ?></td>
	</tr>
</table>	