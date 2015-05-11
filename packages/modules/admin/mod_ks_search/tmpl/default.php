<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<li>
	<form ng-submit="search()">
		<div class="km-list-left-module km-search mod_km_search" ng-submit="search()">
			<input type="text" class="inputbox" id="searchword" ng-model="filterByTitle" ng-change="search()" name="searchword" value="<?php echo $searchword; ?>"/>
			<input type="submit" class="button" id="searchbutton" value="" />
		</div>
	</form>
</li>