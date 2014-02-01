<?php 
defined( '_JEXEC' ) or die;
jimport('joomla.application.component.modelkmadmin');

class KsenMartModelUsers extends JModelKMAdmin {
	
	function __construct() {
		parent::__construct();
	}	
	
	function populateState()
	{
	    $this->onExecuteBefore('populateState');
        
		$app = JFactory::getApplication();
		if ($layout = JRequest::getVar('layout','default')) {
			$this->context .= '.'.$layout;
		}
		
		$value = $app->getUserStateFromRequest($this->context.'list.limit', 'limit', $this->params->get('admin_product_limit',30), 'uint');
		$limit = $value;
		$this->setState('list.limit', $limit);
		
		$value = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0);
		$limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
		$this->setState('list.start', $limitstart);	

		$order_dir=$app->getUserStateFromRequest($this->context . '.order_dir', 'order_dir', 'asc');
		$this->setState('order_dir',$order_dir);
		$order_type=$app->getUserStateFromRequest($this->context . '.order_type', 'order_type', 'id');
		$this->setState('order_type',$order_type);
		
		$searchword=$app->getUserStateFromRequest($this->context . '.searchword', 'searchword', null);
		$this->setState('searchword',$searchword);
		
		$usergroups=$app->getUserStateFromRequest($this->context . '.usergroups', 'usergroups', array());
		JArrayHelper::toInteger($usergroups);
		$usergroups=array_filter($usergroups,'KMFunctions::filterArray');
		$this->setState('usergroups',$usergroups);		
		
		$excluded=$app->getUserStateFromRequest($this->context . '.excluded', 'excluded', array());
		JArrayHelper::toInteger($excluded);
		$excluded=array_filter($excluded,'KMFunctions::filterArray');
		$this->setState('excluded',$excluded);				
		$items_tpl=$app->getUserStateFromRequest($this->context . '.items_tpl', 'items_tpl', null);
		$this->setState('items_tpl',$items_tpl);	
		$items_to=$app->getUserStateFromRequest($this->context . '.items_to', 'items_to', null);
		$this->setState('items_to',$items_to);
        
        $this->onExecuteAfter('populateState');		
	}	
	
	function getListItems()
	{
	    $this->onExecuteBefore('getListItems');
        
		$order_dir=$this->getState('order_dir');
		$order_type=$this->getState('order_type');
		$searchword=$this->getState('searchword');
		$usergroups=$this->getState('usergroups');
		$excluded=$this->getState('excluded');
		$query=$this->db->getQuery(true);		
		$query->select('SQL_CALC_FOUND_ROWS u.*')->from('#__users as u')
		->leftjoin('#__user_usergroup_map as uum on uum.user_id=u.id')
		->order('u.'.$order_type.' '.$order_dir)
		->group('u.id');
		if (!empty($searchword))
			$query->where('u.name like '.$this->db->quote('%'.$searchword.'%').' or u.username like '.$this->db->quote('%'.$searchword.'%').' or u.email like '.$this->db->quote('%'.$searchword.'%'));
		if (count($usergroups)>0)
			$query->where('uum.group_id in ('.implode(',', $usergroups).')');	
		if (count($excluded)>0)
			$query->where('u.id not in ('.implode(',', $excluded).')');	
		$query=KMMedia::setItemMainImageToQuery($query,'user','u.');
		$this->db->setQuery($query,$this->getState('list.start'),$this->getState('list.limit'));
		$items=$this->db->loadObjectList();
		$query=$this->db->getQuery(true);
		$query->select('FOUND_ROWS()');
		$this->db->setQuery($query);
		$this->total=$this->db->loadResult();		
		foreach($items as &$item)
		{
			$item->folder='users';
			$item->small_img = KMMedia::resizeImage($item->filename,$item->folder,$this->params->get('admin_product_thumb_image_width',36),$this->params->get('admin_product_thumb_image_heigth',36),json_decode($item->params,true));
			$item->medium_img = KMMedia::resizeImage($item->filename, $item->folder,$this->params->get('admin_product_medium_image_width',120),$this->params->get('admin_product_medium_image_heigth',120),json_decode($item->params,true));
			$query=$this->db->getQuery(true);
			$query->select('*')->from('#__ksenmart_users')->where('id='.$item->id);
			$this->db->setQuery($query);
			$km_user=$this->db->loadObject();	
			if (!$km_user || empty($km_user))
			{
				$user=array(
					'id'=>$item->id
				);
				$table = $this->getTable('users');
				if (!$table->bindCheckStore($user)) {
					$this->setError($table->getError());
					return false;
				}
			}
			$query=$this->db->getQuery(true);
			$query->select('group_id')->from('#__user_usergroup_map')->where('user_id='.$item->id);
			$this->db->setQuery($query);
			$item->groups=$this->db->loadColumn();			

			if (!empty($item->social))
			{
				if ($item->email==$item->username.'@email.ru')
					$item->email='';			
			}	
		}
        
        $this->onExecuteAfter('getListItems',array(&$items));
		return $items;
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
        
		foreach($ids as $id)
		{	
			$query = $this->db->getQuery(true);
			$query->delete('#__ksenmart_users')->where('id='.$id);
			$this->db->setQuery($query);
			$this->db->query();
			$query = $this->db->getQuery(true);
			$query->delete('#__ksenmart_user_fields_values')->where('user_id='.$id);
			$this->db->setQuery($query);
			$this->db->query();			
			$query = $this->db->getQuery(true);
			$query->delete('#__users')->where('id='.$id);
			$this->db->setQuery($query);
			$this->db->query();
			$query = $this->db->getQuery(true);
			$query->delete('#__user_usergroup_map')->where('user_id='.$id);
			$this->db->setQuery($query);
			$this->db->query();			
		}
        
        $this->onExecuteAfter('deleteListItems',array(&$ids));
		return true;
	}
	
	function getUsers($ids)
	{
	    $this->onExecuteBefore('getUsers', array(&$ids));
        
		$query=$this->db->getQuery(true);		
		$query->select('u.*')->from('#__users as u')->where('u.id in ('.implode(',',$ids).')');
		$query=KMMedia::setItemMainImageToQuery($query,'user','u.');
		$this->db->setQuery($query);
		$items=$this->db->loadObjectList();
		foreach($items as &$item)
			$item->small_img = KMMedia::resizeImage($item->filename,$item->folder,$this->params->get('admin_product_thumb_image_width'),$this->params->get('admin_product_thumb_image_heigth'),json_decode($item->params,true));
        
        $this->onExecuteAfter('getUsers', array(&$items));
		return $items;	
	}	
	
	function getUser()
	{
	    $this->onExecuteBefore('getUser');
        
		$id=JRequest::getInt('id');
		$user=KMSystem::loadDbItem($id,'users');
		$user=KMMedia::setItemMedia($user,'user');
		$user->password='';		
		
		if ($id>0)
		{
			$query=$this->db->getQuery(true);
			$query->select('group_id')->from('#__user_usergroup_map')->where('user_id='.$id);		
			$this->db->setQuery($query);
			$user->groups=$this->db->loadColumn();
		}
		else
			$user->groups=array(2);
		
        $query=$this->db->getQuery(true);
        $query->select('*')->from('#__ksenmart_users')->where('id='.$id);		
		$this->db->setQuery($query);
		$kmuser=$this->db->loadObject();
		if (!empty($kmuser))
		{
			$user->region_id=$kmuser->region_id;
			$user->phone=$kmuser->phone;
			$user->social=$kmuser->social;
		}
		
        $query=$this->db->getQuery(true);
        $query->select('*')->from('#__ksenmart_user_addresses')->where('user_id='.$id);		
		$this->db->setQuery($query);
		$user->addresses=$this->db->loadObjectList();	

        $query=$this->db->getQuery(true);
        $query->select('*')->from('#__ksenmart_user_fields_values')->where('user_id='.$id);		
		$this->db->setQuery($query);
		$user->fields=$this->db->loadObjectList('field_id');		
		
		if (!empty($user->social))
		{
			if ($user->email==$user->username.'@email.ru')
				$user->email='';			
		}		
		
        $this->onExecuteAfter('getUser', array(&$user));
		return $user;
	}	
	
	function saveUser($data)
	{
	    $this->onExecuteBefore('saveUser', array(&$data));
        
		if ($data['social']==1)
			$data['email']=$data['username'].'@email.ru';
		$user = JUser::getInstance($data['id']);
		if (!$user->bind($data))
		{
			$this->setError($user->getError());
			return false;
		}
		if (!$user->save())
		{
			$this->setError($user->getError());
			return false;
		}	
		$id=$user->id;
		
		if (empty($data['id']))
		{
			$query=$this->db->getQuery(true);
			$query->insert('#__ksenmart_users')->columns('id')->values(array($id));
			$this->db->setQuery($query);
			$this->db->query();
			$data['id']=$user->id;
		}
		
		$table = $this->getTable('kmusers');
		if (!$table->bindCheckStore($data)) {
			$this->setError($table->getError());
			return false;
		}
		
		KMMedia::saveItemMedia($id,$data,'user','users');
		
		$in = array();
		foreach ($data['addresses'] as $k=>$v) {
			$v['user_id'] = $id;
			$table = $this->getTable('useraddresses');
			if ($k>0)
				$v['id'] = $k;
			if (!$table->bindCheckStore($v)) {
				$this->setError($table->getError());
				return false;
			}
			$in[] = $table->id;
		}
		$query = $this->db->getQuery(true);
		$query->delete('#__ksenmart_user_addresses')->where('user_id='.$id);
		if (count($in))
			$query->where('id not in ('.implode(',', $in).')');
		$this->db->setQuery($query);
		$this->db->query();		
		
		$in = array();
		foreach ($data['fields'] as $k=>$v) {
			$value=array(
				'user_id'=>$id,
				'field_id'=>$k,
				'value'=>$v
			);
			$table = $this->getTable('userfieldsvalues');
			if (!$table->bindCheckStore($value)) {
				$this->setError($table->getError());
				return false;
			}
			$in[] = $table->id;
		}
		$query = $this->db->getQuery(true);
		$query->delete('#__ksenmart_user_fields_values')->where('user_id='.$id);
		if (count($in))
			$query->where('id not in ('.implode(',', $in).')');
		$this->db->setQuery($query);
		$this->db->query();			
		
		$on_close='window.parent.UsersList.refreshList();';
		$return=array('id'=>$id,'on_close'=>$on_close);
		
        $this->onExecuteAfter('saveUser', array(&$return));
		return $return;		
	}
	
	function getUserGroup()
	{
	    $this->onExecuteBefore('getUserGroup');
        
		$id=JRequest::getInt('id');
		$usergroup=KMSystem::loadDbItem($id,'usergroups');
        
        $this->onExecuteAfter('getUserGroup', array(&$usergroup));
		return $usergroup;
	}
	
	function saveUserGroup($data)
	{
	    $this->onExecuteBefore('saveUserGroup', array(&$data));
        
		$data['parent_id']=isset($data['parent_id'])?$data['parent_id']:0;
		$table = $this->getTable('usergroups');
		if (!$table->bindCheckStore($data)) {
			$this->setError($table->getError());
			return false;
		}
		$id = $table->id;	
		
		$this->rebuild_groups();
		
		$on_close='window.parent.UserGroupsModule.refresh();';
		$return=array('id'=>$id,'on_close'=>$on_close);
		
        $this->onExecuteAfter('saveUserGroup', array(&$return));
		return $return;
	}
	
	function deleteUserGroup($id)
	{
        $this->onExecuteBefore('deleteUserGroup', array(&$id));
		
        $groups=JRequest::getVar('groups',array());
		$query = $this->db->getQuery(true);
		$query->delete('#__usergroups')->where('id='.$id);
		$this->db->setQuery($query);
		$this->db->query();
		$query = $this->db->getQuery(true);
		$query->delete('#__user_usergroup_map')->where('group_id='.$id);
		$this->db->setQuery($query);
		$this->db->query();	
        
        $this->onExecuteAfter('deleteUserGroup', array(&$id));	
		return true;
	}	
	
	function getUserField()
	{
	    $this->onExecuteBefore('getUserField');
        
		$id=JRequest::getInt('id');
		$userfield=KMSystem::loadDbItem($id,'userfields');
		
        $this->onExecuteAfter('getUserField', array(&$userfield));
        return $userfield;
	}
	
	function saveUserField($data)
	{
	    $this->onExecuteBefore('saveUserField', array(&$data));
        
		$table = $this->getTable('userfields');
		if (!$table->bindCheckStore($data)) {
			$this->setError($table->getError());
			return false;
		}
		$id = $table->id;	
	
		$on_close='window.parent.UserFieldsModule.refresh();';
		$return=array('id'=>$id,'on_close'=>$on_close);
		
        $this->onExecuteAfter('saveUserField', array(&$return));
		return $return;
	}
	
	function deleteUserField($id)
	{
	    $this->onExecuteBefore('deleteUserField', array(&$id));
        
		$query = $this->db->getQuery(true);
		$query->delete('#__ksenmart_user_fields')->where('id='.$id);
		$this->db->setQuery($query);
		$this->db->query();
		$query = $this->db->getQuery(true);
		$query->delete('#__ksenmart_user_fields_values')->where('field_id='.$id);
		$this->db->setQuery($query);
		$this->db->query();
        
        $this->onExecuteAfter('deleteUserField', array(&$id));
		return true;
	}
	
	function rebuild_groups($parent_id = 0, $left = 0)
	{
	    $this->onExecuteBefore('rebuild_groups', array(&$parent_id, &$left));
        
		$query="SELECT id FROM #__usergroups WHERE parent_id=".(int)$parent_id." ORDER BY parent_id, title";
		$this->_db->setQuery($query);
		$ids=$this->_db->loadColumn();

		$right = $left + 1;

		for ($i=0, $n=count($ids); $i < $n; $i++)
		{
			$child =$ids[$i];
			$right = $this->rebuild_groups($child, $right);

			if ($right === false) {
				return false;
			}
		}

		$query="UPDATE #__usergroups SET lft=".(int)$left.", rgt=".(int)$right." WHERE id=".(int)$parent_id;
		$this->_db->setQuery($query);
		if (!$this->_db->Query()) {
			return false;
		}
        
        $return = $right + 1;
        
        $this->onExecuteAfter('rebuild_groups', array(&$return));
		return $return;
	}	
}
