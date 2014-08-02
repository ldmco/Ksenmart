<?php 
defined( '_JEXEC' ) or die;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.archive');
jimport('joomla.filesystem.path');

class KMUpdater{

	const UPDATE_SERVER='http://update.ksenmart.ru';
	
	public static function checkLicense()
	{
		$params = JComponentHelper::getParams('com_ksenmart');
		$license = $params->get('license');
		return self::curlResponse(self::UPDATE_SERVER.'?action=check_license&license='.$params->get('license','').'&domen='.$_SERVER['HTTP_HOST']);
	}
	
	function checkUpdates()
	{
		$db=JFactory::getDBO();
		$core_newest_version=self::curlResponse(self::UPDATE_SERVER.'?action=get_last_version&type=core&name=ksenmart');
		$core_current_version=KMCommonFunctions::getManifest('version');
		if ($core_newest_version!=$core_current_version)
			return true;
		$query="select name,version from #__ksenmart_components";	
		$db->setQuery($query);
		$components=$db->loadObjectList();
		foreach($components as $component)
		{
			$component_newest_version=self::curlResponse(self::UPDATE_SERVER.'?action=get_last_version&type=component&name='.$component->name);
			if ($component->version!=$component_newest_version)	
				return true;
		}
		$query="select name,version from #__ksenmart_modules";	
		$db->setQuery($query);
		$modules=$db->loadObjectList();
		foreach($modules as $module)
		{
			$module_newest_version=self::curlResponse(self::UPDATE_SERVER.'?action=get_last_version&type=module&name='.$module->name);
			if ($module->version!=$module_newest_version)	
				return true;
		}		
		return false;
	}
	
	function getUpdates()
	{
		$db=JFactory::getDBO();
		$updates=array();
		$core_newest_version=self::curlResponse(self::UPDATE_SERVER.'?action=get_last_version&type=core&name=ksenmart');
		$core_current_version=KMCommonFunctions::getManifest('version');
		if ($core_newest_version!=$core_current_version)
			$updates[]=array('type'=>'core','name'=>'ksenmart','current_version'=>$core_current_version,'new_version'=>$core_newest_version);
		$query="select name,version from #__ksenmart_components";	
		$db->setQuery($query);
		$components=$db->loadObjectList();
		foreach($components as $component)
		{
			$component_newest_version=self::curlResponse(self::UPDATE_SERVER.'?action=get_last_version&type=component&name='.$component->name);
			if ($component->version!=$component_newest_version)	
				$updates[]=array('type'=>'component','name'=>$component->name,'current_version'=>$component->version,'new_version'=>$component_newest_version);
		}
		$query="select name,version from #__ksenmart_modules";	
		$db->setQuery($query);
		$modules=$db->loadObjectList();
		foreach($modules as $module)
		{
			$module_newest_version=self::curlResponse(self::UPDATE_SERVER.'?action=get_last_version&type=module&name='.$module->name);
			if ($module->version!=$module_newest_version)	
				$updates[]=array('type'=>'module','name'=>$module->name,'current_version'=>$module->version,'new_version'=>$module_newest_version);
		}		
		return $updates;	
	}
	
	function updatePart($component,$active)
	{
		if ($component!='')
		{
			$db=JFactory::getDBO();
			$query="select value from #__ksenmart_settings where name='update_parts'";	
			$db->setQuery($query);
			$update_parts=$db->loadResult();
			if ($active==1)
				$update_parts.=$component.';';
			else	
				$update_parts=str_replace($component.';','',$update_parts);
			$query="update #__ksenmart_settings set value='$update_parts' where name='update_parts'";	
			$db->setQuery($query);
			$db->query();				
		}
	}
	
	function downloadUpdateKsenmart()
	{
		$params = JComponentHelper::getParams('com_ksenmart');
		$license = $params->get('license');		
		$postdata = http_build_query(
			array(
				'versions' => KMUpdaterFunctions::serializeVersions()
			)
		);

		$update=self::curlResponse(self::UPDATE_SERVER.'?action=get_update&license='.$license.'&domen='.$_SERVER['HTTP_HOST'],$postdata);
		if ($update!='false')
		{
			header("Content-type: application/zip");  
			header("Content-Disposition: attachment; filename=update".date('-Y-m-d-H-i-s').".zip");  		
			echo $update;
		}
		return false;
	}	
	
	function updateKsenmart()
	{
		$params = JComponentHelper::getParams('com_ksenmart');
		$license = $params->get('license');		
		$postdata = http_build_query(
			array(
				'versions' => KMUpdaterFunctions::serializeVersions()
			)
		);
		$updates_dir=JPATH_COMPONENT.'/tmp/updates/';
		$update=self::curlResponse(self::UPDATE_SERVER.'?action=get_update&license='.$license.'&domen='.$_SERVER['HTTP_HOST'],$postdata);
		if ($update!='false')
		{
			JFolder::delete($updates_dir);	
			JFolder::create($updates_dir,0777);			
			$update_zip=$updates_dir.'update'.date('-Y-m-d-H-i-s').'.zip';
			file_put_contents($update_zip,$update);
			$zip = new ZipArchive;
			$result = JArchive::extract(JPath::clean($update_zip), JPath::clean($updates_dir));
			if (file_exists($update_zip))
				unlink($update_zip); 
			$updates=scandir($updates_dir);	
			foreach($updates as $update)
			{
				if ($update!='.' && $update!='..')
				{
					$update_dir=str_replace('.zip','',$update);
					JFolder::create($updates_dir.$update_dir,0777);	
					$result = JArchive::extract($updates_dir.$update, $updates_dir.$update_dir);
					if (file_exists($updates_dir.$update_dir.'/install.php'))
						include($updates_dir.$update_dir.'/install.php'); 
					JFolder::delete($updates_dir.$update_dir);	
					if (file_exists($updates_dir.$update))
						unlink($updates_dir.$update); 	
				}
			}
		}
		return false;
	}
	
	function serializeVersions()
	{
		$db=JFactory::getDBO();
		$versions['ksenmart']=KMCommonFunctions::getSettingsValue('version');
		$query="select name,version from #__ksenmart_components";	
		$db->setQuery($query);
		$components=$db->loadObjectList();
		foreach($components as $component)
			$versions[$component->name]=$component->version;
		$query="select name,version from #__ksenmart_modules";	
		$db->setQuery($query);
		$modules=$db->loadObjectList();
		foreach($modules as $module)
			$versions[$module->name]=$module->version;	
		return serialize($versions);
	}	
	
	function updateComponentVersion($component,$version)
	{
		$db=JFactory::getDBO();
		$query="update #__ksenmart_components set version='$version' where name='$component'";
		$db->setQuery($query);
		$db->query();
	}
	
	public static function sendShopEmail()
	{
		$params = JComponentHelper::getParams('com_ksenmart');
		$shop_email=$params->get('shop_email','');
		if (empty($shop_email))
			return;
		$postdata = http_build_query(
			array(
				'shop_email' => $shop_email
			)
		);		
		return self::curlResponse(self::UPDATE_SERVER.'?action=send_shop_email&license='.$params->get('license','').'&domen='.$_SERVER['HTTP_HOST'],$postdata);
	}	
	
	function curlResponse($url,$data='',$timeout=600 )
	{
		$ch = curl_init (); 
		curl_setopt ( $ch, CURLOPT_URL, $url ); 
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); 
		curl_setopt ( $ch, CURLOPT_HEADER, false );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data ); 
		curl_setopt ( $ch, CURLOPT_TIMEOUT, ( int ) $timeout ); 
		
		$response = curl_exec ( $ch );
		curl_close ( $ch );
		
		return $response;
	}	
	
}	
?>