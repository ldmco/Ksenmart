<?php 
defined('_JEXEC') or die('Restricted access');

if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

if (!class_exists('KMPlugin')) 
{
	require (JPATH_ROOT . '/administrator/components/com_ksenmart/classes/kmplugin.php');
}

class plgSystemCallbackkiller extends KMPlugin 
{
	
	public function onAfterRender()
	{
		if (JFactory::getApplication()->isAdmin())
		{
			return true;
		}	
		
		$callbackkiller_code = $this->params->get('callbackkiller_code', '');
		
		if (empty($callbackkiller_code))
		{
			return true;
		}
		
		$code = '
		<link rel="stylesheet" href="//callbackkiller.ru/widget/cbk.css">
		<script type="text/javascript" src="//callbackkiller.ru/widget/cbk.js" charset="UTF-8"></script>
		<script type="text/javascript">var callbackkiller_code="'.$callbackkiller_code.'";</script>		
		</body>
		';
		
		$output = JResponse::getBody();
		$output = str_replace('</body>', $code, $output);
		JResponse::setBody($output);
		
		return true;
	}

}