<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class modKMBestProductHelper {

	public static function getList($params) {
		$id = $params->get('product_id', 0);
		$product = KSMProducts::getProduct($id);
		$product->big_img = KSMedia::resizeImage($product->filename, $product->folder, 960, 540);

		return $product;
	}

}