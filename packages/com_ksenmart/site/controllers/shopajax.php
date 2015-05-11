<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
class KsenMartControllerShopAjax extends JControllerLegacy {
	
	function get_md5($array, $secret_key) {
		ksort($array);
		$params = '';
		
		foreach ($array as $key => $value) if ($key != 'sig') $params.= "$key=$value";
		
		return md5($params . $secret_key);
	}
    
    function get_review_form(){
      $product_id = JRequest::getVar('id',0);
            JRequest::setVar('id', $product_id);
            JRequest::setVar('layout', 'product_comment_form');
      $view = $this->getView('product', 'html');
            $view->setLayout('product_comment_form');
            $view->display();
    }
     
	function fb_auth() {
		include (JPATH_ROOT . '/components/com_ksenmart/social/social.php');
		if (isset($_REQUEST['state']) and isset($_SESSION['state'])) {
			
			if ($_REQUEST['state'] == $_SESSION['state']) {
				
				$tokenUrl = "https://graph.facebook.com/oauth/access_token?client_id=" . FB_APP_KEY . "&redirect_uri=" . urlencode(JURI::base() . 'index.php?option=com_ksenmart&task=shopajax.fb_auth') . "&client_secret=" . FB_SECRET_KEY . "&code=" . $_REQUEST["code"];
				
				$oResponse = file_get_contents($tokenUrl);
				
				$params = null;
				
				parse_str($oResponse, $params);
				
				$graphUrl = "https://graph.facebook.com/me?access_token=" . $params['access_token'];
				
				$response = json_decode(file_get_contents($graphUrl));
				$db = JFactory::getDBO();
				$query = "select * from #__users where username='fb-{$response->id}'";
				$db->setQuery($query);
				$db_user = $db->loadObject();
				if (count($db_user) == 0) {
					$user = new JUser;
					$data = array();
					$data['name'] = $response->name;
					$data['username'] = 'fb-' . $response->id;
					$data['email'] = 'fb-' . $response->id . '@email.ru';
					$data['email1'] = 'fb-' . $response->id . '@email.ru';
					$data['email2'] = 'fb-' . $response->id . '@email.ru';
					$data['password'] = 'fb-' . $response->id;
					$data['password1'] = 'fb-' . $response->id;
					$data['password2'] = 'fb-' . $response->id;
					$data['groups'] = array(
						2
					);
					$data['activation'] = '';
					$data['block'] = 0;
					if (!$user->bind($data)) exit();
					
					JPluginHelper::importPlugin('user');
					if (!$user->save()) exit();
					$app = JFactory::getApplication();
					$options = array();
					$options['remember'] = true;
					$options['return'] = '';
					$credentials = array();
					$credentials['username'] = 'fb-' . $response->id;
					$credentials['password'] = 'fb-' . $response->id;
					$app->login($credentials, $options);
					$query = "insert into #__ksen_users (`id`) values ('$user->id')";
					$db->setQuery($query);
					$db->Query();
				} else {
					$app = JFactory::getApplication();
					$options = array();
					$options['remember'] = true;
					$options['return'] = '';
					$credentials = array();
					$credentials['username'] = 'fb-' . $response->id;
					$credentials['password'] = 'fb-' . $response->id;
					$app->login($credentials, $options);
					$user = JFactory::getUser();
				}
				$session = & JFactory::getSession();
				$order_id = $session->get('shop_order_id', 0);
				if ($order_id != 0) {
					$query = "update #__ksenmart_orders set user_id='$user->id' where id='$order_id'";
					$db->setQuery($query);
					$db->Query();
				}
?>
				<script>
				if(window.opener != null && !window.opener.closed) {
					if (window.opener.getElementById('subcribe').checked==true)
						window.opener.location='<?php echo JRoute::_('index.php?option=com_ksenmart&view=profile&Itemid=' . KSSystem::getShopItemid()) ?>';
					else
						window.opener.location.reload();
					window.close();
				}	
				</script>	
				<?
			}
		}
		exit();
	}
	
	function vk_auth() {
		include (JPATH_ROOT . '/components/com_ksenmart/social/social.php');
		parse_str(urldecode($_COOKIE['vk_app_' . VK_APP_ID]) , $array);
		ksort($array);
		if ($this->get_md5($array, VK_SECRET_KEY) == $array['sig'] && $array['expire'] > time()) {
			$params = array(
				"method" => "secure.getProfiles",
				"api_id" => VK_APP_ID,
				"format" => "json",
				"timestamp" => time() ,
				"rnd" => rand(100000, 999999) ,
				"uids" => $array['mid']
			);
			$url = "http://api.vk.com/api.php?" . http_build_query($params) . "&sig=" . $this->get_md5($params, VK_SECRET_KEY);
			$response = json_decode(file_get_contents($url));
			$db = JFactory::getDBO();
			$query = "select * from #__users where username='vk-{$response->response[0]->user->uid}'";
			$db->setQuery($query);
			$db_user = $db->loadObject();
			if (count($db_user) == 0) {
				$user = new JUser;
				$data = array();
				$data['name'] = $response->response[0]->user->last_name . ' ' . $response->response[0]->user->first_name;
				$data['username'] = 'vk-' . $response->response[0]->user->uid;
				$data['email'] = 'vk-' . $response->response[0]->user->uid . '@email.ru';
				$data['email1'] = 'vk-' . $response->response[0]->user->uid . '@email.ru';
				$data['email2'] = 'vk-' . $response->response[0]->user->uid . '@email.ru';
				$data['password'] = 'vk-' . $response->response[0]->user->uid;
				$data['password1'] = 'vk-' . $response->response[0]->user->uid;
				$data['password2'] = 'vk-' . $response->response[0]->user->uid;
				$data['groups'] = array(
					2
				);
				$data['activation'] = '';
				$data['block'] = 0;
				if (!$user->bind($data)) exit();
				
				JPluginHelper::importPlugin('user');
				if (!$user->save()) exit();
				$app = JFactory::getApplication();
				$options = array();
				$options['remember'] = true;
				$options['return'] = '';
				$credentials = array();
				$credentials['username'] = 'vk-' . $response->response[0]->user->uid;
				$credentials['password'] = 'vk-' . $response->response[0]->user->uid;
				$query = "insert into #__ksen_users (`id`) values ('$user->id')";
				$db->setQuery($query);
				$db->Query();
				if (true === $app->login($credentials, $options)) echo 'register';
			} else {
				$app = JFactory::getApplication();
				$options = array();
				$options['remember'] = true;
				$options['return'] = '';
				$credentials = array();
				$credentials['username'] = 'vk-' . $response->response[0]->user->uid;
				$credentials['password'] = 'vk-' . $response->response[0]->user->uid;
				if (true === $app->login($credentials, $options)) echo 'login';
				$user = JFactory::getUser();
			}
			$session = & JFactory::getSession();
			$order_id = $session->get('shop_order_id', 0);
			if ($order_id != 0) {
				$query = "update #__ksenmart_orders set user_id='$user->id' where id='$order_id'";
				$db->setQuery($query);
				$db->Query();
			}
		}
		exit();
	}
	
	function tw_auth() {
		include (JPATH_ROOT . '/components/com_ksenmart/social/social.php');
		require_once (JPATH_ROOT . '/components/com_ksenmart/social/twitter/twitteroauth.php');
		$session = JFactory::getSession();
		$oauth_verifier = JRequest::getVar('oauth_verifier', '');
		if ($oauth_verifier == '') {
			$connection = new TwitterOAuth(TW_APP_KEY, TW_SECRET_KEY);
			$request_token = $connection->getRequestToken(JURI::base() . 'index.php?option=com_ksenmart&task=shopajax.tw_auth');
			
			switch ($connection->http_code) {
				case 200:
					$url = $connection->getAuthorizeURL($request_token);
					$session->set('oauth_token', $request_token['oauth_token']);
					$session->set('oauth_token_secret', $request_token['oauth_token_secret']);
					header('Location: ' . $url);
				break;
				default:
					exit();
			}
		} else {
			$connection = new TwitterOAuth(TW_APP_KEY, TW_SECRET_KEY, $session->get('oauth_token', '') , $session->get('oauth_token_secret', ''));
			
			$access_token = $connection->getAccessToken($oauth_verifier);
			
			switch ($connection->http_code) {
				case 200:
					$session->set('oauth_token', null);
					$session->set('oauth_token_secret', null);
					$response = $connection->get('account/verify_credentials');
					$db = JFactory::getDBO();
					$query = "select * from #__users where username='tw-{$response->id}'";
					$db->setQuery($query);
					$db_user = $db->loadObject();
					if (count($db_user) == 0) {
						$user = new JUser;
						$data = array();
						$data['name'] = $response->name;
						$data['username'] = 'tw-' . $response->id;
						$data['email'] = 'tw-' . $response->id . '@email.ru';
						$data['email1'] = 'tw-' . $response->id . '@email.ru';
						$data['email2'] = 'tw-' . $response->id . '@email.ru';
						$data['password'] = 'tw-' . $response->id;
						$data['password1'] = 'tw-' . $response->id;
						$data['password2'] = 'tw-' . $response->id;
						$data['groups'] = array(
							2
						);
						$data['activation'] = '';
						$data['block'] = 0;
						if (!$user->bind($data)) exit();
						
						JPluginHelper::importPlugin('user');
						if (!$user->save()) exit();
						$app = JFactory::getApplication();
						$options = array();
						$options['remember'] = true;
						$options['return'] = '';
						$credentials = array();
						$credentials['username'] = 'tw-' . $response->id;
						$credentials['password'] = 'tw-' . $response->id;
						$app->login($credentials, $options);
						$query = "insert into #__ksen_users (`id`) values ('$user->id')";
						$db->setQuery($query);
						$db->Query();
					} else {
						$app = JFactory::getApplication();
						$options = array();
						$options['remember'] = true;
						$options['return'] = '';
						$credentials = array();
						$credentials['username'] = 'tw-' . $response->id;
						$credentials['password'] = 'tw-' . $response->id;
						$app->login($credentials, $options);
						$user = JFactory::getUser();
					}
					$session = & JFactory::getSession();
					$order_id = $session->get('shop_order_id', 0);
					if ($order_id != 0) {
						$query = "update #__ksenmart_orders set user_id='$user->id' where id='$order_id'";
						$db->setQuery($query);
						$db->Query();
					}
?>
					<script>
					if(window.opener != null && !window.opener.closed) {
						if (window.opener.getElementById('subcribe').checked==true)
							window.opener.location='<?php echo JRoute::_('index.php?option=com_ksenmart&view=profile&Itemid=' . KSSystem::getShopItemid()) ?>';
						else
							window.opener.location.reload();
						window.close();
					}	
					</script>	
					<?
					
					break;
				default:
					exit();
				}
			}
			exit();
	}
	
	function ok_auth() {
		include (JPATH_ROOT . '/components/com_ksenmart/social/social.php');
		if (isset($_GET['code'])) {
			$curl = curl_init('http://api.odnoklassniki.ru/oauth/token.do');
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, 'code=' . $_GET['code'] . '&redirect_uri=' . urlencode(JURI::base() . 'index.php?option=com_ksenmart&task=shopajax.ok_auth') . '&grant_type=authorization_code&client_id=' . OK_APP_ID . '&client_secret=' . OK_SECRET_KEY);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$s = curl_exec($curl);
			curl_close($curl);
			$auth = json_decode($s, true);
			$curl = curl_init('http://api.odnoklassniki.ru/fb.do?access_token=' . $auth['access_token'] . '&application_key=' . OK_APP_KEY . '&method=users.getCurrentUser&sig=' . md5('application_key=' . OK_APP_KEY . 'method=users.getCurrentUser' . md5($auth['access_token'] . OK_SECRET_KEY)));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$s = curl_exec($curl);
			curl_close($curl);
			$response = json_decode($s, true);
			$db = JFactory::getDBO();
			$query = "select * from #__users where username='ok-{$response['uid']}'";
			$db->setQuery($query);
			$db_user = $db->loadObject();
			if (count($db_user) == 0) {
				$user = new JUser;
				$data = array();
				$data['name'] = $response['last_name'] . ' ' . $response['first_name'];
				$data['username'] = 'ok-' . $response['uid'];
				$data['email'] = 'ok-' . $response['uid'] . '@email.ru';
				$data['email1'] = 'ok-' . $response['uid'] . '@email.ru';
				$data['email2'] = 'ok-' . $response['uid'] . '@email.ru';
				$data['password'] = 'ok-' . $response['uid'];
				$data['password1'] = 'ok-' . $response['uid'];
				$data['password2'] = 'ok-' . $response['uid'];
				$data['groups'] = array(
					2
				);
				$data['activation'] = '';
				$data['block'] = 0;
				if (!$user->bind($data)) exit();
				
				JPluginHelper::importPlugin('user');
				if (!$user->save()) exit();
				$app = JFactory::getApplication();
				$options = array();
				$options['remember'] = true;
				$options['return'] = '';
				$credentials = array();
				$credentials['username'] = 'ok-' . $response['uid'];
				$credentials['password'] = 'ok-' . $response['uid'];
				$app->login($credentials, $options);
				$query = "insert into #__ksen_users (`id`) values ('$user->id')";
				$db->setQuery($query);
				$db->Query();
			} else {
				$app = JFactory::getApplication();
				$options = array();
				$options['remember'] = true;
				$options['return'] = '';
				$credentials = array();
				$credentials['username'] = 'ok-' . $response['uid'];
				$credentials['password'] = 'ok-' . $response['uid'];
				$app->login($credentials, $options);
				$user = JFactory::getUser();
			}
			$session = & JFactory::getSession();
			$order_id = $session->get('shop_order_id', 0);
			if ($order_id != 0) {
				$query = "update #__ksenmart_orders set user_id='$user->id' where id='$order_id'";
				$db->setQuery($query);
				$db->Query();
			}
?>
			<script>
			if(window.opener != null && !window.opener.closed) {
				if (window.opener.getElementById('subcribe').checked==true)
					window.opener.location='<?php echo JRoute::_('index.php?option=com_ksenmart&view=profile&Itemid=' . KSSystem::getShopItemid()) ?>';
				else
					window.opener.location.reload();
				window.close();
			}	
			</script>	
			<?
		}
		exit();
	}
	
	public function site_auth() {

		$app = JFactory::getApplication();

		// Populate the data array:
		$data = array();
		$data['username'] = JRequest::getVar('login', '', 'GET', 'username');
		$data['password'] = JRequest::getString('password', '', 'GET', JREQUEST_ALLOWRAW);

		// Get the log in options.
		$options = array();
		$options['remember'] = $this->input->getBool('remember', false);

		// Get the log in credentials.
		$credentials = array();
		$credentials['username']  = $data['username'];
		$credentials['password']  = $data['password'];

		// Perform the log in.
		if (true === $app->login($credentials, $options)){
			// Success
			if ($options['remember'] = true){
				$app->setUserState('rememberLogin', true);
			}

			$app->setUserState('users.login.form.data', array());

			$user = JFactory::getUser();
			$session = JFactory::getSession();
			$order_id = $session->get('shop_order_id', 0);
			if ($order_id != 0) {
				$db = JFactory::getDBO();
				$query = "update #__ksenmart_orders set user_id='$user->id' where id='$order_id'";
				$db->setQuery($query);
				$db->Query();
			}
			$app->close('login');
		}else{
			// Login failed !
			$data['remember'] = (int) $options['remember'];
			$app->setUserState('users.login.form.data', $data);
			$app->close('error');
		}
	}
	
	public function site_reg() {
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$user = new JUser;
		$data = array();
		$login = JRequest::getVar('login', '');
		$first_name = JRequest::getVar('first_name', '');
		$last_name = JRequest::getVar('last_name', '');
		$middle_name = JRequest::getVar('middle_name', '');
		$password = JRequest::getVar('password', '');
		$subscribe = JRequest::getVar('subscribe', 0);
		$fields = JRequest::getVar('fields', array());
		$ajax = JRequest::getVar('ajax', false);
		$name = '';
		if (!empty($last_name)) $name .= $last_name.' ';
		if (!empty($first_name)) $name .= $first_name.' ';
		if (!empty($middle_name)) $name .= $middle_name;		
		
		$data['name'] = $name;
		$data['username'] = $login;
		$data['email'] = $login;
		$data['email1'] = $login;
		$data['email2'] = $login;
		$data['password'] = $password;
		$data['password1'] = $password;
		$data['password2'] = $password;
		$data['groups'] = array(
			2
		);
		$data['activation'] = '';
		$data['block'] = 0;
		if (!$user->bind($data)) {
			if ($ajax){
				print_r($user->getError());
				exit();
			} else {
				$app->enqueueMessage($user->getError(), 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_ksenmart&view=profile&layout=registration&Itemid='.KSSystem::getShopItemid()));
				return false;
			}
		}
		
		JPluginHelper::importPlugin('user');
		if (!$user->save()) {
			if ($ajax){
				print_r($user->getError());
				exit();
			} else {
				$app->enqueueMessage($user->getError(), 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_ksenmart&view=profile&layout=registration&Itemid='.KSSystem::getShopItemid()));
				return false;
			}
		}
		
		$options = array();
		$options['remember'] = true;
		$options['return'] = '';
		$credentials = array();
		$credentials['username'] = $login;
		$credentials['password'] = $password;
		
		$app->login($credentials, $options);
		
		$params = JComponentHelper::getParams('com_ksenmart');
		$config = JFactory::getConfig();
		$data = $user->getProperties();
		
		$data['fromname'] = $params->get('shop_name', '');
		$data['mailfrom'] = $params->get('shop_email', '');
		$data['sitename'] = $config->get('sitename');
		$data['siteurl'] = JURI::root();
		
		$emailSubject = JText::sprintf('COM_USERS_EMAIL_ACCOUNT_DETAILS', $login, $data['sitename']);
		
		$emailBody = JText::sprintf('COM_USERS_EMAIL_REGISTERED_BODY', $login, $data['sitename'], $data['siteurl'], $login, $password);
		$return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $login, $emailSubject, $emailBody);
		
		$query = "insert into #__ksen_users (`id`,`first_name`,`last_name`,`middle_name`) values ('$user->id','$first_name','$last_name','$middle_name')";
		$db->setQuery($query);
		$db->Query();
		foreach($fields as $key=>$value){
			$values = array(
				'user_id' => $user->id,
				'field_id' => $key,
				'value' => $db->quote($value)
			);
			$query = $db->getQuery(true);
			$query->insert('#__ksen_user_fields_values')->columns(implode(',', array_keys($values)))->values(implode(',', $values));
			$db->setQuery($query);
			$db->query();			
		}		
		
		$session = JFactory::getSession();
		$order_id = $session->get('shop_order_id', 0);
		if ($order_id != 0) {
			$query = "update #__ksenmart_orders set user_id='$user->id' where id='$order_id'";
			$db->setQuery($query);
			$db->Query();
		}
		if ($ajax)
			$app->close('login');
		else
			$this->setRedirect('/');
	}
	
	public function test() {
		$this->setRedirect('/');
	}
	
	function get_order_shippings() {
		$db = JFactory::getDBO();
		$region_id = JRequest::getVar('region_id', 0);
		$selected = JRequest::getVar('selected', 0);
		$query = "select s.*,(select name from #__ksenmart_shipping_types where id=s.type) as type_name from #__ksenmart_shippings as s where s.regions like '%|$region_id|%'";
		$db->setQuery($query);
		$shippings = $db->loadObjectList();
		if (count($shippings) > 0) {
			
			foreach ($shippings as $ship) {
				$cost = 0;
				$shipping_id = $ship->id;
				$distance = 0;
				include (JPATH_ROOT . '/administrator/components/com_ksenmart/helpers/shipping/' . $ship->type_name . '.php');
?>
			<div class="shipping">
				<input type="radio" name="shipping_type" regions="<?php echo $ship->regions ?>" value="<?php echo $ship->id ?>" <?php echo ($ship->id == $selected ? 'checked' : '') ?>/>
				<span class="grey-span"><?php echo JText::_($ship->title) ?><?php echo ($cost != 0 ? ' — ' . KSMPrice::showPriceWithoutTransform($cost) : '') ?></span>
				<span class="shipping-descr">(доставка не позднее <?php echo KSMShipping::getShippingDate($ship->id) ?>)</span>
			</div>			
			<?
			}
		} else {
?>
		<div class="no_shippings">
			<span class="grey-span">Нет способов доставки для выбранного региона</span>
		</div>
		<?
		}
		exit();
	}
	
	function callback() {
		$params = JComponentHelper::getParams('com_ksenmart');
		$phone = JRequest::getVar('phone', '');
		$data['fromname'] = $params->get('shop_name', '');
		$data['mailfrom'] = $params->get('shop_email', '');
		$body = '
		<b>Телефон : </b>' . $phone . '<br>
		';
		$mailer = JFactory::getMailer();
		$mailer->isHTML(1);
		$return = $mailer->sendMail($data['mailfrom'], $data['fromname'], $data['mailfrom'], 'Новый запрос на обратный звонок', $body);
		if ($return) echo 'В ближайшее время мы с вами свяжемся .';
		else echo 'Ошибка отправки запроса . Попробуйте позже .';
		exit();
	}
	
	function question() {
		$params = JComponentHelper::getParams('com_ksenmart');
		$email = JRequest::getVar('email', '');
		$question = JRequest::getVar('question', '');
		$data['fromname'] = $params->get('shop_name', '');
		$data['mailfrom'] = $params->get('shop_email', '');
		$body = '
		<b>E-mail : </b>' . $email . '<br>
		<b>Вопрос : </b>' . $question . '<br>
		';
		$mailer = JFactory::getMailer();
		$mailer->isHTML(1);
		$return = $mailer->sendMail($data['mailfrom'], $data['fromname'], $data['mailfrom'], 'Новый вопрос', $body);
		if ($return) echo 'В ближайшее время мы с вами свяжемся .';
		else echo 'Ошибка отправки вопроса . Попробуйте позже .';
		exit();
	}
	
	function add_favorites() {
		$id = JRequest::getVar('id', 0);
		$user = KSUsers::getUser();
		if ($user->id != 0 && !in_array($id, $user->favorites)) {
			$user->favorites[] = $id;
			$db = JFactory::getDBO();
			echo $query = "update #__ksen_users set favorites='" . json_encode($user->favorites) . "' where id='$user->id'";
			$db->setQuery($query);
			$db->query();
		}
		exit();
	}
	
	function add_watched() {
		$id = JRequest::getVar('id', 0);
		$user = KSUsers::getUser();
		if ($user->id != 0 && !in_array($id, $user->watched)) {
			$user->watched[] = $id;
			$db = JFactory::getDBO();
			$query = "update #__ksen_users set watched='" . json_encode($user->watched) . "' where id='$user->id'";
			$db->setQuery($query);
			$db->query();
		}
		exit();
	}
	
	function subscribe() {
		$user = KSUsers::getUser();
		if ($user->id != 0 && !in_array(2, $user->groups)) {
			if ($user->email == '') {
				echo 'email';
				exit();
			}
			$user->groups[] = 2;
			$db = JFactory::getDBO();
			$query = "update #__ksen_users set groups='|" . implode('||', $user->groups) . "|' where id='$user->id'";
			$db->setQuery($query);
			$db->query();
		}
		exit();
	}
	
	function get_shipping_cost() {
		$cost = 0;
		$region_id = JRequest::getVar('region_id', 0);
		$shipping_id = JRequest::getVar('shipping_id', 0);
		$distance = JRequest::getVar('distance', 0);
		$db = JFactory::getDBO();
		$query = "select st.name from #__ksenmart_shippings as s,#__ksenmart_shipping_types as st where s.id='$shipping_id' and st.id=s.type";
		$db->setQuery($query);
		$shipping_type = $db->loadResult();
		include (JPATH_ROOT . '/administrator/components/com_ksenmart/helpers/shipping/' . $shipping_type . '.php');
		echo $cost;
		exit(0);
	}
	
	public function get_product_price_with_properties() {
		
		$pid                = $this->input->get('id', 0, 'int');
		$val_prop_id        = $this->input->get('val_prop_id', 0, 'int');
		$prop_id            = $this->input->get('prop_id', 0, 'int');
		$selectedProperties = $this->input->get('properties', array(), 'array');
		
		$db                = JFactory::getDBO();
		$app               = JFactory::getApplication();
		$properties        = KSMProducts::getProperties($pid, $prop_id, $val_prop_id);
		$productProperties = KSMProducts::getProperties($pid);
		$prices            = KSMProducts::getProductPrices($pid);
		
		$price              = $prices->price;
		$price_type         = $prices->price_type;
		$checked            = array();

		foreach ($productProperties as $property) {
			foreach ($selectedProperties as $selectedPropId => $selectedProperty) {
				foreach ($selectedProperty as $selectedValueId => $selectedValue) {
					if(isset($selectedValue['checked'])){
						$checked[$selectedValue['valueId']] = $selectedValue['checked'];
					}
					if($property->property_id == $selectedValue['propId'] && ($val_prop_id != $property->values[$selectedValueId]->id)){
						$edit_priceC = $property->values[$selectedValueId]->price;
						$edit_price_symC = substr($edit_priceC, 0, 1);
						$this->getCalcPriceAsProperties($edit_price_symC, $edit_priceC, $price);
						$property->values[$selectedValueId]->id . '-' .$price . "\n\t";
					}
				}
			}
		}

		foreach ($properties as $property) {
			$edit_price = null;
			if ($property->edit_price) {
				if ($property->view == 'checkbox') {
					$value = array_pop($property->values);
					if ($checked[$value->id]) {
						$edit_price = $value->price;
					}
				} elseif ($property->view == 'select' || $property->view == 'radio') {
					if ($val_prop_id != 0) {
						$edit_price = $property->values[$val_prop_id]->price;
					}
				}
			}
			
			if($edit_price){
				$edit_price_sym = substr($edit_price, 0, 1);
				$this->getCalcPriceAsProperties($edit_price_sym, $edit_price, $price);
			}
		}
		$price = KSMPrice::getPriceInCurrentCurrency($price, $price_type);
		$app->close($price . '^^^' . $price);
	}

	private function getCalcPriceAsProperties($edit_price_sym, $edit_price, &$price) {
		switch ($edit_price_sym) {
			case '+':
				$price += substr($edit_price, 1, strlen($edit_price) - 1);
			break;
			case '-':
				$price -= substr($edit_price, 1, strlen($edit_price) - 1);
			break;
			case '/':
				$price = $price / substr($edit_price, 1, strlen($edit_price) - 1);
			break;
			case '*':
				$price = $price * substr($edit_price, 1, strlen($edit_price) - 1);
			break;
			default:
				$price += $edit_price;
		}
		return $price;
	}
	
	function validate_in_stock() {
		$params = JComponentHelper::getParams('com_ksenmart');
		$db = JFactory::getDBO();
		$id = JRequest::getVar('id', 0);
		$count = JRequest::getVar('count', 0);
		$product = KSMProducts::getProduct($id);
		if ($count > $product->in_stock && $params->get('use_stock', 1) == 1) echo 'Недостаточно количества на складе';
		exit();
	}
	
	function save_variable() {
		$app = JFactory::getApplication();
		$name = JRequest::getVar('name', '');
		$value = JRequest::getVar('value', '');
		if ($name != '') $app->setUserState('com_ksenmart.' . $name, $value);
		$app->close();
	}
	
	function get_transform_price() {
		$price = JRequest::getVar('price', 0);
		echo KSMPrice::showPriceWithTransform($price);
	}
	
	function get_route_link() {
		$url = JRequest::getVar('url', '');
		$url = JRoute::_($url);
		$url = str_replace('&amp;', '&', $url);
		echo $url;
		JFactory::getApplication()->close();
	}
	
	function set_session_variable() {
		$session = JFactory::getSession();
		$name = JRequest::getVar('name', null);
		$value = JRequest::getVar('value', null);
		if (!empty($name)) {
			$name = 'com_ksenmart.' . $name;
			$session->set($name, $value);
		}
		JFactory::getApplication()->close();
	}
	
	function get_session_variable() {
		$session = JFactory::getSession();
		$name = JRequest::getVar('name', null);
		$value = '';
		if (!empty($name)) {
			$name = 'com_ksenmart.' . $name;
			$value = $session->get($name, null);
		}
		echo $value;
		JFactory::getApplication()->close();
	}
	
	function set_session_data() {
		$session_data = JRequest::getVar('session_data', '{}');
		$session_data = json_decode($session_data, true);
		if (!count($session_data)) $_SESSION = $session_data;
		JFactory::getApplication()->close();
	}
	
	function get_session_data() {
		$session_data = $_SESSION;
		$session_data = json_encode($session_data);
		echo $session_data;
		JFactory::getApplication()->close();
	}
	
	function set_user_activity() {
		$session = JFactory::getSession();
		$time = JRequest::getVar('time', time());
		$session->set('com_ksenmart.user_last_activity', $time);
		JFactory::getApplication()->close();
	}
}