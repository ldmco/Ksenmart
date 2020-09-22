<?php
/**
 * @copyright   Copyright (C) 2017. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="ksm-module-minicart <?php echo $class_sfx ?>">
    <div class="ksm-module-minicart-icon"></div>
    <div class="ksm-module-minicart-block">
        <div class="ksm-module-minicart-inner">
            <div class="ksm-module-minicart-products-wrapper">
				<?php if ($cart->total_prds == 0) { ?>
					<?php echo JText::_('MOD_KM_MINICART_EMPTY_TEXT'); ?>
				<?php } else { ?>
                    <div class="ksm-module-minicart-products">
                        <table width="100%">
                            <thead>
                            <tr>
                                <th><?php echo JText::_('MOD_KM_MINICART_PRODUCT_PHOTO'); ?></th>
                                <th><?php echo JText::_('MOD_KM_MINICART_PRODUCT_TITLE'); ?></th>
                                <th><?php echo JText::_('MOD_KM_MINICART_PRODUCT_COUNT'); ?></th>
                                <th><?php echo JText::_('MOD_KM_MINICART_PRODUCT_PRICE'); ?></th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <td colspan="4">
                                    <hr>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3"><?php echo JText::_('MOD_KM_MINICART_TOTAL'); ?> </td>
                                <td class="ksm-module-minicart-total"><span><?php echo $cart->products_sum_val; ?> </span></td>
                            </tr>
                            </tfoot>
                            <tbody>
							<?php foreach ($cart->items as $item) { ?>
                                <tr>
                                    <td><img src="<?php echo $item->product->mini_small_img; ?>"/></td>
                                    <td>
                                        <div class="product_name">
                                            <a href="<?php echo $item->product->link; ?>"><?php echo $item->product->title; ?></a>
                                        </div>
                                    </td>
                                    <td style="text-align: center;"><span
                                                class="quantity"><?php echo $item->count; ?></span></td>
                                    <td style="width: 60px;">
                                        <div class="subtotal_with_tax"
                                             style="float: right;"><?php echo $item->price_val; ?></div>
                                    </td>
                                </tr>
							<?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="show-cart">
                        <a href="<?php echo $link; ?>"
                           rel="nofollow"><?php echo JText::_('MOD_KM_MINICART_SHOW_CART'); ?></a>
                    </div>
				<?php } ?>
            </div>
        </div>
    </div>
</div>