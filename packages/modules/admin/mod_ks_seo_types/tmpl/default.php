<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<li>
<div class="km-list-left-module km-seo-types mod_km_seo_types">
	<div class="km-list-left-module-title">
		<label><?php echo JText::_('mod_ks_seo_types_title')?></label>
		<a class="sh hides" href="#"></a>
	</div>	
	<div class="km-list-left-module-content">
		<div class="lists">
			<div class="row-fluid">	
				<ul>
					<?php foreach($seo_types as $seo_type):?>
					<li class="<?php echo ($seo_type->selected?'active':'');?>">
						<label>
							<?php echo JText::_('ks_'.$seo_type->title);?>
							<input type="radio" value="<?php echo $seo_type->title?>" onclick="setSeoType(this);" name="seo_type" <?php echo ($seo_type->selected?'checked':'')?>>
						</label>
					</li>
					<?php endforeach;?>
				</ul>
			</div>
		</div>	
	</div>	
</div>
</li>				