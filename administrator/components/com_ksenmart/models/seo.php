<?php 
defined( '_JEXEC' ) or die;
jimport( 'joomla.application.component.modelkmadmin' );

class KsenMartModelSeo extends JModelKMAdmin{

	function __construct() {
		parent::__construct();
	}
	
	function populateState()
	{
	    $this->onExecuteBefore('populateState');
        
		$app = JFactory::getApplication();
		
		$params = JComponentHelper::getParams('com_ksenmart');
		
		$seo_type=$app->getUserStateFromRequest($this->context.'.seo_type', 'seo_type','text');
		$this->setState('seo_type',$seo_type);
		$this->context.='.'.$seo_type;

		$value = $app->getUserStateFromRequest($this->context.'list.limit', 'limit', $params->get('admin_product_limit',20), 0);
		$limit = $value;
		$this->setState('list.limit',$limit);	
		
		$value = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0);
		$limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
		$this->setState('list.start',$limitstart);
        
        $this->onExecuteAfter('populateState');	
	}
	
	function getUrlsConfigs()
	{
	    $this->onExecuteBefore('getUrlsConfigs');
        
		$query=$this->db->getQuery(true);
		$query->select('*')->from('#__ksenmart_seo_config')->where('type="url"');
		$this->db->setQuery($query);
		$config=$this->db->loadObjectList('part');
		foreach($config as &$c)
			$c->config=json_decode($c->config);
        
        $this->onExecuteAfter('getUrlsConfigs', array(&$config));
		return $config;
	}
	
	function saveUrlsConfigs($config)
	{
	    $this->onExecuteBefore('saveUrlsConfigs', array(&$config));
        
		foreach($config as $key=>$val)
		{
			$query=$this->db->getQuery(true);
			$query->update('#__ksenmart_seo_config')->set('config='.$this->db->quote(json_encode($val)))->where('part='.$this->db->quote($key))->where('type="url"');
			$this->db->setQuery($query);
			$this->db->Query();
		}
        
        $this->onExecuteAfter('saveUrlsConfigs');
	}
	
	function getMetaConfigs()
	{
	    $this->onExecuteBefore('getMetaConfigs');
        
		$query=$this->db->getQuery(true);
		$query->select('*')->from('#__ksenmart_seo_config')->where('type="meta"');
		$this->db->setQuery($query);
		$config=$this->db->loadObjectList('part');
		foreach($config as &$c)
			$c->config=json_decode($c->config);
		
        $this->onExecuteAfter('getMetaConfigs', array(&$config));
        return $config;
	}

	function saveMetaConfigs($config)
	{
	    $this->onExecuteBefore('saveMetaConfigs', array(&$config));
        
		foreach($config as $key=>$val)
		{
			$query=$this->db->getQuery(true);
			$query->select('config')->from('#__ksenmart_seo_config')->where('part='.$this->db->quote($key))->where('type="meta"');
			$this->db->setQuery($query);
			$db_config=json_decode($this->db->loadResult());
			$val['description']['types']=$db_config->description->types;
			$val['keywords']['types']=$db_config->keywords->types;
			$query=$this->db->getQuery(true);
			$query->update('#__ksenmart_seo_config')->set('config='.$this->db->quote(json_encode($val)))->where('part='.$this->db->quote($key))->where('type="meta"');;
			$this->db->setQuery($query);
			$this->db->Query();
		}
        
        $this->onExecuteAfter('saveMetaConfigs');
	}

	function getTitlesConfigs()
	{
	    $this->onExecuteBefore('getTitlesConfigs');
        
		$query=$this->db->getQuery(true);
		$query->select('*')->from('#__ksenmart_seo_config')->where('type="title"');
		$this->db->setQuery($query);
		$config=$this->db->loadObjectList('part');
		foreach($config as &$c)
			$c->config=json_decode($c->config);
		
        $this->onExecuteAfter('getTitlesConfigs', array(&$config));
        return $config;
	}
	
	function saveTitlesConfigs($config)
	{
	    $this->onExecuteBefore('saveTitlesConfigs', array(&$config));
        
		foreach($config as $key=>$val)
		{
			$query=$this->db->getQuery(true);
			$query->update('#__ksenmart_seo_config')->set('config='.$this->db->quote(json_encode($val)))->where('part='.$this->db->quote($key))->where('type="title"');
			$this->db->setQuery($query);
			$this->db->Query();
		}
        
        $this->onExecuteAfter('saveTitlesConfigs');
	}	
	
	function getSeoURLValue()
	{
	    $this->onExecuteBefore('getSeoURLValue');
        
		$section = JRequest::getVar('section',null);
		$values = JRequest::getVar('values',array());
		$values[]='seo-user-value';
		$seourlvalue=new stdClass();
		$seourlvalue->values = $values;
		$seourlvalue->section = $section;
		
        $this->onExecuteAfter('getSeoURLValue', array(&$seourlvalue));
        return $seourlvalue;
	}	
	
	function getSeoTitleValue()
	{
	    $this->onExecuteBefore('getSeoTitleValue');
        
		$section = JRequest::getVar('section',null);
		$values = JRequest::getVar('values',array());
		$values[]='seo-user-value';
		if ($section=='product')
			$values[]='seo-property';
		$query=$this->db->getQuery(true);
		$query->select('id,title')->from('#__ksenmart_properties');
		$this->db->setQuery($query);
		$properties=$this->db->loadObjectList();
		$seotitlevalue=new stdClass();
		$seotitlevalue->values = $values;
		$seotitlevalue->section = $section;
		$seotitlevalue->properties = $properties;
		
        $this->onExecuteAfter('getSeoTitleValue', array(&$seotitlevalue));
        return $seotitlevalue;
	}	
	
    function getListItems()
	{
	    $this->onExecuteBefore('getListItems');
        
		$countries=$this->getState('countries');
		$order_dir=$this->getState('order_dir');
		$order_type=$this->getState('order_type');
		$query=$this->db->getQuery(true);		
		$query->select('SQL_CALC_FOUND_ROWS *')->from('#__ksenmart_seo_texts')->order('id desc');
		$this->db->setQuery($query,$this->getState('list.start'),$this->getState('list.limit'));
		$seo_texts = $this->db->loadObjectList();		
		$query=$this->db->getQuery(true);
		$query->select('FOUND_ROWS()');
		$this->db->setQuery($query);
		$this->total=$this->db->loadResult();		
		foreach($seo_texts as &$seo_text)
		{
			$seo_text->categories=$seo_text->categories!=''?explode(';',$seo_text->categories):array();
			$seo_text->countries=$seo_text->countries!=''?explode(';',$seo_text->countries):array();
			$seo_text->manufacturers=$seo_text->manufacturers!=''?explode(';',$seo_text->manufacturers):array();
			$seo_text->properties=$seo_text->properties!=''?explode(';',$seo_text->properties):array();
			foreach($seo_text->categories as &$category)
			{
				$query=$this->db->getQuery(true);
				$query->select('title')->from('#__ksenmart_categories')->where('id='.$category);
				$this->db->setQuery($query);
				$title=$this->db->loadResult();	
				if (!empty($title))
					$category=$title;
			}
			foreach($seo_text->countries as &$country)
			{
				$query=$this->db->getQuery(true);
				$query->select('title')->from('#__ksenmart_countries')->where('id='.$country);
				$this->db->setQuery($query);
				$title=$this->db->loadResult();	
				if (!empty($title))
					$country=$title;
			}
			foreach($seo_text->manufacturers as &$manufacturer)
			{
				$query=$this->db->getQuery(true);
				$query->select('title')->from('#__ksenmart_manufacturers')->where('id='.$manufacturer);
				$this->db->setQuery($query);
				$title=$this->db->loadResult();	
				if (!empty($title))
					$manufacturer=$title;
			}
			$properties=array();
			foreach($seo_text->properties as $property)
			{
				$sql=$this->db->getQuery(true);
				$sql->select('title,property_id')->from('#__ksenmart_property_values')->where('id='.$property);
				$this->db->setQuery($sql);
				$property_value=$this->db->loadObject();
				if (!empty($property_value))
				{
					if (!isset($properties[$property_value->property_id]))
						$properties[$property_value->property_id]=array();
					if (!empty($property_value->title))
						$properties[$property_value->property_id][]=$property_value->title;
				}		
			}	
			$seo_text->properties=array();
			foreach($properties as $key=>$property_values)
			{
				$sql=$this->db->getQuery(true);
				$sql->select('title')->from('#__ksenmart_properties')->where('id='.$key);
				$this->db->setQuery($sql);
				$title=$this->db->loadResult();	
				if (!empty($title))
					$seo_text->properties[]=$title.'='.implode('+',$property_values);
			}
			$seo_text->text=mb_substr(strip_tags($seo_text->text),0,150);		
		}
        
        $this->onExecuteAfter('getListItems',array(&$seo_texts));
		return $seo_texts;
	}
	
	function getTotal()
	{
		$this->onExecuteBefore('getTotal');
		
		$total=$this->total;
		
		$this->onExecuteAfter('getTotal',array(&$total));
		return $total;
	}	
	
	function deleteListItems($ids)
	{
	    $this->onExecuteBefore('deleteListItems',array(&$ids));
        
		$table=$this->getTable('seotexts');
		foreach($ids as $id)
			$table->delete($id);
	    
        $this->onExecuteAfter('deleteListItems',array(&$ids));
		return true;
	}	
	
	function getSeoText()
	{
	    $this->onExecuteBefore('getSeoText');
        
		$id=JRequest::getInt('id');
		$seotext=KMSystem::loadDbItem($id,'seotexts');
		$seotext->categories=explode(';',$seotext->categories);
		$seotext->countries=explode(';',$seotext->countries);
		$seotext->manufacturers=explode(';',$seotext->manufacturers);
		$seotext->properties=explode(';',$seotext->properties);		
		
        $this->onExecuteAfter('getSeoText', array(&$seotext));
        return $seotext;
	}
	
	function saveSeoText($data)
	{
	    $this->onExecuteBefore('saveSeoText', array(&$data));
        
		$data['categories']=isset($data['categories'])?$data['categories']:array();
		sort($data['categories']);
		$data['categories']=implode(';',$data['categories']);
		$data['manufacturers']=isset($data['manufacturers'])?$data['manufacturers']:array();
		sort($data['manufacturers']);
		$data['manufacturers']=implode(';',$data['manufacturers']);
		$data['countries']=isset($data['countries'])?$data['countries']:array();
		sort($data['countries']);
		$data['countries']=implode(';',$data['countries']);
		$data['properties']=isset($data['properties'])?$data['properties']:array();
		sort($data['properties']);	
		$data['properties']=implode(';',$data['properties']);	
		
		$table = $this->getTable('seotexts');
		if (!$table->bindCheckStore($data)) {
			$this->setError($table->getError());
			return false;
		}
		$id = $table->id;	
		
		$on_close='window.parent.SeoTextsList.refreshList();';
		$return=array('id'=>$id,'on_close'=>$on_close);
		
        $this->onExecuteBefore('saveSeoText', array(&$return));
		return $return;	
	}
}