<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<table class="cellpadding">
	<tr>
		<td colspan="2"><?php echo JText::_('KSM_ORDER_MAIL_INFORMATION'); ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('KSM_ORDER_MAIL_NAME'); ?></td>
		<td><?php echo $this->order->customer_name; ?></td>
	</tr>							
	<tr>
		<td><?php echo JText::_('KSM_ORDER_MAIL_EMAIL'); ?></td>
		<td><?php echo $this->order->customer_fields['email']; ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('KSM_ORDER_MAIL_ADDRESS'); ?></td>
		<td><?php echo $this->order->address_fields; ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('KSM_ORDER_MAIL_PHONE'); ?></td>
		<td><?php echo $this->order->phone; ?></td>
	</tr>	
</table>	
<h2><?php echo JText::_('KSM_ORDER_MAIL_ORDER'); ?></h2>
<table id="cart_content_tbl" cellspacing="0">
	<colgroup>
		<col width="50%" />
		<col width="15%" />
		<col width="20%" />
		<col width="5%" />
	</colgroup>
	<tr id="cart_content_header">
		<td><b><?php echo JText::_('KSM_ORDER_MAIL_PRODUCT'); ?></b></td>
		<td align="center"><b><?php echo JText::_('KSM_ORDER_MAIL_PRODUCT_QTY'); ?></b></td>
		<td align="center"><b><?php echo JText::_('KSM_ORDER_MAIL_PRODUCT_PRICE'); ?></b></td>
		<td align="center"><b><?php echo JText::_('KSM_ORDER_MAIL_PRODUCT_SUM'); ?></b></td>
	</tr>
    <?php foreach($this->order->items as $item) { ?>
    		<tr class="row_odd">
    			<td class="vid_produkt">
    				<a class="title_lookp" href="<?php echo JURI::root() . JRoute::_('index.php?option=com_ksenmart&view=product&id=' . $item->product_id . ':' . $item->product->alias . '&Itemid=' . KSSystem::getShopItemid()); ?>" ><?php echo $item->product->title; ?></a>
                    <?php if($item->product->product_code != '') { ?>
                        <i><?php echo JText::_('KSM_ORDER_MAIL_PRODUCT_SKU'); ?> <?php echo $item->product->product_code; ?></i>
                    <?php } ?>
                
                    <?php foreach($item->properties as $item_property) { ?>
                        <?php if(!empty($item_property->value)) { ?>
                            <br /><span><?php echo $item_property->title; ?>:</span> <?php echo $item_property->value; ?>
                        <?php } ?>
                    <?php } ?>
    			</td>
			<td align="center"><?php echo $item->count; ?></td>
			<td align="center"><?php echo KSMPrice::showPriceWithTransform($item->price); ?></td>
			<td align="center" nowrap="nowrap">
				<?php echo KSMPrice::showPriceWithTransform($item->count * $item->price); ?>
			</td>
		</tr>
    <?php } ?>
	<tr>
		<td id="cart_total_label">
			<?php echo JText::_('KSM_ORDER_MAIL_PRODUCTS_SUM'); ?>
		</td>
		<td align="center">
		</td>
		<td></td>
		<td id="cart_total" align="center"><?php echo $this->order->costs['cost_val']; ?></td>
	</tr>
	<tr>
		<td id="cart_total_label"><?php echo JText::_('KSM_ORDER_MAIL_DISCOUNT'); ?></td>
		<td align="center"></td>
		<td></td>
		<td id="cart_total" align="center"><?php echo $this->order->costs['discount_cost_val']; ?></td>
	</tr>	
	<tr>
		<td id="cart_total_label"><?php echo JText::_('KSM_ORDER_MAIL_SHIPPING'); ?></td>
		<td align="center"></td>
		<td></td>
		<td id="cart_total" align="center"><?php echo $this->order->costs['shipping_cost_val']; ?></td>
	</tr>	
	<tr>
		<td id="cart_total_label">
			<?php echo JText::_('KSM_ORDER_MAIL_TOTAL'); ?>
		</td>
		<td align="center"></td>
		<td></td>
		<td id="cart_total" align="center"><?php echo $this->order->costs['total_cost_val']; ?></td>
	</tr>
</table>
<?php $this->params = JComponentHelper::getParams('com_ksenmart'); ?>
<?php if($this->params->get('printforms_company_logo', null) && JURI::root() . $this->params->get('printforms_company_logo')): ?>
<a href="<?php echo JURI::root(); ?>" title="<?php echo $this->params->get('printforms_companyname'); ?>">
	<img src="<?php echo JURI::root() . $this->params->get('printforms_company_logo'); ?>" alt="<?php echo $this->params->get('printforms_companyname'); ?>" />
</a>
<?php endif; ?>
<?php if($this->params->get('printforms_companyname')): ?>
<p><b><?php echo JText::_('KSM_ORDER_MAIL_COMPANYNAME'); ?> </b><?php echo $this->params->get('printforms_companyname'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_companyaddress')): ?>
<p><b><?php echo JText::_('KSM_ORDER_MAIL_COMPANYADDRESS'); ?> </b><?php echo $this->params->get('printforms_companyaddress'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_companyphone')): ?>
<p><b><?php echo JText::_('KSM_ORDER_MAIL_COMPANYPHONE'); ?> </b><?php echo $this->params->get('printforms_companyphone'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_ceo_name')): ?>
<p><b><?php echo JText::_('KSM_ORDER_MAIL_CEO_NAME'); ?> </b><?php echo $this->params->get('printforms_ceo_name'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_buh_name')): ?>
<p><b><?php echo JText::_('KSM_ORDER_MAIL_BUH_NAME'); ?> </b><?php echo $this->params->get('printforms_buh_name'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_bank_account_number')): ?>
<p><b><?php echo JText::_('KSM_ORDER_MAIL_BANK_ACCOUNT_NUMBER'); ?> </b><?php echo $this->params->get('printforms_bank_account_number'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_inn')): ?>
<p><b><?php echo JText::_('KSM_ORDER_MAIL_INN'); ?> </b><?php echo $this->params->get('printforms_inn'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_kpp')): ?>
<p><b><?php echo JText::_('KSM_ORDER_MAIL_KPP'); ?> </b><?php echo $this->params->get('printforms_kpp'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_bankname')): ?>
<p><b><?php echo JText::_('KSM_ORDER_MAIL_BANKNAME'); ?> </b><?php echo $this->params->get('printforms_bankname'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_bank_kor_number')): ?>
<p><b><?php echo JText::_('KSM_ORDER_MAIL_BANK_KOR_NUMBER'); ?> </b><?php echo $this->params->get('printforms_bank_kor_number'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_bik')): ?>
<p><b><?php echo JText::_('KSM_ORDER_MAIL_BIK'); ?> </b><?php echo $this->params->get('printforms_bik'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_ip_name')): ?>
<p><b><?php echo JText::_('KSM_ORDER_MAIL_IP_NAME'); ?> </b><?php echo $this->params->get('printforms_ip_name'); ?></p>
<?php endif; ?>
<?php if($this->params->get('printforms_ip_registration')): ?>
<p><b><?php echo JText::_('KSM_ORDER_MAIL_IP_REGISTRATION'); ?> </b><?php echo $this->params->get('printforms_ip_registration'); ?></p>
<?php endif; ?>