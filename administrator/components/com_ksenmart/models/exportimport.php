<?php	 		 		 	
defined( '_JEXEC' ) or die( '=;)' );
jimport( 'joomla.application.component.modelkmadmin' );

class KsenMartModelExportImport extends JModelKMAdmin {
	
	function __construct() {
		parent::__construct();
	}
	
	function populateState()
	{
	    $this->onExecuteBefore('populateState');
        
		$app = JFactory::getApplication();
		
		$params = JComponentHelper::getParams('com_ksenmart');
		
		$type=$app->getUserStateFromRequest($this->context.'.type', 'type','text');
		$this->setState('type',$type);
		$this->context.='.'.$type;
		
		$encoding=$app->getUserStateFromRequest($this->context.'.encoding', 'encoding','cp1251');
		$this->setState('encoding',$encoding);
        
        $this->onExecuteAfter('populateState');
	}	
	
	function getProperties()
	{
	    $this->onExecuteBefore('getProperties');
        
		$query=$this->db->getQuery(true);
		$query->select('*')->from('#__ksenmart_properties')->order('ordering');
		$this->db->setQuery($query);
		$properties=$this->db->loadObjectList();
		
        $this->onExecuteAfter('getProperties', array(&$properties));
		return $properties;
	}
	
	function getCSVOptions()
	{
	    $this->onExecuteBefore('getCSVOptions');
        
		$encoding=$this->getState('encoding');
		if ($encoding=='cp1251')
			setLocale(LC_ALL, 'ru_RU.CP1251');
			
		$f = fopen(JPATH_COMPONENT.'/tmp/import.csv', "rt") or die("Ошибка!");
		$data=fgetcsv($f,10000,";");
		fclose($f);
		$options='<option value=""></option>';
		for($k=0;$k<count($data);$k++)
			if ($data[$k]!='')	
				$options.='<option value="'.$k.'">'.$this->encode($data[$k]).'</option>';	
        
        $this->onExecuteAfter('getCSVOptions', array(&$options));
		return $options;
	}
	
	function uploadImportCSVFile()
	{
	    $this->onExecuteBefore('uploadImportCSVFile');
        
		if (!isset($_FILES['csvfile']))
			return false;
		if ($_FILES['csvfile']['tmp_name']=='')
			return false;			
		if (substr($_FILES['csvfile']['name'],strlen($_FILES['csvfile']['name'])-4,4)!='.csv')
			return false;	
		if (file_exists(JPATH_COMPONENT.'/tmp/import.csv'))
			unlink(JPATH_COMPONENT.'/tmp/import.csv');
		copy($_FILES['csvfile']['tmp_name'],JPATH_COMPONENT.'/tmp/import.csv');	
	    
        $this->onExecuteAfter('uploadImportCSVFile');
		return true;
	}
	
	function getImportInfo()
	{
	    $this->onExecuteBefore('getImportInfo');
        
		$encoding=$this->getState('encoding');
		if ($encoding=='cp1251')
			setLocale(LC_ALL, 'ru_RU.CP1251');	
			
		if ($_FILES['photos_zip']['tmp_name']!='')
		{
			$import_dir=JPATH_ROOT.'/media/ksenmart/import/';
			JFolder::delete($import_dir);	
			JFolder::create($import_dir,0777);	
			copy($_FILES['photos_zip']['tmp_name'],$import_dir.'import.zip');
			$result = JArchive::extract(JPath::clean($import_dir.'import.zip'), JPath::clean($import_dir));
		}				
		$unic=JRequest::getVar('unic');
		$f = fopen(JPATH_COMPONENT.'/tmp/import.csv', "rt") or die("Ошибка!");
		$info=array('insert'=>'','update'=>'');
		for ($k=0; $data=fgetcsv($f,10000,";"); $k++)
		{
			if ($k==0)
			{
				$headers=$data;
				continue;
			}	
			$product_data=array();
			if ($k>0)
			{
				if (isset($_POST['title']) && $_POST['title']!='')
					$product_data['title']=$this->encode($data[$_POST['title']]);
				if (isset($_POST['parent']) && $_POST['parent']!='')
					$product_data['parent']=$this->encode($data[$_POST['parent']]);					
				if (isset($_POST['categories']) && $_POST['categories']!='')
					$product_data['categories']=$this->encode($data[$_POST['categories']]);
				if (isset($_POST['childs_group']) && $_POST['childs_group']!='')
					$product_data['childs_group']=$this->encode($data[$_POST['childs_group']]);					
				if (isset($_POST['price']) && $_POST['price']!='')
					$product_data['price']=(float)str_replace(' ','',$this->encode($data[$_POST['price']]));
				if (isset($_POST['promotion_price']) && $_POST['promotion_price']!='')
					$product_data['promotion_price']=(float)str_replace(' ','',$this->encode($data[$_POST['promotion_price']]));	
				if (isset($_POST['price_type']) && $_POST['price_type']!='')
					$product_data['price_type']=str_replace(' ','',$this->encode($data[$_POST['price_type']]));	
				if (isset($_POST['product_code']) && $_POST['product_code']!='')					
					$product_data['product_code']=$this->encode($data[$_POST['product_code']]);
				if (isset($_POST['product_packaging']) && $_POST['product_packaging']!='')
					$product_data['product_packaging']=(float)str_replace(' ','',$this->encode($data[$_POST['product_packaging']]));					
				if (isset($_POST['product_unit']) && $_POST['product_unit']!='')
					$product_data['product_unit']=str_replace(' ','',$this->encode($data[$_POST['product_unit']]));	
				if (isset($_POST['in_stock']) && $_POST['in_stock']!='')
					$product_data['in_stock']=(float)$this->encode($data[$_POST['in_stock']]);
				if (isset($_POST['promotion']) && $_POST['promotion']!='')
					$product_data['promotion']=$this->encode($data[$_POST['promotion']]);					
				if (isset($_POST['manufacturer']) && $_POST['manufacturer']!='')
					$product_data['manufacturer']=$this->encode($data[$_POST['manufacturer']]);
				if (isset($_POST['country']) && $_POST['country']!='')
					$product_data['country']=$this->encode($data[$_POST['country']]);						
				if (isset($_POST['content']) && $_POST['content']!='')
					$product_data['content']=$this->encode($data[$_POST['content']]);
				if (isset($_POST['photos']) && $_POST['photos']!='')
					$product_data['photos']=$this->encode($data[$_POST['photos']]);		
				if (isset($_POST['metatitle']) && $_POST['metatitle']!='')
					$product_data['metatitle']=$this->encode($data[$_POST['metatitle']]);	
				else
					$product_data['metatitle']='';
				if (isset($_POST['metadescription']) && $_POST['metadescription']!='')
					$product_data['metadescription']=$this->encode($data[$_POST['metadescription']]);	
				else
					$product_data['metadescription']='';					
				if (isset($_POST['metakeywords']) && $_POST['metakeywords']!='')
					$product_data['metakeywords']=$this->encode($data[$_POST['metakeywords']]);		
				else
					$product_data['metakeywords']='';	
				$product_data['type']='product';
				$query=$this->db->getQuery(true);
				$query->select('*')->from('#__ksenmart_properties')->order('ordering');
				$this->db->setQuery($query);
				$properties=$this->db->loadObjectList();	
				foreach($properties as $property)	
					if (isset($_POST['property_'.$property->id]) && $_POST['property_'.$property->id]!='')
						$product_data['property_'.$property->id]=$this->encode($data[$_POST['property_'.$property->id]]);
				$product=array();	
				if (isset($product_data['parent']) && $product_data['parent']!='')
				{	
					$query=$this->db->getQuery(true);
					$query->select('id')->from('#__ksenmart_products')->where($unic.'='.$this->db->quote($product_data['parent']))->where('parent_id=0');
					$this->db->setQuery($query);
					$parent_id=$this->db->loadResult();	
					if (empty($parent_id))	
						$product_data['parent']=0;		
					else
					{
						$product_data['type']='child';
						$product_data['parent']=$parent_id;	
						$query=$this->db->getQuery(true);
						$query->update('#__ksenmart_products')->set('is_parent=1')->where('id='.$parent_id);
						$this->db->setQuery($query);
						$this->db->query();						
					}
				}
				else
					$product_data['parent']=0;					
				if ($unic!='')		
				{
					$query=$this->db->getQuery(true);
					$query->select('*')->from('#__ksenmart_products')->where($unic.'='.$this->db->quote($product_data[$unic]))->where('parent_id='.$product_data['parent']);				
					$this->db->setQuery($query);
					$product=$this->db->loadObjectList();
				}	
				$query=$this->db->getQuery(true);
				$query->select('id')->from('#__ksenmart_currencies')->where('`default`=1');
				$this->db->setQuery($query);
				$price_type=$this->db->loadResult();
				if (count($product)==0)
				{
					$alias = KMFunctions::GenAlias($product_data['title']);
					if (isset($product_data['promotion_price']) && $product_data['promotion_price']!='')
					{
						$product_data['promotion']=1;
						$product_data['old_price']=$product_data['price'];
						$product_data['price']=$product_data['promotion_price'];
					}	
					else
					{
						$product_data['promotion']=0;
						$product_data['old_price']=0;
					}	
					if (!isset($product_data['product_packaging']))
						$product_data['product_packaging']=1;						
					$values="parent_id,title,price,type,product_code,published,date_added,alias,promotion,old_price,product_packaging";
					$vals=array();
					if (!isset($product_data['price_type']))
						$product_data['price_type']=$price_type;					
					if (!isset($product_data['in_stock']))
						$product_data['in_stock']=1;
					if (!isset($product_data['content']))
						$product_data['content']='';
					$values.=",in_stock,content,price_type,metatitle,metadescription,metakeywords";
					$vals[]=$product_data['parent'];
					$vals[]=$this->db->quote($product_data['title']);
					$vals[]=$product_data['price'];
					$vals[]=$this->db->quote($product_data['type']);
					$vals[]=$this->db->quote($product_data['product_code']);
					$vals[]=1;
					$vals[]='NOW()';
					$vals[]=$this->db->quote($alias);
					$vals[]=$product_data['promotion'];
					$vals[]=$product_data['old_price'];
					$vals[]=$product_data['product_packaging'];
					$vals[]=$product_data['in_stock'];
					$vals[]=$this->db->quote($product_data['content']);
					$vals[]=$product_data['price_type'];
					$vals[]=$this->db->quote($product_data['metatitle']);
					$vals[]=$this->db->quote($product_data['metadescription']);
					$vals[]=$this->db->quote($product_data['metakeywords']);
					if (isset($product_data['childs_group']) && $product_data['childs_group']!='' && $product_data['parent']!=0)
					{	
						$query=$this->db->getQuery(true);
						$query->select('*')->from('#__ksenmart_products_child_groups')
						->where('title like '.$this->db->quote($product_data['childs_group']))
						->where('product_id='.$product_data['parent']);
						$this->db->setQuery($query);
						$childs_group=$this->db->loadObject();	
						if (count($childs_group)==0)
						{
							$qvalues=array($this->db->quote($product_data['childs_group']),$product_data['parent']);
							$query=$this->db->getQuery(true);
							$query->insert('#__ksenmart_products_child_groups')->columns('title,product_id')->values(implode(',', $qvalues));
							$this->db->setQuery($query);
							$this->db->query();
							$childs_group_id=$this->db->insertid();
							$vals[]=$childs_group_id;
						}
						else
							$vals[]=$childs_group->id;
						$values.=",childs_group";							
					}
					else
						$product_data['childs_group']=0;					
					if (isset($product_data['country']) && $product_data['country']!='')
					{
						$query=$this->db->getQuery(true);
						$query->select('*')->from('#__ksenmart_countries')
						->where('title='.$this->db->quote($product_data['country']));
						$this->db->setQuery($query);
						$country=$this->db->loadObject();
						if (count($country)==0)
						{
							$alias = KMFunctions::GenAlias($product_data['country']);
							$qvalues=array($this->db->quote($product_data['country']),$this->db->quote($alias),1,$this->db->quote($product_data['country']));
							$query=$this->db->getQuery(true);
							$query->insert('#__ksenmart_countries')->columns('title,alias,published,metatitle')->values(implode(',', $qvalues));							
							$this->db->setQuery($query);
							$this->db->query();
							$country_id=$this->db->insertid();
							$product_data['country']=$country_id;
						}
						else
							$product_data['country']=$country->id;
					}	
					else
						$product_data['country']=0;
					if (isset($product_data['product_unit']) && $product_data['product_unit']!='')
					{
						$query=$this->db->getQuery(true);
						$query->select('*')->from('#__ksenmart_product_units')
						->where('form1='.$this->db->quote($product_data['product_unit']));					
						$this->db->setQuery($query);
						$unit=$this->db->loadObject();
						if (count($unit)==0)
						{
							$qvalues=array($this->db->quote($product_data['product_unit']),$this->db->quote($product_data['product_unit']),$this->db->quote($product_data['product_unit']));
							$query=$this->db->getQuery(true);
							$query->insert('#__ksenmart_product_units')->columns('form1,form2,form5')->values(implode(',', $qvalues));							
							$this->db->setQuery($query);
							$this->db->query();
							$unit_id=$this->db->insertid();
							$vals[]=$unit_id;
						}
						else
							$vals[]=$unit->id;
						$values.=",product_unit";
					}					
					if (isset($product_data['manufacturer']) && $product_data['manufacturer']!='')
					{
						$query=$this->db->getQuery(true);
						$query->select('*')->from('#__ksenmart_manufacturers')
						->where('title='.$this->db->quote($product_data['manufacturer']));					
						$this->db->setQuery($query);
						$manufacturer=$this->db->loadObject();
						if (count($manufacturer)==0)
						{
							$alias = KMFunctions::GenAlias($product_data['manufacturer']);
							$qvalues=array($this->db->quote($product_data['manufacturer']),$this->db->quote($alias),$this->db->quote($product_data['country']),1,$this->db->quote($product_data['manufacturer']));
							$query=$this->db->getQuery(true);
							$query->insert('#__ksenmart_manufacturers')->columns('title,alias,country,published,metatitle')->values(implode(',', $qvalues));
							$this->db->setQuery($query);
							$this->db->query();
							$manufacturer_id=$this->db->insertid();
							$vals[]=$manufacturer_id;
						}
						else
							$vals[]=$manufacturer->id;
						$values.=",manufacturer";
					}					
					$categories=explode(';',$product_data['categories']);
					$prd_cats=array();
					foreach($categories as $cats)
					{
						$parent=0;
						$prd_cat=0;
						$cats=explode(':',$cats);
						foreach($cats as $cat)
						{
							$cat=trim($cat);
							if ($cat!='')
							{
								$query=$this->db->getQuery(true);
								$query->select('*')->from('#__ksenmart_categories')
								->where('title='.$this->db->quote($cat))->where('parent='.$parent);								
								$this->db->setQuery($query);
								$category=$this->db->loadObject();
								if (!$category)
								{
									$alias = KMFunctions::GenAlias($cat);
									$qvalues=array($this->db->quote($cat),$this->db->quote($alias),$parent,1);
									$query=$this->db->getQuery(true);
									$query->insert('#__ksenmart_categories')->columns('title,alias,parent,published')->values(implode(',', $qvalues));
									$this->db->setQuery($query);
									$this->db->query();
									$prd_cat=$this->db->insertid();
									$parent=$prd_cat;
								}
								else
								{
									$prd_cat=$category->id;
									$parent=$prd_cat;			
								}
								$prd_cats[]=$prd_cat;
							}	
						}
					}	
					$query=$this->db->getQuery(true);		
					$query->update('#__ksenmart_products')->set('ordering=ordering+1');
					$this->db->setQuery($query);
					$this->db->query();					
					$query=$this->db->getQuery(true);
					$query->insert('#__ksenmart_products')->columns($values)->values(implode(',', $vals));
					$this->db->setQuery($query);
					$this->db->query();
					$product_id=$this->db->insertid();
					$is_default=true;
					if ($product_data['parent']!=0)
					{
						$prd_cats=array();
						$query=$this->db->getQuery(true);
						$query->select('*')->from('#__ksenmart_products_categories')->where('product_id='.$product_data['parent']);								
						$this->db->setQuery($query);
						$cats=$this->db->loadObjectList();	
						foreach($cats as $cat)
							$prd_cats[]=$cat->category_id;
					}
					foreach($prd_cats as $prd_cat)
					{
						$qvalues=array($product_id,$prd_cat,(int)$is_default);
						$query=$this->db->getQuery(true);
						$query->insert('#__ksenmart_products_categories')->columns('product_id,category_id,is_default')->values(implode(',', $qvalues));					
						$this->db->setQuery($query);
						$this->db->query();
						$is_default=false;						
					}						
					if (isset($product_data['photos']))
					{
						$product_data['photos']=explode(',',$product_data['photos']);
						$i=1;
						foreach($product_data['photos'] as $photo)
						{
							$photo=trim($photo);
							if ($photo!='' && file_exists(JPATH_ROOT.'/media/ksenmart/import/'.$photo))
							{
								$file=basename($photo);
								$nameParts = explode('.', $file);
								$file = microtime(true).'.'.$nameParts[count($nameParts)-1];								
								if (copy(JPATH_ROOT.'/media/ksenmart/import/'.$photo,JPATH_ROOT.'/media/ksenmart/images/products/original/'.$file))
								{
									$mime=mime_content_type(JPATH_ROOT.'/media/ksenmart/images/products/original/'.$file);
									$qvalues=array($product_id,$this->db->quote('image'),$this->db->quote('product'),$this->db->quote('products'),$this->db->quote($file),$this->db->quote($mime),$this->db->quote(''),$i);
									$query=$this->db->getQuery(true);
									$query->insert('#__ksenmart_files')->columns('owner_id,media_type,owner_type,folder,filename,mime_type,title,ordering')->values(implode(',', $qvalues));					
									$this->db->setQuery($query);									
									$this->db->query();
									$i++;
								}
							}
						}	
					}
					foreach($properties as $property)
					{
						if (isset($product_data['property_'.$property->id]))
						{
							switch ($property->type)
							{
								case 'text':
									if ($product_data['property_'.$property->id]!='')
									{
										$query=$this->db->getQuery(true);
										$query->select('*')->from('#__ksenmart_property_values')->where('property_id='.$property->id)->where('title='.$this->db->quote($product_data['property_'.$property->id]));										
										$this->db->setQuery($query);
										$prop_value=$this->db->loadObject();
										if (count($prop_value)==0)
										{
											$alias = KMFunctions::GenAlias($product_data['property_'.$property->id]);
											$qvalues=array($property->id,$this->db->quote($product_data['property_'.$property->id]),$alias);
											$query=$this->db->getQuery(true);
											$query->insert('#__ksenmart_property_values')->columns('property_id,title,alias')->values(implode(',', $qvalues));					
											$this->db->setQuery($query);
											$this->db->query();	
											$prop_value_id=$this->db->insertid();
										}
										else
											$prop_value_id=$prop_value->id;
										$qvalues=array($product_id,$property->id,$prop_value_id,$this->db->quote($product_data['property_'.$property->id]));
										$query=$this->db->getQuery(true);
										$query->insert('#__ksenmart_product_properties_values')->columns('product_id,property_id,value_id,text')->values(implode(',', $qvalues));					
										$this->db->setQuery($query);
										$this->db->query();	
									}		
									break;
								case 'checkbox':
									if ($product_data['property_'.$property->id]!='')
									{
										$query=$this->db->getQuery(true);
										$query->select('*')->from('#__ksenmart_property_values')->where('property_id='.$property->id)->where('title='.$this->db->quote('1'));										
										$this->db->setQuery($query);
										$prop_value=$this->db->loadObject();
										if (count($prop_value)==0)
										{
											$alias = KMFunctions::GenAlias($property->id);
											$qvalues=array($property->id,$this->db->quote('1'),$alias);
											$query=$this->db->getQuery(true);
											$query->insert('#__ksenmart_property_values')->columns('property_id,title,alias')->values(implode(',', $qvalues));					
											$this->db->setQuery($query);
											$this->db->query();	
											$prop_value_id=$this->db->insertid();
										}
										else
											$prop_value_id=$prop_value->id;
										$qvalues=array($product_id,$property->id,$prop_value_id,$this->db->quote('1'));
										$query=$this->db->getQuery(true);
										$query->insert('#__ksenmart_product_properties_values')->columns('product_id,property_id,value_id,text')->values(implode(',', $qvalues));					
										$this->db->setQuery($query);
										$this->db->query();											
									}		
									break;	
								default:
									$prop_vals=explode(',',$product_data['property_'.$property->id]);
									$prop_values='';
									foreach($prop_vals as $prop_val)
									{
										if ($prop_val!='')
										{
											$query=$this->db->getQuery(true);
											$query->select('*')->from('#__ksenmart_property_values')->where('property_id='.$property->id)->where('title='.$this->db->quote($prop_val));										
											$this->db->setQuery($query);
											$prop_value=$this->db->loadObject();
											if (count($prop_value)==0)
											{
												$alias = KMFunctions::GenAlias($prop_val);
												$qvalues=array($property->id,$this->db->quote($prop_val),$alias);
												$query=$this->db->getQuery(true);
												$query->insert('#__ksenmart_property_values')->columns('property_id,title,alias')->values(implode(',', $qvalues));					
												$this->db->setQuery($query);
												$this->db->query();	
												$prop_value_id=$this->db->insertid();
											}
											else
												$prop_value_id=$prop_value->id;
											$qvalues=array($product_id,$property->id,$prop_value_id);
											$query=$this->db->getQuery(true);
											$query->insert('#__ksenmart_product_properties_values')->columns('product_id,property_id,value_id')->values(implode(',', $qvalues));					
											$this->db->setQuery($query);
											$this->db->query();													
										}	
									}	
							}
							if ($product_data['property_'.$property->id]!='')
							{							
								foreach($prd_cats as $prd_cat)
								{
									$query=$this->db->getQuery(true);
									$query->select('id')->from('#__ksenmart_product_categories_properties')->where('category_id='.$prd_cat)->where('property_id='.$property->id);										
									$this->db->setQuery($query);
									$res=$this->db->loadResult();
									if (empty($res))
									{
										$qvalues=array($prd_cat,$property->id);
										$query=$this->db->getQuery(true);
										$query->insert('#__ksenmart_product_categories_properties')->columns('category_id,property_id')->values(implode(',', $qvalues));					
										$this->db->setQuery($query);
										$this->db->query();									
									}
								}
							}	
						}
					}	
					$info['insert']++;
				}
				else
				{
					$categories=array();
					$product_id=$product[0]->id;
					if (isset($product_data['photos']) && $product_data['photos']!='')
					{
						$query = $this->db->getQuery(true);
						$query->select('*')->from('#__ksenmart_files')->where(array("media_type='image'","owner_type='product'", "owner_id=".$product_id ))
						->order('ordering');
						$this->db->setQuery($query);
						$images = $this->db->loadObjectList('id');
						$i=count($images);
						//foreach($images as $image)
							//$this->delPhoto($image->filename,$image->folder);					
						$product_data['photos']=explode(',',$product_data['photos']);
						//$i=1;
						foreach($product_data['photos'] as $photo)
						{
							$photo=trim($photo);
							if ($photo!='' && file_exists(JPATH_ROOT.'/media/ksenmart/import/'.$photo))
							{
								$file=basename($photo);
								if (copy(JPATH_ROOT.'/media/ksenmart/import/'.$photo,JPATH_ROOT.'/media/ksenmart/images/products/original/'.$file))
								{
									$mime=mime_content_type(JPATH_ROOT.'/media/ksenmart/images/products/original/'.$file);
									$qvalues=array($product_id,$this->db->quote('image'),$this->db->quote('product'),$this->db->quote('products'),$this->db->quote($file),$this->db->quote($mime),$this->db->quote(''),$i);
									$query=$this->db->getQuery(true);
									$query->insert('#__ksenmart_files')->columns('owner_id,media_type,owner_type,folder,filename,mime_type,title,ordering')->values(implode(',', $qvalues));					
									$this->db->setQuery($query);
									$this->db->query();
									$i++;
								}
							}
						}	
					}			
					$to_update=array();
					$to_update[]='date_added=NOW()';
					if (isset($product_data['title']))
						$to_update[]='title='.$this->db->quote($product_data['title']);
					if (isset($product_data['product_code']))
						$to_update[]='product_code='.$this->db->quote($product_data['product_code']);						
					if (isset($product_data['in_stock']))
						$to_update[]='in_stock='.$this->db->quote($product_data['in_stock']);						
					if (isset($product_data['content']))
						$to_update[]='content='.$this->db->quote($product_data['content']);		
					if (isset($product_data['introcontent']))
						$to_update[]='introcontent='.$this->db->quote($product_data['introcontent']);	
					if (isset($product_data['product_packaging']))
						$to_update[]='product_packaging='.$this->db->quote($product_data['product_packaging']);	
					if ($product_data['metatitle']!='')
						$to_update[]='metatitle='.$this->db->quote($product_data['metatitle']);		
					if ($product_data['metadescription']!='')
						$to_update[]='metadescription='.$this->db->quote($product_data['metadescription']);	
					if ($product_data['metakeywords']!='')
						$to_update[]='metakeywords='.$this->db->quote($product_data['metakeywords']);							
					if (isset($product_data['promotion_price']) && $product_data['promotion_price']!='')
					{
						$product_data['promotion']=1;
						$product_data['old_price']=$product_data['price'];
						$product_data['price']=$product_data['promotion_price'];
					}	
					else
					{
						$product_data['promotion']=0;
						$product_data['old_price']=0;
					}	
					if (isset($product_data['price']))
						$to_update[]='price='.$this->db->quote($product_data['price']);	
					if (isset($product_data['old_price']))
						$to_update[]='old_price='.$this->db->quote($product_data['old_price']);	
					if (isset($product_data['promotion']))
						$to_update[]='promotion='.$this->db->quote($product_data['promotion' ]);	
					if (isset($product_data['country']) && $product_data['country']!='')
					{
						$query=$this->db->getQuery(true);
						$query->select('*')->from('#__ksenmart_countries')
						->where('title='.$this->db->quote($product_data['country']));
						$this->db->setQuery($query);
						$country=$this->db->loadObject();
						if (count($country)==0)
						{
							$alias = KMFunctions::GenAlias($product_data['country']);
							$qvalues=array($this->db->quote($product_data['country']),$this->db->quote($alias),1,$this->db->quote($product_data['country']));
							$query=$this->db->getQuery(true);
							$query->insert('#__ksenmart_countries')->columns('title,alias,published,metatitle')->values(implode(',', $qvalues));							
							$this->db->setQuery($query);
							$this->db->query();
							$country_id=$this->db->insertid();
							$product_data['country']=$country_id;
						}
						else
							$product_data['country']=$country->id;
					}	
					else
						$product_data['country']=0;					
					if (isset($product_data['childs_group']) && $product_data['childs_group']!='' && $product_data['parent']!=0)
					{	
						$query=$this->db->getQuery(true);
						$query->select('*')->from('#__ksenmart_products_child_groups')
						->where('title like '.$this->db->quote($product_data['childs_group']))
						->where('product_id='.$product_data['parent']);
						$this->db->setQuery($query);
						$childs_group=$this->db->loadObject();	
						if (count($childs_group)==0)
						{
							$qvalues=array($this->db->quote($product_data['childs_group']),$product_data['parent']);
							$query=$this->db->getQuery(true);
							$query->insert('#__ksenmart_products_child_groups')->columns('title,product_id')->values(implode(',', $qvalues));
							$this->db->setQuery($query);
							$this->db->query();
							$childs_group_id=$this->db->insertid();
							$to_update[]='childs_group='.$childs_group_id;	
						}
						else
							$to_update[]='childs_group='.$childs_group->id;	
					}
					else
						$to_update[]='childs_group='.$product[0]->childs_group;	
					if (isset($product_data['product_unit']) && $product_data['product_unit']!='')
					{
						$query=$this->db->getQuery(true);
						$query->select('*')->from('#__ksenmart_product_units')
						->where('form1='.$this->db->quote($product_data['product_unit']));					
						$this->db->setQuery($query);
						$unit=$this->db->loadObject();
						if (count($unit)==0)
						{
							$qvalues=array($this->db->quote($product_data['product_unit']),$this->db->quote($product_data['product_unit']),$this->db->quote($product_data['product_unit']));
							$query=$this->db->getQuery(true);
							$query->insert('#__ksenmart_product_units')->columns('form1,form2,form5')->values(implode(',', $qvalues));							
							$this->db->setQuery($query);
							$this->db->query();
							$unit_id=$this->db->insertid();
						}
						else
							$unit_id=$unit->id;
						$to_update[]='product_unit='.$unit_id;	
					}		
					if (isset($product_data['manufacturer']) && $product_data['manufacturer']!='')
					{
						$query=$this->db->getQuery(true);
						$query->select('*')->from('#__ksenmart_manufacturers')
						->where('title='.$this->db->quote($product_data['manufacturer']));					
						$this->db->setQuery($query);
						$manufacturer=$this->db->loadObject();
						if (count($manufacturer)==0)
						{
							$alias = KMFunctions::GenAlias($product_data['manufacturer']);
							$qvalues=array($this->db->quote($product_data['manufacturer']),$this->db->quote($alias),$this->db->quote($product_data['country']),1,$this->db->quote($product_data['manufacturer']));
							$query=$this->db->getQuery(true);
							$query->insert('#__ksenmart_manufacturers')->columns('title,alias,country,published,metatitle')->values(implode(',', $qvalues));
							$this->db->setQuery($query);
							$this->db->query();
							$manufacturer_id=$this->db->insertid();
						}
						else
							$manufacturer_id=$manufacturer->id;
						$to_update[]='manufacturer='.$manufacturer_id;
					}					
					$categories=explode(';',$product_data['categories']);
					$prd_cats=array();
					foreach($categories as $cats)
					{
						$parent=0;
						$prd_cat=0;
						$cats=explode(':',$cats);
						foreach($cats as $cat)
						{
							$cat=trim($cat);
							if ($cat!='')
							{
								$query=$this->db->getQuery(true);
								$query->select('*')->from('#__ksenmart_categories')
								->where('title='.$this->db->quote($cat))->where('parent='.$parent);								
								$this->db->setQuery($query);
								$category=$this->db->loadObject();
								if (!$category)
								{
									$alias = KMFunctions::GenAlias($cat);
									$qvalues=array($this->db->quote($cat),$this->db->quote($alias),$parent,1);
									$query=$this->db->getQuery(true);
									$query->insert('#__ksenmart_categories')->columns('title,alias,parent,published')->values(implode(',', $qvalues));
									$this->db->setQuery($query);
									$this->db->query();
									$prd_cat=$this->db->insertid();
									$parent=$prd_cat;
								}
								else
								{
									$prd_cat=$category->id;
									$parent=$prd_cat;			
								}
								$prd_cats[]=$prd_cat;
							}	
						}
					}	
					if ($product_data['parent']!=0)
					{
						$prd_cats=array();
						$query=$this->db->getQuery(true);
						$query->select('*')->from('#__ksenmart_products_categories')->where('product_id='.$product_data['parent']);								
						$this->db->setQuery($query);
						$cats=$this->db->loadObjectList();	
						foreach($cats as $cat)
							$prd_cats[]=$cat->category_id;
					}
					foreach($prd_cats as $prd_cat)
					{
						$query=$this->db->getQuery(true);
						$query->select('*')->from('#__ksenmart_products_categories')->where('product_id='.$product_id)->where('category_id='.$prd_cat);						
						$this->db->setQuery($query);
						$db_cat=$this->db->loadObject();	
						if (count($db_cat)==0)
						{					
							$qvalues=array($product_id,$prd_cat);
							$query=$this->db->getQuery(true);
							$query->insert('#__ksenmart_products_categories')->columns('product_id,category_id')->values(implode(',', $qvalues));					
							$this->db->setQuery($query);
							$this->db->query();
							$is_default=false;	
						}
					}					
					$query=$this->db->getQuery(true);
					$query->update('#__ksenmart_products')->set($to_update)->where('id='.$product_id);
					$this->db->setQuery($query);
					$this->db->query();
					foreach($properties as $property)
					{
						if (isset($product_data['property_'.$property->id]) && $product_data['property_'.$property->id]!='')
						{
							$query=$this->db->getQuery(true);
							$query->delete('#__ksenmart_product_properties_values')->where('product_id='.$product_id)->where('property_id='.$property->id);
							$this->db->setQuery($query);
							$this->db->query();						
							switch ($property->type)
							{
								case 'text':
									if ($product_data['property_'.$property->id]!='')
									{
										$query=$this->db->getQuery(true);
										$query->select('*')->from('#__ksenmart_property_values')->where('property_id='.$property->id)->where('title='.$this->db->quote($product_data['property_'.$property->id]));										
										$this->db->setQuery($query);
										$prop_value=$this->db->loadObject();
										if (count($prop_value)==0)
										{
											$alias = KMFunctions::GenAlias($product_data['property_'.$property->id]);
											$qvalues=array($property->id,$this->db->quote($product_data['property_'.$property->id]),$alias);
											$query=$this->db->getQuery(true);
											$query->insert('#__ksenmart_property_values')->columns('property_id,title,alias')->values(implode(',', $qvalues));					
											$this->db->setQuery($query);
											$this->db->query();	
											$prop_value_id=$this->db->insertid();
										}
										else
											$prop_value_id=$prop_value->id;
										$qvalues=array($product_id,$property->id,$prop_value_id,$this->db->quote($product_data['property_'.$property->id]));
										$query=$this->db->getQuery(true);
										$query->insert('#__ksenmart_product_properties_values')->columns('product_id,property_id,value_id,text')->values(implode(',', $qvalues));					
										$this->db->setQuery($query);
										$this->db->query();	
									}		
									break;
								case 'checkbox':
									if ($product_data['property_'.$property->id]!='')
									{
										$query=$this->db->getQuery(true);
										$query->select('*')->from('#__ksenmart_property_values')->where('property_id='.$property->id)->where('title='.$this->db->quote('1'));										
										$this->db->setQuery($query);
										$prop_value=$this->db->loadObject();
										if (count($prop_value)==0)
										{
											$alias = KMFunctions::GenAlias($property->id);
											$qvalues=array($property->id,$this->db->quote('1'),$alias);
											$query=$this->db->getQuery(true);
											$query->insert('#__ksenmart_property_values')->columns('property_id,title,alias')->values(implode(',', $qvalues));					
											$this->db->setQuery($query);
											$this->db->query();	
											$prop_value_id=$this->db->insertid();
										}
										else
											$prop_value_id=$prop_value->id;
										$qvalues=array($product_id,$property->id,$prop_value_id,$this->db->quote('1'));
										$query=$this->db->getQuery(true);
										$query->insert('#__ksenmart_product_properties_values')->columns('product_id,property_id,value_id,text')->values(implode(',', $qvalues));					
										$this->db->setQuery($query);
										$this->db->query();											
									}		
									break;	
								default:
									$prop_vals=explode(',',$product_data['property_'.$property->id]);
									$prop_values='';
									foreach($prop_vals as $prop_val)
									{
										if ($prop_val!='')
										{
											$query=$this->db->getQuery(true);
											$query->select('*')->from('#__ksenmart_property_values')->where('property_id='.$property->id)->where('title='.$this->db->quote($prop_val));										
											$this->db->setQuery($query);
											$prop_value=$this->db->loadObject();
											if (count($prop_value)==0)
											{
												$alias = KMFunctions::GenAlias($prop_val);
												$qvalues=array($property->id,$this->db->quote($prop_val),$alias);
												$query=$this->db->getQuery(true);
												$query->insert('#__ksenmart_property_values')->columns('property_id,title,alias')->values(implode(',', $qvalues));					
												$this->db->setQuery($query);
												$this->db->query();	
												$prop_value_id=$this->db->insertid();
											}
											else
												$prop_value_id=$prop_value->id;
											$qvalues=array($product_id,$property->id,$prop_value_id);
											$query=$this->db->getQuery(true);
											$query->insert('#__ksenmart_product_properties_values')->columns('product_id,property_id,value_id')->values(implode(',', $qvalues));					
											$this->db->setQuery($query);
											$this->db->query();													
										}	
									}	
							}
							if ($product_data['property_'.$property->id]!='')
							{							
								foreach($prd_cats as $prd_cat)
								{
									$query=$this->db->getQuery(true);
									$query->select('id')->from('#__ksenmart_product_categories_properties')->where('category_id='.$prd_cat)->where('property_id='.$property->id);										
									$this->db->setQuery($query);
									$res=$this->db->loadResult();
									if (empty($res))
									{
										$qvalues=array($prd_cat,$property->id);
										$query=$this->db->getQuery(true);
										$query->insert('#__ksenmart_product_categories_properties')->columns('category_id,property_id')->values(implode(',', $qvalues));					
										$this->db->setQuery($query);
										$this->db->query();									
									}
								}
							}	
						}
					}					
					$info['update']++;
				}
			}
		}
		fclose($f);	
		$dir=scandir(JPATH_COMPONENT.'/tmp/');
		foreach($dir as $d)
			if ($d!='.' && $d!='..')
				unlink(JPATH_COMPONENT.'/tmp/'.$d);
                
        $this->onExecuteAfter('getImportInfo', array(&$info));
		return $info;	
	}
	
	function encode($string)
	{
	    $this->onExecuteBefore('encode', array(&$string));
        
		$encoding=$this->getState('encoding');
		if ($encoding=='cp1251')
			$string=trim(iconv('WINDOWS-1251','UTF-8',$string));
            
        $this->onExecuteAfter('encode', array(&$string));
		return $string;	
	}	
	
	function saveYandexmarket($data)
	{
	    $this->onExecuteBefore('saveYandexmarket', array(&$data));
        
		$categories=isset($data['categories'])?json_encode($data['categories']):'{}';
		$shopname=$data['shopname'];
		$company=$data['company'];

		$query = $this->db->getQuery(true);
		$query->update('#__ksenmart_yandeximport')->set('value='.$this->db->quote($categories))->where('setting='.$this->db->quote('categories'));
		$this->db->setQuery($query);
		$this->db->query();				

		$query = $this->db->getQuery(true);
		$query->update('#__ksenmart_yandeximport')->set('value='.$this->db->quote($shopname))->where('setting='.$this->db->quote('shopname'));
		$this->db->setQuery($query);
		$this->db->query();				

		$query = $this->db->getQuery(true);
		$query->update('#__ksenmart_yandeximport')->set('value='.$this->db->quote($company))->where('setting='.$this->db->quote('company'));
		$this->db->setQuery($query);
		$this->db->query();
        
        $this->onExecuteAfter('saveYandexmarket', array(&$data));			
	}	
	
	function delPhoto($filename,$folder)
	{
	    $this->onExecuteBefore('delPhoto', array(&$filename,&$folder));
        
		$files=scandir(JPATH_ROOT.'/media/ksenmart/images/'.$folder);
		foreach($files as $file)
		{
			if ($file!='.' && $file!='..' && is_dir(JPATH_ROOT.'/media/ksenmart/images/'.$folder.'/'.$file))
				if (file_exists(JPATH_ROOT.'/media/ksenmart/images/'.$folder.'/'.$file.'/'.$filename))
					unlink(JPATH_ROOT.'/media/ksenmart/images/'.$folder.'/'.$file.'/'.$filename);
		}
		$query = $this->db->getQuery(true);
		$query->delete('#__ksenmart_files');
		$where = array("filename='$filename'");
		$query->where($where);
		$this->db->setQuery($query);
		$this->db->query();
        
        $this->onExecuteAfter('delPhoto', array(&$filename,&$folder));			
		return true;
	}

	function delProduct($product_id)
	{
	    $this->onExecuteBefore('delProduct', array(&$product_id));
        
		$query = $this->db->getQuery(true);
		$query->delete('#__ksenmart_product_properties_values');
		$query->where("product_id='$product_id'");
		$this->db->setQuery($query);
		$this->db->query();
		$query = $this->db->getQuery(true);
		$query->delete('#__ksenmart_products_categories');
		$query->where("product_id='$product_id'");
		$this->db->setQuery($query);
		$this->db->query();	
		$query = $this->db->getQuery(true);
		$query->delete('#__ksenmart_products_child_groups');
		$query->where("product_id='$product_id'");
		$this->db->setQuery($query);
		$this->db->query();
		$query = $this->db->getQuery(true);
		$query->delete('#__ksenmart_products_relations');
		$query->where("product_id='$product_id'");
		$this->db->setQuery($query);
		$this->db->query();	
		$query = $this->db->getQuery(true);
		$query->select('*')
		->from('#__ksenmart_files')
		->where(array("media_type='image'","owner_type='product'", "owner_id=".$product_id ))
		->order('ordering');
		$this->db->setQuery($query);
		$images = $this->db->loadObjectList('id');
		foreach($images as $image)
			$this->delPhoto($image->filename,$image->folder);
		$query = $this->db->getQuery(true);
		$query->delete('#__ksenmart_products');
		$query->where("id='$product_id'");
		$this->db->setQuery($query);
		$this->db->query();	
		$query = $this->db->getQuery(true);
		$query->select('id')->from('#__ksenmart_products')->where('parent_id='.$product_id);
		$this->db->setQuery($query);
		$childs=$this->db->loadObjectList();	
		foreach($childs as $child)
			$this->delProduct($child->id);
            
        $this->onExecuteAfter('delProduct', array(&$product_id));
	}
	
	function getYMFormData()
	{
	    $this->onExecuteBefore('getYMFormData');
        
		$query = $this->db->getQuery(true);
		$query->select('*')->from('#__ksenmart_yandeximport');
		$this->db->setQuery($query);
		$settings=$this->db->loadObjectList('setting');
		
		$data=new stdClass();
		$data->categories=json_decode($settings['categories']->value,true);
		$data->shopname=$settings['shopname']->value;
		$data->company=$settings['company']->value;
		
        $this->onExecuteBefore('getYMFormData', array(&$data));
		return $data;
	}
}
