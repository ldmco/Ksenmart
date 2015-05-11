<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if (!class_exists('KsenMartHelper'))
	include(JPATH_ROOT.'/administrator/components/com_ksenmart/helpers/ksenmart.php');
$Itemid=KsenMartHelper::getShopItemid();
$title=JRequest::getVar('title','');		
?>
<div class="search">
	<form action="<?=JRoute::_('index.php?option=com_ksenmart&view=shopcatalog&Itemid='.$Itemid)?>" method="get" id="simple-search-form">
		<div class="input-append">
			<input class="span9" name="title" id="appendedInputButton" type="text" placeholder="Поиск" value="<?=$title?>">
			<button class="btn" type="submit">Поиск</button>
		</div>
	</form>	
</div>