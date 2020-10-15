<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

class com_ksenmartInstallerScript
{

	public function preflight($type, $parent)
	{
		if ($type == 'update')
		{
			$path_config = __DIR__ . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'install';

			require $path_config . DIRECTORY_SEPARATOR . 'update' . DIRECTORY_SEPARATOR . 'config.php';

			$KsenmartConfig = new KsenmartUpdateConfig();
			$db             = JFactory::getDbo();
			$query          = $db->getQuery(true);
			$query->select('*')->from('#__update_sites');
			$db->setQuery($query);
			$updates = $db->loadObjectList();

			foreach ($updates as $update)
			{
				if (!empty($KsenmartConfig->extensions[$update->location]))
				{
					$obj_update = (object) [
						'update_site_id' => $update->update_site_id,
						'location'       => $KsenmartConfig->extensions[$update->location],
					];

					$db->updateObject('#__update_sites', $obj_update, 'update_site_id');
				}
			}

			if ($parent->getElement() != 'com_ksenmart')
			{
				return;
			}

			$extension_id = $parent->get('currentExtensionId');

			if (empty($extension_id))
			{
				return;
			}

			$db = JFactory::getDbo();
			/*$query = $db->getQuery(true);
			$query->select('*')->from('#__schemas')->where('extension_id=' . (int) $extension_id);
			$db->setQuery($query);
			$schema = $db->loadObject();

			if (!empty($schema))
			{
				return;
			}*/

			$query = $db->getQuery(true);
			$query->select('manifest_cache')->from('#__extensions')->where('extension_id=' . (int) $extension_id);
			$db->setQuery($query);
			$manifest = $db->loadResult();

			if (empty($manifest))
			{
				return;
			}

			$manifest = new Registry($manifest);
			$version  = $manifest->get('version', '');

			$query = $db->getQuery(true);
			$query->update('#__schemas')
				->set($db->qn('version_id') . '=' . $db->q($version))
				->where($db->qn('extension_id') . '=' . (int) $extension_id);
			/*$query->insert('#__schemas')
				->columns([
					$db->qn('extension_id'),
					$db->qn('version_id'),
				])
				->values((int) $extension_id . ',' . $db->q($version));*/
			$db->setQuery($query);
			$db->execute();
		}
	}

	public function postflight($type, $parent)
	{
		jimport('joomla.installer.helper');

		if (!defined('DIRECTORY_SEPARATOR')) define('DIRECTORY_SEPARATOR', DIRECTORY_SEPARATOR);

		$path = JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_ksenmart' . DIRECTORY_SEPARATOR . 'install';

		if (!JFile::move($path . DIRECTORY_SEPARATOR . 'administrator-templates-system' . DIRECTORY_SEPARATOR . 'ksenmart.php', JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart.php'))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage('Couldnt move file');
		}
		if (!JFile::move($path . DIRECTORY_SEPARATOR . 'administrator-templates-system' . DIRECTORY_SEPARATOR . 'ksenmart-full.php', JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart-full.php'))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage('Couldnt move file');
		}
		if (!JFile::move($path . DIRECTORY_SEPARATOR . 'templates-system' . DIRECTORY_SEPARATOR . 'ksenmart.php', JPATH_ROOT . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart.php'))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage('Couldnt move file');
		}
		JFolder::create(JPATH_ROOT . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ksenmart');
		JFolder::copy($path . DIRECTORY_SEPARATOR . 'images-ksenmart', JPATH_ROOT . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ksenmart', null, 1);

		JFolder::delete($path);
		$jconfig  = new JConfig();
		$mailfrom = $jconfig->mailfrom;

		$fields = array(
			'1' => array(
				'required'  => '1',
				'position'  => 'customer',
				'type'      => 'text',
				'title'     => 'first_name',
				'ordering'  => '1',
				'system'    => '1',
				'published' => '1',
				'id'        => '1',
			),
			'2' => array(
				'required'  => '1',
				'position'  => 'customer',
				'type'      => 'text',
				'title'     => 'last_name',
				'ordering'  => '1',
				'system'    => '1',
				'published' => '0',
				'id'        => '1',
			),
			'3' => array(
				'required'  => '1',
				'position'  => 'customer',
				'type'      => 'text',
				'title'     => 'middle_name',
				'ordering'  => '3',
				'system'    => '1',
				'published' => '0',
				'id'        => '3',
			),
			'4' => array(
				'required'  => '1',
				'position'  => 'customer',
				'type'      => 'text',
				'title'     => 'phone',
				'ordering'  => '4',
				'system'    => '1',
				'published' => '1',
				'id'        => '4',
			),
			'5' => array(
				'required'  => '1',
				'position'  => 'customer',
				'type'      => 'text',
				'title'     => 'email',
				'ordering'  => '5',
				'system'    => '1',
				'published' => '1',
				'id'        => '5',
			)
		);

		$params = array(
			'catalog_default_view'                       => 'grid',
			'show_categories_from_catalog'               => '0',
			'show_minprice_categories'                   => '1',
			'show_products_from_subcategories'           => '1',
			'show_out_stock'                             => '1',
			'show_comment_form'                          => '1',
			'show_product_rate'                          => '0',
			'site_product_limit'                         => '15',
			'site_use_pagination'                        => '1',
			'parent_products_template'                   => 'list',
			'only_auth_buy'                              => '0',
			'use_stock'                                  => '0',
			'catalog_mode'                               => '0',
			'full_width'                                 => '900',
			'full_height'                                => '900',
			'thumb_width'                                => '170',
			'thumb_height'                               => '170',
			'middle_width'                               => '350',
			'middle_height'                              => '350',
			'mini_thumb_width'                           => '110',
			'mini_thumb_height'                          => '110',
			'manufacturer_width'                         => '240',
			'manufacturer_height'                        => '120',
			'category_width'                             => '192',
			'category_height'                            => '192',
			'shipping_width'                             => '30',
			'shipping_height'                            => '30',
			'count_symbol'                               => '400',
			'review_moderation'                          => '0',
			'review_notice'                              => '1',
			'discount_catalog'                           => '0',
			'mindiscount'                                => array(
				'mindifprice' => '0',
				'type'        => '1'
			),
			'printforms_companyname'                     => '',
			'printforms_companyaddress'                  => '',
			'printforms_companyphone'                    => '',
			'printforms_nds'                             => '',
			'printforms_ceo_name'                        => '',
			'printforms_buh_name'                        => '',
			'printforms_bank_account_number'             => '',
			'printforms_inn'                             => '',
			'printforms_kpp'                             => '',
			'printforms_bankname'                        => '',
			'printforms_bank_kor_number'                 => '',
			'printforms_bik'                             => '',
			'printforms_ip_name'                         => '',
			'printforms_ip_registration'                 => '',
			'printforms_company_logo'                    => '',
			'printforms_congritulation_message_template' => '<p>Спасибо за ваш заказ. В ближайшее время наши менеджеры с вами свяжутся.</p><p>Вы сможете связаться с нами по телефону: 8 800 2000 600</p>',
			'order_process'                              => '0',
			'order_process_message'                      => '',
			'order_process_fields'                       => $fields,
			'shop_name'                                  => '',
			'shop_email'                                 => $mailfrom,
			'shop_phone'                                 => '8-800-200-00-00',
			'include_css'                                => '1',
			'modules_styles'                             => '1',
			'admin_product_limit'                        => '30',
			'calculate_set_price'                        => '0',
			'watermark'                                  => '0',
			'watermark_image'                            => '',
			'watermark_type'                             => '0',
			'watermark_valign'                           => 'middle',
			'watermark_halign'                           => 'center',
			'displace'                                   => '0',
			'valign'                                     => 'middle',
			'halign'                                     => 'center',
			'background_type'                            => 'color',
			'background_file'                            => '',
			'background_color'                           => 'ffffff',
			'region_id'                                  => '1',
			'show_regions'                               => '0',
			'customer_fields'                            => $fields,
			'address_fields'                             => array(
				'13' => array(
					'required'  => '1',
					'position'  => 'address',
					'type'      => 'text',
					'title'     => 'address',
					'ordering'  => '0',
					'system'    => '1',
					'published' => '1',
					'id'        => '13',
				),
				'6'  => array(
					'required'  => '1',
					'position'  => 'address',
					'type'      => 'text',
					'title'     => 'city',
					'ordering'  => '1',
					'system'    => '1',
					'published' => '0',
					'id'        => '6',
				),
				'7'  => array(
					'required'  => '1',
					'position'  => 'address',
					'type'      => 'text',
					'title'     => 'zip',
					'ordering'  => '2',
					'system'    => '1',
					'published' => '0',
					'id'        => '7',
				),
				'8'  => array(
					'required'  => '1',
					'position'  => 'address',
					'type'      => 'text',
					'title'     => 'street',
					'ordering'  => '3',
					'system'    => '1',
					'published' => '0',
					'id'        => '8',
				),
				'9'  => array(
					'required'  => '1',
					'position'  => 'address',
					'type'      => 'text',
					'title'     => 'house',
					'ordering'  => '4',
					'system'    => '1',
					'published' => '0',
					'id'        => '9',
				),
				'10' => array(
					'required'  => '1',
					'position'  => 'address',
					'type'      => 'text',
					'title'     => 'entrance',
					'ordering'  => '5',
					'system'    => '1',
					'published' => '0',
					'id'        => '10',
				),
				'11' => array(
					'required'  => '1',
					'position'  => 'address',
					'type'      => 'text',
					'title'     => 'floor',
					'ordering'  => '6',
					'system'    => '1',
					'published' => '0',
					'id'        => '11',
				),
				'12' => array(
					'required'  => '1',
					'position'  => 'address',
					'type'      => 'text',
					'title'     => 'flat',
					'ordering'  => '7',
					'system'    => '1',
					'published' => '0',
					'id'        => '12',
				)
			),
		);

		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->update('#__extensions')->set('params=' . $db->quote(json_encode($params)))->where('name=' . $db->quote('ksenmart'));
		$db->setQuery($query);
		$db->execute();
	}

	public function update($parent)
	{
		//$version = $parent->getParent()->getManifest()->version;
	}

	public function uninstall($parent)
	{
		jimport('joomla.installer.helper');
		if (file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart.php') && !JFile::delete(JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart.php'))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage('Couldnt delete file');
		}
		if (file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart-full.php') && !JFile::delete(JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart-full.php'))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage('Couldnt delete file');
		}
		if (file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart.php') && !JFile::delete(JPATH_ROOT . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'ksenmart.php'))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage('Couldnt delete file');
		}
	}
}
