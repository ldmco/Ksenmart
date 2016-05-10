<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-module-minicart <?php echo $class_sfx?>">
	<a href="<?php echo $link; ?>">
		<div class="ksm-module-minicart-label"><?php echo JText::_('MOD_KM_MINICART_LABEL'); ?> [<?php echo $cart->total_prds; ?>]</div>
		<div class="ksm-module-minicart-text"><?php echo JText::_('MOD_KM_MINICART_TEXT'); ?></div>
	</a>
</div>