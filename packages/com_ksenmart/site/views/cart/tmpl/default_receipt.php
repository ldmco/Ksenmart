<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<form action="" class="noprint">
	<input id="print_button" type="button" value="<?php echo JText::_('ksm_print'); ?>" alt="<?php echo JText::_('ksm_print'); ?>" title="<?php echo JText::_('ksm_print'); ?>" onclick="window.print();return false;">
</form>
<table border=1 cellpadding=0 cellspacing=0 width=683 style='border-collapse:
 collapse;width:513pt'>
 <col width=78 style='mso-width-source:userset;mso-width-alt:3328;width:59pt'>
 <col width=35 style='mso-width-source:userset;mso-width-alt:1493;width:26pt'>
 <col width=19 style='mso-width-source:userset;mso-width-alt:810;width:14pt'>
 <col width=39 style='mso-width-source:userset;mso-width-alt:1664;width:29pt'>
 <col width=20 style='mso-width-source:userset;mso-width-alt:853;width:15pt'>
 <col width=35 style='mso-width-source:userset;mso-width-alt:1493;width:26pt'>
 <col width=61 style='mso-width-source:userset;mso-width-alt:2602;width:46pt'>
 <col width=38 style='mso-width-source:userset;mso-width-alt:1621;width:29pt'>
 <col width=31 style='mso-width-source:userset;mso-width-alt:1322;width:23pt'>
 <col width=27 style='mso-width-source:userset;mso-width-alt:1152;width:20pt'>
 <col width=37 style='mso-width-source:userset;mso-width-alt:1578;width:28pt'>
 <col width=19 span=3 style='mso-width-source:userset;mso-width-alt:810;
 width:14pt'>
 <col width=37 span=2 style='mso-width-source:userset;mso-width-alt:1578;
 width:28pt'>
 <col width=21 style='mso-width-source:userset;mso-width-alt:896;width:16pt'>
 <col width=17 style='mso-width-source:userset;mso-width-alt:725;width:13pt'>
 <col width=24 style='mso-width-source:userset;mso-width-alt:1024;width:18pt'>
 <col width=32 style='mso-width-source:userset;mso-width-alt:1365;width:24pt'>
 <col width=9 style='mso-width-source:userset;mso-width-alt:384;width:7pt'>
 <col width=29 style='mso-width-source:userset;mso-width-alt:1237;width:22pt'>
 <col width=62 span=234 style='mso-width-source:userset;mso-width-alt:2645;
 width:47pt'>
 <tr height=20 style='mso-height-source:userset;height:15.0pt'>
  <td colspan=3 height=20 width=132 style='height:15.0pt;width:99pt'></td>
  <td width=39 style='width:29pt'></td>
  <td width=20 style='width:15pt'></td>
  <td colspan=3 width=134 style='width:101pt'></td>
  <td width=31 style='width:23pt'></td>
  <td width=27 style='width:20pt'></td>
  <td width=37 style='width:28pt'></td>
  <td width=19 style='width:14pt'></td>
  <td width=19 style='width:14pt'></td>
  <td width=19 style='width:14pt'></td>
  <td width=37 style='width:28pt'></td>
  <td width=37 style='width:28pt'></td>
  <td width=21 style='width:16pt'></td>
  <td width=17 style='width:13pt'></td>
  <td width=24 style='width:18pt'></td>
  <td colspan=3 class=xl26 width=70 style='width:53pt'>0401060</td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.75pt'>
  <td colspan=3 height=13 class=xl27 style='height:9.75pt'><?php echo JText::_('KSM_CART_RECEIPT_CASH'); ?></td>
  <td colspan=2 style='mso-ignore:colspan'></td>
  <td colspan=3 class=xl27><?php echo JText::_('KSM_CART_RECEIPT_DEBIT'); ?></td>
  <td colspan=14 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=19 style='mso-height-source:userset;height:14.45pt'>
  <td colspan=8 rowspan=2 height=45 class=xl28 style='height:34.55pt'
  x:str="<?php echo JText::_('KSM_CART_RECEIPT_NUMBER'); ?>"><?php echo JText::_('KSM_CART_RECEIPT_NUMBER'); ?><span
  style='mso-spacerun:yes'> </span></td>
  <td colspan=5 rowspan=2 class=xl29><?php echo date('d.m.Y')?></td>
  <td></td>
  <td colspan=5 rowspan=2 class=xl31><?php echo JText::_('KSM_CART_RECEIPT_ELECTRON'); ?></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=26 style='mso-height-source:userset;height:20.1pt'>
  <td height=26 style='height:20.1pt'></td>
  <td></td>
  <td class=xl19></td>
  <td class=xl20>&nbsp;</td>
 </tr>
 <tr height=19 style='mso-height-source:userset;height:14.25pt'>
  <td height=19 colspan=8 style='height:14.25pt;mso-ignore:colspan'></td>
  <td colspan=5 class=xl27><?php echo JText::_('KSM_CART_RECEIPT_DATE'); ?></td>
  <td></td>
  <td colspan=5 class=xl27><?php echo JText::_('KSM_CART_RECEIPT_TYPE'); ?></td>
  <td></td>
  <td class=xl21></td>
  <td></td>
 </tr>
 <tr height=58 style='mso-height-source:userset;height:43.5pt'>
  <td height=58 class=xl22 width=78 style='height:43.5pt;width:59pt'><?php echo JText::_('KSM_CART_RECEIPT_SUM_LETTERS'); ?></td>
  <td colspan=21 class=xl32 width=605 style='border-left:none;width:454pt'><?php echo KSFunctions::stringView($this->order->costs['total_cost'])?></td>
 </tr>
 <tr height=20 style='mso-height-source:userset;height:15.0pt'>
  <td colspan=5 height=20 class=xl33 style='height:15.0pt'>&nbsp;</td>
  <td colspan=5 class=xl34 width=192 style='width:144pt'>&nbsp;</td>
  <td colspan=2 rowspan=2 class=xl35><?php echo JText::_('KSM_CART_RECEIPT_SUM'); ?></td>
  <td colspan=10 rowspan=2 class=xl36><?php echo $this->order->costs['total_cost_val'];?></td>
 </tr>
 <tr height=39 style='mso-height-source:userset;height:29.25pt'>
  <td colspan=10 rowspan=2 height=84 class=xl37 width=383 style='height:63.0pt;width:287pt'><?php echo $this->order->customer_fields->name;?></td>
 </tr>
 <tr height=45 style='mso-height-source:userset;height:33.75pt'>
  <td colspan=2 rowspan=2 height=58 class=xl35 style='height:43.5pt'><?php echo JText::_('KSM_CART_RECEIPT_ACCOUNT_NUMBER'); ?></td>
  <td colspan=10 rowspan=2 class=xl38>&nbsp;</td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.75pt'>
  <td colspan=10 height=13 class=xl39 style='height:9.75pt'><?php echo JText::_('KSM_CART_RECEIPT_PAYER'); ?></td>
 </tr>
 <tr height=21 style='mso-height-source:userset;height:15.75pt'>
  <td colspan=10 rowspan=2 height=45 class=xl40 width=383 style='height:33.75pt;
  width:287pt'>&nbsp;</td>
  <td colspan=2 class=xl41 style='border-left:none'><?php echo JText::_('KSM_CART_RECEIPT_BIK'); ?></td>
  <td colspan=10 class=xl42></td>
 </tr>
 <tr height=24 style='mso-height-source:userset;height:18.0pt'>
  <td colspan=2 rowspan=2 height=38 class=xl35 style='height:28.5pt'><?php echo JText::_('KSM_CART_RECEIPT_ACCOUNT_NUMBER'); ?></td>
  <td colspan=10 rowspan=2 class=xl43>&nbsp;</td>
 </tr>
 <tr height=14 style='mso-height-source:userset;height:10.5pt'>
  <td colspan=10 height=14 class=xl39 style='height:10.5pt'><?php echo JText::_('KSM_CART_RECEIPT_PAYER_BANK'); ?></td>
 </tr>
 <tr height=21 style='mso-height-source:userset;height:15.75pt'>
  <td colspan=10 rowspan=2 height=46 class=xl40 width=383 style='height:34.5pt;width:287pt'><?php echo $this->order->payment->params['bankname']?></td>
  <td colspan=2 class=xl41 style='border-left:none'><?php echo JText::_('KSM_CART_RECEIPT_BIK'); ?></td>
  <td colspan=10 class=xl44><?php echo $this->order->payment->params['bik']?></td>
 </tr>
 <tr height=25 style='mso-height-source:userset;height:18.75pt'>
  <td colspan=2 rowspan=2 height=39 class=xl35 style='height:29.25pt'><?php echo JText::_('KSM_CART_RECEIPT_ACCOUNT_NUMBER'); ?></td>
  <td colspan=10 rowspan=2 class=xl45><?php echo $this->order->payment->params['bank_account_number']?></td>
 </tr>
 <tr height=14 style='mso-height-source:userset;height:10.5pt'>
  <td colspan=10 height=14 class=xl39 style='height:10.5pt'><?php echo JText::_('KSM_CART_RECEIPT_RECIPIENT_BANK'); ?></td>
 </tr>
 <tr height=20 style='mso-height-source:userset;height:15.0pt'>
  <td colspan=5 height=20 class=xl33 style='height:15.0pt'><?php echo JText::_('KSM_CART_RECEIPT_INN'); ?> <?php echo $this->order->payment->params['inn']?></td>
  <td colspan=5 class=xl34 width=192 style='width:144pt'><?php echo JText::_('KSM_CART_RECEIPT_KPP'); ?> <?php echo $this->order->payment->params['kpp']?></td>
  <td colspan=2 rowspan=2 class=xl35><?php echo JText::_('KSM_CART_RECEIPT_ACCOUNT_NUMBER'); ?></td>
  <td colspan=10 rowspan=2 class=xl45><?php echo $this->order->payment->params['bank_kor_number']?></td>
 </tr>
 <tr height=37 style='mso-height-source:userset;height:27.75pt'>
  <td colspan=10 rowspan=4 height=84 class=xl37 width=383 style='height:63.0pt;width:287pt'><?php echo $this->order->payment->params['companyname']?></td>
 </tr>
 <tr height=20 style='mso-height-source:userset;height:15.0pt'>
  <td colspan=2 height=20 class=xl46 style='height:15.0pt;border-left:none'><?php echo JText::_('KSM_CART_RECEIPT_PAYMENT_TYPE'); ?></td>
  <td colspan=3 class=xl47 style='border-left:none'>01</td>
  <td colspan=3 class=xl48 style='border-left:none'><?php echo JText::_('KSM_CART_RECEIPT_PAYMENT_DATE'); ?></td>
  <td colspan=4 class=xl49>&nbsp;</td>
 </tr>
 <tr height=19 style='mso-height-source:userset;height:14.25pt'>
  <td colspan=2 height=19 class=xl46 style='height:14.25pt;border-left:none'><?php echo JText::_('KSM_CART_RECEIPT_PAYMENT_PURPOSE'); ?></td>
  <td colspan=3 class=xl50 style='border-left:none'>&nbsp;</td>
  <td colspan=3 class=xl46 style='border-left:none'><?php echo JText::_('KSM_CART_RECEIPT_PAYMENT_QUEUE'); ?></td>
  <td colspan=4 class=xl51 align=right style='border-left:none' x:num>6</td>
 </tr>
 <tr height=8 style='mso-height-source:userset;height:6.0pt'>
  <td colspan=2 rowspan=2 height=22 class=xl46 style='height:16.5pt'><?php echo JText::_('KSM_CART_RECEIPT_PAYMENT_CODE'); ?></td>
  <td colspan=3 rowspan=2 class=xl52>&nbsp;</td>
  <td colspan=3 rowspan=2 class=xl46><?php echo JText::_('KSM_CART_RECEIPT_PAYMENT_SUMMARY_FIELD'); ?></td>
  <td colspan=4 rowspan=2 class=xl53>&nbsp;</td>
 </tr>
 <tr height=14 style='mso-height-source:userset;height:10.5pt'>
  <td colspan=10 height=14 class=xl39 style='height:10.5pt'><?php echo JText::_('KSM_CART_RECEIPT_RECIPIENT'); ?></td>
 </tr>
 <tr height=20 style='mso-height-source:userset;height:15.0pt'>
  <td colspan=4 height=20 class=xl54 style='height:15.0pt'>&nbsp;</td>
  <td colspan=3 class=xl23>&nbsp;</td>
  <td class=xl23>&nbsp;</td>
  <td colspan=3 class=xl23>&nbsp;</td>
  <td colspan=5 class=xl55 style='border-left:none'>&nbsp;</td>
  <td colspan=4 class=xl55 style='border-left:none'>&nbsp;</td>
  <td colspan=2 class=xl56 style='border-left:none'>&nbsp;</td>
 </tr>
 <tr height=97 style='mso-height-source:userset;height:72.75pt'>
  <td colspan=22 height=97 class=xl57 width=683 style='height:72.75pt;width:513pt' x:str="<?php echo JText::_('KSM_CART_RECEIPT_TEXT'); ?>">
  <?php echo JText::sprintf('KSM_CART_RECEIPT_TEXT', $this->order->id, date('d.m.Y')); ?><span
  style='mso-spacerun:yes'> </span></td>
 </tr>
 <tr height=16 style='mso-height-source:userset;height:12.6pt'>
  <td colspan=22 height=16 class=xl58 style='height:12.6pt'><?php echo JText::_('KSM_CART_RECEIPT_PAYMENT_FULLPURPOSE'); ?></td>
 </tr>
 <tr height=14 style='mso-height-source:userset;height:10.5pt'>
  <td height=14 style='height:10.5pt'></td>
  <td class=xl21></td>
  <td colspan=4 style='mso-ignore:colspan'></td>
  <td colspan=7 class=xl24><?php echo JText::_('KSM_CART_RECEIPT_SIGNS'); ?></td>
  <td colspan=2 style='mso-ignore:colspan'></td>
  <td colspan=4 style='mso-ignore:colspan'><?php echo JText::_('KSM_CART_RECEIPT_SIGNS'); ?></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=44 style='mso-height-source:userset;height:33.0pt'>
  <td height=44 style='height:33.0pt'></td>
  <td class=xl24></td>
  <td colspan=4 style='mso-ignore:colspan'></td>
  <td colspan=7 class=xl58>&nbsp;</td>
  <td colspan=9 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=58 style='mso-height-source:userset;height:43.5pt'>
  <td height=58 class=xl21 style='height:43.5pt'></td>
  <td class=xl25><?php echo JText::_('KSM_CART_RECEIPT_STAMP'); ?></td>
  <td colspan=4 style='mso-ignore:colspan'></td>
  <td colspan=7 class=xl58>&nbsp;</td>
  <td colspan=9 style='mso-ignore:colspan'></td>
 </tr>
 <![if supportMisalignedColumns]>
 <tr height=0 style='display:none'>
  <td width=78 style='width:59pt'></td>
  <td width=35 style='width:26pt'></td>
  <td width=19 style='width:14pt'></td>
  <td width=39 style='width:29pt'></td>
  <td width=20 style='width:15pt'></td>
  <td width=35 style='width:26pt'></td>
  <td width=61 style='width:46pt'></td>
  <td width=38 style='width:29pt'></td>
  <td width=31 style='width:23pt'></td>
  <td width=27 style='width:20pt'></td>
  <td width=37 style='width:28pt'></td>
  <td width=19 style='width:14pt'></td>
  <td width=19 style='width:14pt'></td>
  <td width=19 style='width:14pt'></td>
  <td width=37 style='width:28pt'></td>
  <td width=37 style='width:28pt'></td>
  <td width=21 style='width:16pt'></td>
  <td width=17 style='width:13pt'></td>
  <td width=24 style='width:18pt'></td>
  <td width=32 style='width:24pt'></td>
  <td width=9 style='width:7pt'></td>
  <td width=29 style='width:22pt'></td>
 </tr>
 <![endif]>
</table>
