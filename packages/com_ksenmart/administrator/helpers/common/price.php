<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class KSMPrice
{

	private static $_currencies = array();
	private static $_discounts = null;
	private static $_currency = 0;

	public static function getCurrencies()
	{
		if (empty(self::$_currencies))
		{
			$db    = JFactory::getDBO();
			$query = "select * from #__ksenmart_currencies";
			$db->setQuery($query);
			$rows = $db->loadObjectList('id');

			if ($rows)
			{
				self::$_currencies = $rows;
			}
		}

		return self::$_currencies;
	}

	public static function _getDefaultCurrency()
	{
		$currencies = self::getCurrencies();
		foreach ($currencies as $currency)
		{
			if ($currency->default)
			{
				return $currency->id;
			}
		}
	}

	public static function getDiscount($key = '')
	{
		$discounts = null;
		if (is_null(self::$_discounts))
		{
			$db    = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('*')->from('#__ksenmart_discounts')->where('enabled=1');
			$db->setQuery($query);
			$cache            = JFactory::getCache('com_ksenmart.discounts', 'callback');
			self::$_discounts = $cache->get(array($db, 'loadObjectList'), 'id');
			foreach (self::$_discounts as &$discount)
				$discount->params = json_decode($discount->params, true);
			unset($discount);
		}

		if (empty(self::$_discounts)) return is_numeric($key) ? null : array();
		if (empty($key)) return self::$_discounts;
		if (is_numeric($key))
		{
			if (isset(self::$_discounts[$key]))
			{
				$discounts = self::$_discounts[$key];
			}
		}
		else
		{
			$discounts = array();
			foreach (self::$_discounts as $discount)
			{
				if ($discount->type == $key)
				{
					$discounts[] = $discount;
				}
			}
		}

		return $discounts;
	}

	public static function showPriceWithoutTransform($price, $currency = 0)
	{
		if (empty($currency))
		{
			$currency = self::getDefaultUserCurrency();
		}

		self::getCurrencies();
		$currency = self::$_currencies[$currency];

		$price = str_replace('{price}', $price, $currency->template);

		return $price;
	}

	public static function showPriceWithTransform($price, $currency = 0)
	{
		if (empty($currency))
		{
			$currency = self::getDefaultUserCurrency();
		}

		self::getCurrencies();
		$currency = self::$_currencies[$currency];

		if (is_numeric($price))
		{
			$price = number_format($price, $currency->fractional, '.', $currency->separator);
		}
		$price = str_replace('{price}', '<span class="price_num">' . $price . '</span>', $currency->template); //Ужас!!!

		return $price;
	}

	public static function getPriceInDefaultCurrency($price, $currency = 0)
	{
		self::getCurrencies();
		if ($currency == 0)
		{
			$currency = self::_getDefaultCurrency();
		}

		$currency = self::$_currencies[$currency];
		$price    = $price / $currency->rate;

		return $price;
	}

	public static function getPriceInCurrentCurrency($price, $currency = 0)
	{
		$session       = JFactory::getSession();
		$curr_currency = $session->get('com_ksenmart.catalog.currency', self::_getDefaultCurrency());
		self::getCurrencies();
		if ($currency == 0)
		{
			$currency = self::_getDefaultCurrency();
		}
		$currency      = self::$_currencies[$currency];
		$curr_currency = self::$_currencies[$curr_currency];
		$price         = $price / $currency->rate * $curr_currency->rate;

		return $price;
	}

	public static function getPriceInDefaultCurrencyWithTransform($price, $currency)
	{
		self::getCurrencies();
		$currency = self::$_currencies[$currency];
		$price    = number_format($price, $currency->fractional, '.', $currency->separator);
		$price    = str_replace('{price}', $price, $currency->template);

		return $price;
	}

	public static function getPriceInCurrency($price, $currency = 0)
	{
		self::getCurrencies();
		if ($currency <= 0)
		{
			$currency = self::getDefaultUserCurrency();
		}
		$currency = self::$_currencies[$currency];
		$price    = $price * $currency->rate;
		$price    = number_format($price, $currency->fractional, '.', $currency->separator);

		return $price;
	}

	public static function getPriceInCurrencyInt($price, $currency = 0)
	{
		self::getCurrencies();
		if ($currency <= 0)
		{
			$currency = self::getDefaultUserCurrency();
		}
		$currency = self::$_currencies[$currency];
		$price    = $price * $currency->rate;
		$price    = number_format($price, $currency->fractional, '.', '');

		return $price;
	}

	public static function getCurrencyCode($currency = 0)
	{
		self::getCurrencies();

		if (!$currency)
		{
			$currency = self::_getDefaultCurrency();
		}

		$currency = self::$_currencies[$currency];

		return $currency->code;
	}

	public static function getCurrencyName($currency = 0)
	{
		self::getCurrencies();
		$currency = self::$_currencies[$currency];

		return $currency->title;
	}

	public static function getPriceWithDiscount($price, $subscribe = 0)
	{
		$user    = KSUsers::getUser();
		$db      = JFactory::getDBO();
		$percent = 0;
		$rur     = 0;
		/*$where = " and (0";
			  foreach ($user->groups as $group)
				  if ($group != 2 || ($group == 2 && $subscribe == 2)) $where .=
						  " or user_group like '%|$group|%'";
			  if ($subscribe == 1) $where .= " or user_group like '%|2|%'";
			  $where .= ")";
			  if (array_key_exists($where, self::$_discounts)) {
				  $discounts = self::$_discounts[$where];
			  } else {
				  $query = "select * from #__ksenmart_discounts where enabled='1' $where";
				  $db->setQuery($query);
				  $discounts = $db->loadObjectList();
				  self::$_discounts[$where] = $discounts;
			  }
		*/

		$price = ($price - $rur) * (100 - $percent) / 100;


		return number_format($price, 2, '.', '');
	}

	public static function setDefaultUserCurrency($id)
	{
		if ($id > 0)
		{
			$session = JFactory::getSession();
			$session->set('com_ksenmart.catalog.currency', $id);

			return true;
		}
	}

	public static function getDefaultUserCurrency()
	{
		$session  = JFactory::getSession();
		$currency = $session->get('com_ksenmart.catalog.currency', self::_getDefaultCurrency());

		return $currency;
	}
}
