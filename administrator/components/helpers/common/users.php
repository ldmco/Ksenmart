<?php

defined('_JEXEC') or die;

class KMUsers {
    
    private static $_user = array();

    public static function getSubscribersGroupID() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id')->from('#__usergroups')->where('title like ' . $db->quote('Подписчики'));
        $db->setQuery($query);
        $group_id = $db->loadResult();
        return $group_id;
    }	

    public static function getUser($id = '') {
        if(array_key_exists($id, self::$_user)) {
            return self::$_user[$id];
        }

        $db = JFactory::getDBO();
        $original_id = $id;
        if($id == '') {
            $id = JFactory::getUser()->id;
        }
        if($id != 0) {
            $query = $db->getQuery(true);

            $query->select('
                km_u.id,  
                km_u.region_id, 
                km_u.phone, 
                km_u.watched, 
                km_u.favorites,
                km_u.settings,  
                u.id, 
                u.name, 
                u.username,
                u.email,  
                u.usertype, 
                u.block, 
                u.sendEmail, 
                u.registerDate, 
                u.lastvisitDate, 
                u.activation, 
                u.params, 
                u.lastResetTime, 
                u.resetCount,
                ua.city,
                ua.zip,
                ua.street,
                ua.house,
                ua.floor,
                ua.flat,
                ua.coords,
                uf.filename AS logo
            ');
            $query->from('#__ksenmart_users AS km_u, #__users AS u');
            $query->leftjoin('#__ksenmart_files AS uf ON uf.owner_id=u.id');
            $query->leftjoin('#__ksenmart_user_addresses AS ua ON ua.user_id=u.id AND ua.default=1');
            $query->where('u.id=km_u.id');
            $query->where('km_u.id=' . $id);
			$query=KMMedia::setItemMainImageToQuery($query,'user','km_u.');

            $db->setQuery($query);
            $user = $db->loadObject();
			$user->folder='users';
			$params = JComponentHelper::getParams('com_ksenmart');
			$user->small_img = KMMedia::resizeImage($user->filename,$user->folder,$params->get('admin_product_thumb_image_width',36),$params->get('admin_product_thumb_image_heigth',36),json_decode($user->params,true));
			$user->medium_img = KMMedia::resizeImage($user->filename, $user->folder,$params->get('admin_product_medium_image_width',120),$params->get('admin_product_medium_image_heigth',120),json_decode($user->params,true));

            if (!empty($item->social) && $user->email == $user->username . '@email.ru') $user->email = '';

            if(empty($user->groups)) {
                $user->groups = KMUsers::getUserGroups($id);
            } else {
                $user->groups = array();
            }
            
            if(!empty($user->watched)) {
                $user->watched = json_decode($user->watched);
            } else {
                $user->watched = array();
            }
            if(!empty($user->favorites)) {
                $user->favorites = json_decode($user->favorites);
            } else {
                $user->favorites = array();
            }
            if($user->region_id == 0) {
                $session = JFactory::getSession();
                $user_region = $session->get('user_region', 0);
                $user->region_id = $user_region;
            }

            $user->address  = KMUsers::getDefaultAddress($id);
			if (empty($user->settings))
				$user->settings='{"catalog_layout":"list"}';
            $user->settings = json_decode($user->settings);
            
            KMUsers::setAvatarLogoInObject($user);
            KMUsers::setUserFields($user);

        } else {
            $session = JFactory::getSession();
            $user    = new stdClass();
            
            $user->id           = 0;
            $user->region_id    = $session->get('user_region', 0);
            $user->phone        = null;
            $user->watched      = array();
            $user->favorites    = array();
            $user->name         = JText::_('ksm_users_anonym');
            $user->username     = null;
            $user->email        = null;
            $user->groups       = array();
            $user->usertype     = null;
            $user->block        = null;
            $user->city         = null;
            $user->zip          = null;
            $user->street       = null;
            $user->house        = null;
            $user->floor        = null;
            $user->flat         = null;
            $user->coords       = null;
            $user->address      = null;
            $user->sendmails    = null;
            $user->settings->catalog_layout = 'grid';
			
			$user->folder='users';
			$user->filename='no.jpg';
			$params = JComponentHelper::getParams('com_ksenmart');
			$user->small_img = KMMedia::resizeImage($user->filename,$user->folder,$params->get('admin_product_thumb_image_width',36),$params->get('admin_product_thumb_image_heigth',36));
			$user->medium_img = KMMedia::resizeImage($user->filename, $user->folder,$params->get('admin_product_medium_image_width',120),$params->get('admin_product_medium_image_heigth',120));
			

            KMUsers::setAvatarLogoInObject($user);
            KMUsers::setUserFields($user);
        }
        self::$_user[$original_id] = $user;
        return $user;
    }
    
    /**
     * KMSystem::is_admin()
     * 
     * @return
     */
    public static function is_admin() {
        $user = KMUsers::getUser();
        return in_array(7, $user->groups);
    }

    /**
     * KMSystem::updateUser()
     * 
     * @param mixed $uid
     * @param mixed $column
     * @param mixed $data
     * @return
     */
    public static function updateUser($uid, $column, $data) {
        if(!empty($column) && !empty($uid) && $uid > 0) {
            $db = JFactory::getDbo();
            $user = new stdClass();

            $user->id = $uid;
            $user->{$column} = $data;

            try {
                $db->updateObject('#__ksenmart_users', $user, 'id');
                return true;
            }
            catch (exception $e) {

            }
        }
        return false;
    }

    /**
     * KMSystem::setAvatarLogoInObject()
     * 
     * @param mixed $object
     * @return
     */
    public static function setAvatarLogoInObject(&$object) {
        if(!empty($object)) {
            if(is_array($object)) {
                foreach($object as $item) {
                    if(!empty($item->logo)) {
                        $item->logo_original = 'media/ksenmart/images/users/original/' . $item->logo;
                        $item->logo_thumb = 'media/ksenmart/images/users/thumb/' . $item->logo;
                    } else {
                        $item->logo_thumb = 'media/ksenmart/images/users/default.png';
                        $item->logo_original = 'media/ksenmart/images/users/default.png';
                    }
                    unset($item->logo);
                }
            } elseif(is_object($object)) {
                if(!empty($object->logo)) {
                    $object->logo_original = 'media/ksenmart/images/users/original/' . $object->logo;
                    $object->logo_thumb = 'media/ksenmart/images/users/thumb/' . $object->logo;
                } else {
                    $object->logo_thumb = 'media/ksenmart/images/users/default.png';
                    $object->logo_original = 'media/ksenmart/images/users/default.png';
                }
                unset($object->logo);
            }
            return $object;
        }
        return false;
    }
    
    /**
     * KMUsers::getAddresses()
     * 
     * @return
     */
    public static function getAddresses() {
        $user = KMUsers::getUser();
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->select($db->quoteName(array(
            'id',
            'city',
            'zip',
            'street',
            'house',
            'floor',
            'flat',
            'coords',
            'default')));
        $query->from($db->quoteName('#__ksenmart_user_addresses'));
        $query->where($db->quoteName('user_id') . '=' . $db->getEscaped($user->id));

        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }

    /**
     * KMUsers::getUserGroups()
     * 
     * @param mixed $uid
     * @return
     */
    public static function getUserGroups($uid) {
        $db     = JFactory::getDbo();
        $query  = $db->getQuery(true);
        $groups = array();

        $query
            ->select($db->quoteName(array('group_id')))
            ->from($db->quoteName('#__user_usergroup_map'))
            ->where($db->quoteName('user_id') . '=' . $db->getEscaped($uid))
        ;

        $db->setQuery($query);
        $groups = $db->loadResultArray();

        return $groups;
    }
    
    /**
     * KMSystem::getDefaultAddress()
     * 
     * @param mixed $uid
     * @return
     */
    public static function getDefaultAddress($uid) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select($db->quoteName(array(
            'city',
            'zip',
            'street',
            'house',
            'floor',
            'flat',
            'coords')));
        $query->from($db->quoteName('#__ksenmart_user_addresses'));
        $query->where($db->quoteName('user_id') . '=' . $db->getEscaped($uid));
        $query->where($db->quoteName('default') . '=1');

        $db->setQuery($query);

        $address_struct = $db->loadObject();
        return KMSystem::formatAddress($address_struct);
    }
    
    /**
     * KMUsers::getFields()
     * 
     * @return
     */
    public static function getFields() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query
            ->select('
                uf.id,
                uf.title
            ')
            ->from('#__ksenmart_user_fields AS uf')
            ->order('uf.ordering')
        ;
        
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    public static function setUserFields(&$user){
        if(!empty($user) && is_object($user)){
            $db         = JFactory::getDBO();
            $fields     = KMUsers::getFields();
            $field_ids  = array();
            
            foreach($fields as $field){
                $user->{'field_'.$field->id}->value = null;
                $field_ids[]                        = $field->id;
            }
        
            $query = $db->getQuery(true);
            $query
                ->select('
                    ufv.id,
                    ufv.value,
                    ufv.field_id
                ')
                ->from('#__ksenmart_user_fields_values AS ufv')
                ->where('ufv.user_id='.$db->getEscaped($user->id))
                ->where('(ufv.field_id IN('.implode(', ', $field_ids).'))')
            ;
            
            $db->setQuery($query);
            $fields_values = $db->loadObjectList();
            
            if(!empty($fields_values)){
                foreach($fields_values as $field_value){
                    $user->{'field_'.$field_value->field_id}->id        = $field_value->id;
                    $user->{'field_'.$field_value->field_id}->value     = $field_value->value;
                    $user->{'field_'.$field_value->field_id}->field_id  = $field_value->field_id;
                }
            }
        }
        return $user;
    }
    
    public function removeUserSubscribeGroup($uid) {
        if(!empty($uid) && $uid > 0){
            $db    = JFactory::getDBO();
            $query = $db->getQuery(true);
    
            $conditions = array('user_id=' . $db->getEscaped($uid), 'group_id=' . $db->getEscaped(KMUsers::getSubscribersGroupID()));
    
            $query->delete($db->quoteName('#__user_usergroup_map'));
            $query->where($conditions);
    
            $db->setQuery($query);
    
            try {
                $result = $db->query();
                return true;
            }catch (exception $e) {}
        }
        return false;
    }
    
    public function setUserSubscribeGroup($uid) {
        if(!empty($uid) && $uid > 0){
            $db     = JFactory::getDBO();
            $groups = KMUsers::getUserGroups($uid);
            if (!in_array(KMUsers::getSubscribersGroupID(), $groups)) {
                
                $user_map           = new stdClass;
                $user_map->group_id = KMUsers::getSubscribersGroupID();
                $user_map->user_id  = $uid;
                
                try {
                    $db->insertObject('#__user_usergroup_map', $user_map);
                }catch(Exception $e) {}
            }
        }
        return false;
    }
}