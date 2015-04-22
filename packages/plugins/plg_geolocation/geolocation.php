<?php
defined('_JEXEC') or die;

if (!class_exists('KMPlugin')) {
    require (JPATH_ROOT.'/administrator/components/com_ksenmart/classes/kmplugin.php');
}

class plgKMPluginsGeolocation extends KMPlugin {

    public function onBeforeStartComponent(){
		$db=JFactory::getDBO();
		$session=JFactory::getSession();
		$user_region=$session->get('user_region',0);
		$phone_code=$session->get('phone_code','');
		if (empty($user_region))
		{
			$user_region=0;
			require_once dirname(__file__) . '/helpers/geo.php';
			$SxGeo = new SxGeo(dirname(__file__) . '/helpers/SxGeoCity.dat', SXGEO_BATCH | SXGEO_MEMORY);
			$data = $SxGeo->getCityFull($_SERVER['HTTP_X_REAL_IP']);
			
			$region = isset($data['region']['name_ru']) ? $data['region']['name_ru'] : '';
			if ($region!='')
			{
				$query = $db->getQuery(true);
				$query->select('id')->from('#__ksenmart_regions')->where('title='.$db->quote($region));
				$db->setQuery($query, 0, 1);
				$user_region=(int)$db->loadResult();
			}
			$session->set('user_region',$user_region);	
			
			$country = isset($data['country']['name_ru']) ? $data['country']['name_ru'] : '';
			if ($country!='')
			{
				$query = $db->getQuery(true);
				$query->select('phone_code')->from('#__ksenmart_countries')->where('title='.$db->quote($country));
				$db->setQuery($query, 0, 1);
				$phone_code=$db->loadResult();
			}
			$session->set('phone_code',$phone_code);	
			
			unset($SxGeo);
		}

    }
	
}