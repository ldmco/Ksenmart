<?php
defined('_JEXEC') or die;

use Joomla\Registry\Registry;

if (!class_exists('KMPlugin')) 
{
	require (JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_ksenmart' . DS . 'classes' . DS . 'kmplugin.php');
}

class PlgUserKsenmart extends KMPlugin
{

	protected $app;

	protected $db;
	
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		JFormHelper::addFieldPath(__DIR__ . '/assets/fields/');
	}	

	public function onUserAfterSave($user, $isnew, $success, $msg)
	{
		if ($isnew)
		{
			$query = $this->db->getQuery(true);
			$query
				->insert('#__ksen_users')
				->set('id = '.$user['id'])
				->set('first_name = '.$this->db->q($user['name']))
				->set('favorites = '.$this->db->q('[]'))
				->set('watched = '.$this->db->q('[]'))
			;
			$this->db->setQuery($query);
			$this->db->query();

			if ($this->app->isSite())
			{
				$session = JFactory::getSession();
				$order_id = $session->get('shop_order_id', 0);
				if (!empty($order_id))
				{
					$query = $this->db->getQuery(true);
					$query
						->update('#__ksenmart_orders')
						->set('user_id = '.$user['id'])
						->where('id = '.$order_id)
					;
					$this->db->setQuery($query);
					$this->db->query();
				}
			}
		}

		if ($user['id'] && $success && isset($user['ksenprofile']) && (count($user['ksenprofile'])))
		{
			$favorites = isset($user['ksenprofile']['favorites']) ? $user['ksenprofile']['favorites'] : array();
			$favorites = json_encode($favorites);
			$watched = isset($user['ksenprofile']['watched']) ? $user['ksenprofile']['watched'] : array();
			$watched = json_encode($watched);			
			
			$query = $this->db->getQuery(true);
			$query
				->update('#__ksen_users')
				->set('last_name = '.$this->db->q($user['ksenprofile']['last_name']))
				->set('middle_name = '.$this->db->q($user['ksenprofile']['middle_name']))
				->set('phone = '.$this->db->q($user['ksenprofile']['phone']))
				->set('region_id = '.$this->db->q($user['ksenprofile']['region_id']))
				->set('favorites = '.$this->db->q($favorites))
				->set('watched = '.$this->db->q($watched))
				->where('id = '.$user['id'])
			;
			$this->db->setQuery($query);
			$this->db->query();
			
			$addr_ids = array(0);
			if (isset($user['ksenprofile']['addresses']))
			{
				foreach($user['ksenprofile']['addresses'] as $addr_id => $address)
				{
					$address['default'] = isset($address['default']) ? $address['default'] : 0;
					
					if (!empty($address['default']))
					{
						$query = $this->db->getQuery(true);
						$query
							->update('#__ksen_user_addresses')
							->set('`default` = 0')	
							->where('user_id = '.$user['id'])
						;
						$this->db->setQuery($query);
						$this->db->query();								
					}
						
					if ($addr_id > 0)
					{
						$query = $this->db->getQuery(true);
						$query
							->update('#__ksen_user_addresses')
							->set('city = '.$this->db->q($address['city']))
							->set('zip = '.$this->db->q($address['zip']))
							->set('street = '.$this->db->q($address['street']))
							->set('house = '.$this->db->q($address['house']))
							->set('entrance = '.$this->db->q($address['entrance']))
							->set('floor = '.$this->db->q($address['floor']))
							->set('flat = '.$this->db->q($address['flat']))
							->set('`default` = '.$this->db->q($address['default']))
							->where('id = '.$addr_id)
						;
						$this->db->setQuery($query);
						$this->db->query();						
					}
					else
					{
						$query = $this->db->getQuery(true);
						$query
							->insert('#__ksen_user_addresses')
							->set('user_id = '.$user['id'])
							->set('city = '.$this->db->q($address['city']))
							->set('zip = '.$this->db->q($address['zip']))
							->set('street = '.$this->db->q($address['street']))
							->set('house = '.$this->db->q($address['house']))
							->set('entrance = '.$this->db->q($address['entrance']))
							->set('floor = '.$this->db->q($address['floor']))
							->set('flat = '.$this->db->q($address['flat']))
							->set('`default` = '.$this->db->q($address['default']))
						;
						$this->db->setQuery($query);
						$this->db->query();	
						$addr_id = $this->db->insertid();	
					}
					$addr_ids[] = $addr_id;
				}
			}
			$query = $this->db->getQuery(true);
			$query
				->delete('#__ksen_user_addresses')
				->where('id not in ('.implode(',', $addr_ids).')')	
				->where('user_id = '.$user['id'])
			;
			$this->db->setQuery($query);
			$this->db->query();		

			$review_ids = isset($user['ksenprofile']['reviews']) ? $user['ksenprofile']['reviews'] : array(0);
			$query = $this->db->getQuery(true);
			$query
				->delete('#__ksenmart_comments')
				->where('id not in ('.implode(',', $review_ids).')')	
				->where('user_id = '.$user['id'])
			;
			$this->db->setQuery($query);
			$this->db->query();			
		}		
	}
	
	public function onUserLogin($user, $options = array())
	{
		$user_id = (int)JUserHelper::getUserId($user['username']);
		
		if (!$user_id)
		{
			return false;
		}
		
		$query = $this->db->getQuery(true);
		$query
			->select('count(id)')
			->from('#__ksen_users')
			->where('id = '.$user_id)
		;
		$this->db->setQuery($query);
		$res = $this->db->loadResult();		
		
		if (!$res)
		{
			$instance = JUser::getInstance();
			$instance->load($user_id);
			
			$query = $this->db->getQuery(true);
			$query
				->insert('#__ksen_users')
				->set('id = '.$user_id)
				->set('first_name = '.$this->db->q($instance->name))
				->set('favorites = '.$this->db->q('[]'))
				->set('watched = '.$this->db->q('[]'))				
			;
			$this->db->setQuery($query);
			$this->db->query();			
		}
		
		$session = JFactory::getSession();
		$order_id = $session->get('shop_order_id', 0);
		if (!empty($order_id))
		{
			$query = $this->db->getQuery(true);
			$query
				->update('#__ksenmart_orders')
				->set('user_id = '.$user_id)
				->where('id = '.$order_id)
			;
			$this->db->setQuery($query);
			$this->db->query();
		}

		return true;
	}	

	public function onUserAfterDelete($user, $success, $msg)
	{
		if (!$success)
		{
			return false;
		}
		
		$query = $this->db->getQuery(true);
		$query
			->delete('#__ksenmart_comments')
			->where('user_id = '.$user['id'])
		;
		$this->db->setQuery($query);
		$this->db->query();
		
		$query = $this->db->getQuery(true);
		$query
			->delete('#__ksen_user_addresses')
			->where('user_id = '.$user['id'])
		;
		$this->db->setQuery($query);
		$this->db->query();
		
		$query = $this->db->getQuery(true);
		$query
			->delete('#__ksen_users')
			->where('id = '.$user['id'])
		;
		$this->db->setQuery($query);
		$this->db->query();
		
		return true;
	}	
	
	public function onContentPrepareData($context, $data)
	{
		if (!in_array($context, array('com_users.profile', 'com_users.user', 'com_users.registration', 'com_admin.profile')))
		{
			return true;
		}	
		
		if ($this->app->isSite())
		{
			if (!class_exists('KsenmartHtmlHelper')) 
			{
				require JPATH_ROOT . DS . 'components' . DS . 'com_ksenmart' . DS . 'helpers' . DS . 'head.php';
			}
			KsenmartHtmlHelper::AddHeadTags();
			KSSystem::loadJSLanguage(false);
			JHtml::_('behavior.tooltip');			
		}

		if (is_object($data))
		{
			$user_id = isset($data->id) ? $data->id : 0;

			if (!isset($data->ksenprofile) and $user_id > 0)
			{
				$query = $this->db->getQuery(true);
				$query
					->select('*')
					->from('#__ksen_users')
					->where('id = '.$user_id)
				;
				$this->db->setQuery($query);
				$user = $this->db->loadObject();

				$data->ksenprofile['last_name'] = $user->last_name;
				$data->ksenprofile['middle_name'] = $user->middle_name;
				$data->ksenprofile['phone'] = $user->phone;
				$data->ksenprofile['region_id'] = $user->region_id;
				$data->ksenprofile['favorites'] = json_decode($user->favorites, true);
				$data->ksenprofile['watched'] = json_decode($user->watched, true);
				
				$query = $this->db->getQuery(true);
				$query
					->select('id')
					->from('#__ksenmart_orders')
					->where('user_id = '.$user_id)
					->order('date_add desc')
				;
				$this->db->setQuery($query);
				$data->ksenprofile['orders'] = $this->db->loadColumn();	
				
				$query = $this->db->getQuery(true);
				$query
					->select('*')
					->from('#__ksen_user_addresses')
					->where('user_id = '.$user_id)
				;
				$this->db->setQuery($query);
				$data->ksenprofile['addresses'] = $this->db->loadObjectList();	
				
				$query = $this->db->getQuery(true);
				$query
					->select('*')
					->from('#__ksenmart_comments')
					->where('published = 1')
					->where('user_id = '.$user_id)
				;
				$this->db->setQuery($query);
				$data->ksenprofile['reviews'] = $this->db->loadObjectList();			
			}
			elseif ($user_id <= 0)
			{
				$data->ksenprofile['last_name'] = '';
				$data->ksenprofile['middle_name'] = '';
				$data->ksenprofile['phone'] = '';
				$data->ksenprofile['region_id'] = 0;
				$data->ksenprofile['orders'] = array();
				$data->ksenprofile['favorites'] = array();
				$data->ksenprofile['watched'] = array();	
			}
			
			if (!JHtml::isRegistered('users.region_id'))
			{
				JHtml::register('users.region_id', array(__CLASS__, 'region_id'));
			}	
			
			if (!JHtml::isRegistered('users.orders'))
			{
				JHtml::register('users.orders', array(__CLASS__, 'orders'));
			}
			
			if (!JHtml::isRegistered('users.addresses'))
			{
				JHtml::register('users.addresses', array(__CLASS__, 'addresses'));
			}	

			if (!JHtml::isRegistered('users.favorites'))
			{
				JHtml::register('users.favorites', array(__CLASS__, 'favorites'));
			}	

			if (!JHtml::isRegistered('users.watched'))
			{
				JHtml::register('users.watched', array(__CLASS__, 'watched'));
			}

			if (!JHtml::isRegistered('users.reviews'))
			{
				JHtml::register('users.reviews', array(__CLASS__, 'reviews'));
			}
			
			$doc = JFactory::getDocument();
			$doc->addScript(JURI::root().'plugins/user/ksenmart/assets/js/profile.js');	
			if ($this->ksm_params->get('modules_styles', true) && $this->app->isSite()) 
			{
				$doc->addStyleSheet(JURI::root().'plugins/user/ksenmart/assets/css/profile_site.css');
			}
			elseif ($this->app->isAdmin()) 
			{
				$doc->addStyleSheet(JURI::root().'plugins/user/ksenmart/assets/css/profile_admin.css');
			}			
		}

		return true;
	}	

	public static function region_id($value)
	{
		if (empty($value))
		{
			return JHtml::_('users.value', $value);
		}
		else
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query
				->select('title')
				->from('#__ksenmart_regions')
				->where('id = '.$value)
			;
			$db->setQuery($query);
			$region = $db->loadResult();
			
			return $region;
		}
	}	

	public static function orders($value)
	{
		if (empty($value))
		{
			return JHtml::_('users.value', $value);
		}
		else
		{
			$db = JFactory::getDbo();
			foreach($value as &$order)
			{
				$order = KSMOrders::getOrder($order);
				$order->items = KSMOrders::getOrderItems($order->id);
				
				$query = $db->getQuery(true);
				$query
					->select('title')
					->from('#__ksenmart_shippings')
					->where('id = '.$order->shipping_id)
				;
				$db->setQuery($query);
				$order->shipping_title = $db->loadResult();				
			}
			
			$view = new stdClass();
			$view->orders = $value;
			$html = KSSystem::loadPluginTemplate('ksenmart', 'user', $view, 'orders_view');
			
			return $html;
		}
	}
	
	public static function addresses($value)
	{
		if (empty($value))
		{
			return JHtml::_('users.value', $value);
		}
		else
		{
			foreach($value as &$address)
			{
				$address = self::getAddressString($address);
			}
			
			$view = new stdClass();
			$view->addresses = $value;
			$html = KSSystem::loadPluginTemplate('ksenmart', 'user', $view, 'addresses_view');
			
			return $html;
		}
	}

	public static function favorites($value)
	{
		if (empty($value))
		{
			return JHtml::_('users.value', $value);
		}
		else
		{
			foreach($value as &$product)
			{
				$product = KSMProducts::getProduct($product);
			}
			
			$view = new stdClass();
			$view->products = $value;
			$html = KSSystem::loadPluginTemplate('ksenmart', 'user', $view, 'products_view');
			
			return $html;
		}
	}

	public static function watched($value)
	{
		if (empty($value))
		{
			return JHtml::_('users.value', $value);
		}
		else
		{
			foreach($value as &$product)
			{
				$product = KSMProducts::getProduct($product);
			}
			
			$view = new stdClass();
			$view->products = $value;
			$html = KSSystem::loadPluginTemplate('ksenmart', 'user', $view, 'products_view');
			
			return $html;
		}
	}

	public static function reviews($value)
	{
		if (empty($value))
		{
			return JHtml::_('users.value', $value);
		}
		else
		{
			$view = new stdClass();
			$view->shop_review = null;
			$view->comments = array();
			
			foreach($value as $comment)
			{
				if ($comment->type == 'shop_review')
				{
					$ksm_params = JComponentHelper::getParams('com_ksenmart');
					$comment->img = $ksm_params->get('printforms_company_logos', 'plugins/user/ksenmart/assets/images/logo.png');
					$view->shop_review = $comment;
				}
				else
				{
					$product = KSMProducts::getProduct($comment->product_id);
					$comment->img = $product->mini_small_img;
					$comment->link = $product->link;
					$view->comments[] = $comment;
				}
			}
			
			$html = KSSystem::loadPluginTemplate('ksenmart', 'user', $view, 'reviews_view');
			
			return $html;
		}
	}
	
	public function getAddressString($address)
	{
		$addr_parts = array();
		$string = '';
		
		if (!empty($address->zip))
		{
			$addr_parts[] = $address->zip;
		}				
		if (!empty($address->city))
		{
			$addr_parts[] = JText::sprintf('PLG_USER_KSENMART_ADDRESSES_CITY_TXT', $address->city);
		}
		if (!empty($address->street))
		{
			$addr_parts[] = JText::sprintf('PLG_USER_KSENMART_ADDRESSES_STREET_TXT', $address->street);
		}
		if (!empty($address->house))
		{
			$addr_parts[] = JText::sprintf('PLG_USER_KSENMART_ADDRESSES_HOUSE_TXT', $address->house);
		}
		if (!empty($address->entrance))
		{
			$addr_parts[] = JText::sprintf('PLG_USER_KSENMART_ADDRESSES_ENTRANCE_TXT', $address->entrance);
		}
		if (!empty($address->floor))
		{
			$addr_parts[] = JText::sprintf('PLG_USER_KSENMART_ADDRESSES_FLOOR_TXT', $address->floor);
		}
		if (!empty($address->flat))
		{
			$addr_parts[] = JText::sprintf('PLG_USER_KSENMART_ADDRESSES_FLAT_TXT', $address->flat);
		}
		$string = implode(', ', $addr_parts);
		
		if (!empty($address->default))
		{
			$string .= JText::_('PLG_USER_KSENMART_ADDRESSES_DEFAULT_TXT');
		}		

		return $string;
	}
	
	public function onContentPrepareForm($form, $data)
	{
		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');

			return false;
		}

		$name = $form->getName();

		if (!in_array($name, array('com_admin.profile', 'com_users.user', 'com_users.profile')))
		{
			return true;
		}

		JForm::addFormPath(__DIR__ . '/assets/forms/');
		$form->loadFile('ksenprofile', false);
		
		return true;
	}

}
