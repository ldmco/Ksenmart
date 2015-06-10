<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

if (!class_exists('KMPlugin')) {
	require (JPATH_ROOT . '/administrator/components/com_ksenmart/classes/kmplugin.php');
}
if (!class_exists('KSInstaller')) {
	require (JPATH_ROOT . '/plugins/system/ksencore/core/helpers/common/installer.php');
}

class plgSystemKsentplinstaller extends KMPlugin {
	
	private $eid = 0;
	private $installer = null;

	public function onExtensionAfterInstall($installer, $eid )
	{
		if (!$eid){
			return false;
		}
		
		$row = JTable::getInstance('extension');
		$row->load($eid);
		if ($row->type != 'template'){
			return false;
		}
		
		$this->installer = $installer;
		$this->eid = $eid;

		$this->processInstallTemplate();
		
		return true;
	}
	
	public function onExtensionBeforeUninstall($eid)
	{
		if (!$eid){
			return false;
		}
		
		$row = JTable::getInstance('extension');
		$row->load($eid);
		if ($row->type != 'template'){
			return false;
		}
		
		$installer = JInstaller::getInstance();
		$installer->setAdapter($row->type);
		$client = JApplicationHelper::getClientInfo($row->client_id);
		$installer->setPath('extension_root', $client->path . '/templates/' . strtolower($row->element));
		$installer->setPath('source', $installer->getPath('extension_root'));		
		$this->installer = $installer;
		$this->eid = $eid;

		$this->processUninstallTemplate();

		return true;
	}	
	
	private function processInstallTemplate()
	{
		$manifest		= $this->installer->getManifest();
		$manifestScript = (string)$manifest->installfile;
		$element        = $name = strtolower(JFilterInput::getInstance()->clean((string) $manifest->name, 'cmd'));

		if ($manifestScript)
		{
			$manifestScriptFile = $this->installer->getPath('extension_root') . '/' . $manifestScript;

			if (is_file($manifestScriptFile))
			{
				include_once $manifestScriptFile;
			}

			$classname = $element . 'InstallerScript';

			if (class_exists($classname))
			{
				$manifestClass = new $classname($this->installer);

				if ($manifestClass && method_exists($manifestClass, 'install'))
				{
					if ($manifestClass->install() === false)
					{
						$this->parent->abort(JText::_('JLIB_INSTALLER_ABORT_COMP_INSTALL_CUSTOM_INSTALL_FAILURE'));
						return false;
					}
				}
			}
		}
		
		return true;
	}	
	
	private function processUninstallTemplate()
	{
		$manifest		= $this->installer->getManifest();
		$manifestScript = (string)$manifest->installfile;
		$element        = $name = strtolower(JFilterInput::getInstance()->clean((string) $manifest->name, 'cmd'));

		if ($manifestScript)
		{
			$manifestScriptFile = $this->installer->getPath('extension_root') . '/' . $manifestScript;
			if (is_file($manifestScriptFile))
			{
				include_once $manifestScriptFile;
			}

			$classname = $element . 'InstallerScript';

			if (class_exists($classname))
			{
				$manifestClass = new $classname($this->installer);

				if ($manifestClass && method_exists($manifestClass, 'uninstall'))
				{
					if ($manifestClass->uninstall() === false)
					{
						return false;
					}
				}
			}
		}
		
		return true;
	}	
	
}