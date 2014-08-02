<?php

define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);

if (file_exists(dirname(__FILE__) . '/defines.php')) {
	include_once dirname(__FILE__) . '/defines.php';
}

if (!defined('_JDEFINES')) {
	define('JPATH_BASE', $_SERVER["DOCUMENT_ROOT"]);
	require_once JPATH_BASE . '/includes/defines.php';
}

require_once JPATH_BASE . '/includes/framework.php';
// Mark afterLoad in the profiler.
JDEBUG ? $_PROFILER->mark('afterLoad') : null;
// Instantiate the application.
$app = JFactory::getApplication('site');
// Initialise the application.
$app->initialise();

$db = JFactory::getDBO();
$task = JRequest::getVar('task', null);
$response = array();


switch ($task) {
	case 'send_request':
		$ldmco_email = 'me@ldm-co.ru';
		$yescredit_email = 'komarov@yes-credit.ru';
		
		$lang = JFactory::getLanguage();
		$lang->load('plg_kmpayment_yescredit.sys', JPATH_ADMINISTRATOR, null, false, false) || $lang->load('plg_kmpayment_yescredit.sys', JPATH_PLUGINS . DS . 'kmpayment' . DS . 'yescredit', null, false, false) || $lang->load('plg_kmpayment_yescredit.sys', JPATH_ADMINISTRATOR, $lang->getDefault() , false, false) || $lang->load('plg_kmpayment_yescredit.sys', JPATH_PLUGINS . DS . 'kmpayment' . DS . 'yescredit', $lang->getDefault() , false, false);
		
		$company_name = JRequest::getVar('company_name', '');
		$company_ogrn = JRequest::getVar('company_ogrn', '');
		$company_head = JRequest::getVar('company_head', '');
		$company_legal_address = JRequest::getVar('company_legal_address', '');
		$company_post_address = JRequest::getVar('company_post_address', '');
		$company_inn = JRequest::getVar('company_inn', '');
		$company_kpp = JRequest::getVar('company_kpp', '');
		$company_bank_account = JRequest::getVar('company_bank_account', '');
		$company_bik = JRequest::getVar('company_bik', '');
		$company_email = JRequest::getVar('company_email', '');
		$company_phone = JRequest::getVar('company_phone', '');
		
		$html = '';
		$html.= '<h1>' . JText::_('ksm_payment_yescredit_email_header') . '</h1>';
		$html.= '<table cellspacing="0" cellpadding="0">';
		$html.= '	<tr>';
		$html.= '		<td width="200px"><b>' . JText::_('ksm_payment_yescredit_company_name') . ' :</b></td>';
		$html.= '		<td>' . $company_name . '</td>';
		$html.= '	</tr>';
		$html.= '	<tr>';
		$html.= '		<td width="200px"><b>' . JText::_('ksm_payment_yescredit_company_ogrn') . ' :</b></td>';
		$html.= '		<td>' . $company_ogrn . '</td>';
		$html.= '	</tr>';
		$html.= '	<tr>';
		$html.= '		<td width="200px"><b>' . JText::_('ksm_payment_yescredit_company_head') . ' :</b></td>';
		$html.= '		<td>' . $company_head . '</td>';
		$html.= '	</tr>';
		$html.= '	<tr>';
		$html.= '		<td width="200px"><b>' . JText::_('ksm_payment_yescredit_company_legal_address') . ' :</b></td>';
		$html.= '		<td>' . $company_legal_address . '</td>';
		$html.= '	</tr>';
		$html.= '	<tr>';
		$html.= '		<td width="200px"><b>' . JText::_('ksm_payment_yescredit_company_post_address') . ' :</b></td>';
		$html.= '		<td>' . $company_post_address . '</td>';
		$html.= '	</tr>';
		$html.= '	<tr>';
		$html.= '		<td width="200px"><b>' . JText::_('ksm_payment_yescredit_company_inn') . ' :</b></td>';
		$html.= '		<td>' . $company_inn . '</td>';
		$html.= '	</tr>';
		$html.= '	<tr>';
		$html.= '		<td width="200px"><b>' . JText::_('ksm_payment_yescredit_company_kpp') . ' :</b></td>';
		$html.= '		<td>' . $company_kpp . '</td>';
		$html.= '	</tr>';
		$html.= '	<tr>';
		$html.= '		<td width="200px"><b>' . JText::_('ksm_payment_yescredit_company_bank_account') . ' :</b></td>';
		$html.= '		<td>' . $company_bank_account . '</td>';
		$html.= '	</tr>';
		$html.= '	<tr>';
		$html.= '		<td width="200px"><b>' . JText::_('ksm_payment_yescredit_company_bik') . ' :</b></td>';
		$html.= '		<td>' . $company_bik . '</td>';
		$html.= '	</tr>';
		$html.= '	<tr>';
		$html.= '		<td width="200px"><b>' . JText::_('ksm_payment_yescredit_company_email') . ' :</b></td>';
		$html.= '		<td>' . $company_email . '</td>';
		$html.= '	</tr>';
		$html.= '	<tr>';
		$html.= '		<td width="200px"><b>' . JText::_('ksm_payment_yescredit_company_phone') . ' :</b></td>';
		$html.= '		<td>' . $company_phone . '</td>';
		$html.= '	</tr>';
		$html.= '</table>';
		
		$mail = JFactory::getMailer();
		$sender = array(
			$ldmco_email,
			JText::_('ksm_payment_yescredit_email_sender_name')
		);
		$mail->isHTML(true);
		$mail->setSender($sender);
		$mail->Subject = JText::_('ksm_payment_yescredit_email_subject');
		$mail->Body = $html;
		$mail->AddAddress($ldmco_email);
		
		$mail = JFactory::getMailer();
		$sender = array(
			$ldmco_email,
			JText::_('ksm_payment_yescredit_email_sender_name')
		);
		$mail->isHTML(true);
		$mail->setSender($sender);
		$mail->Subject = JText::_('ksm_payment_yescredit_email_subject');
		$mail->Body = $html;
		$mail->AddAddress($yescredit_email);
		
		if ($mail->Send()) $message = JText::_('ksm_payment_yescredit_email_success');
		else $message = JText::_('ksm_payment_yescredit_email_error');
		
		$response['message'] = $message;
		
		break;
	}
	
	$response = json_encode($response);
	JFactory::getDocument()->setMimeEncoding('application/json');
	echo $response;
	$app->close();
?>