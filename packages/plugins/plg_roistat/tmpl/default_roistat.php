<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<form class="form form-horizontal js-RoiUserSave" method="POST">
	<table class="cat" width="100%" cellspacing="0">	
		<thead>
			<tr>
				<th align="left" style="position:relative;">
					<?php echo JText::_('KSM_EXPORTIMPORT_ROISTAT_SETTINGS'); ?>
				</th>	
			</tr>
		<thead>
		<tbody>
		<tr>
			<td class="rightcol mod_ksm_roistat" style="background:#f9f9f9!important;padding:15px 10px;">
				<?php if($this->user_isset): ?>
					<?php echo $this->loadTemplate('roistat_info'); ?>
				<?php else: ?>
					<?php echo $this->loadTemplate('roistat_form'); ?>
				<?php endif; ?>
			</td>
		</tr>
		</tbody>
	</table>
</form>