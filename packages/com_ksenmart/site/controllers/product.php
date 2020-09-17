<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class KsenMartControllerProduct extends JControllerLegacy
{

	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('add_comment', 'add_comment');
	}

	public function add_comment()
	{
		$app            = JFactory::getApplication();
		$model          = $this->getModel('Product', 'KsenmartModel');
		$comments_model = $this->getModel('Comments', 'KsenmartModel');

		$return_url  = JRoute::_('index.php?option=com_ksenmart&view=product&id=' . $model->_id . '&Itemid=' . KSSystem::getShopItemid());
		$requestData = $this->input->post->get('jform', array(), 'array');
		$data        = array();

		if (count($requestData))
		{
			$model->form = 'review';
			$form        = $model->getForm();

			if (!$form)
			{
				JError::raiseError(500, $model->getError());

				return false;
			}

			$data = $model->validate($form, $requestData);

			if ($data === false)
			{
				$errors = $model->getErrors();

				for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
				{
					if ($errors[$i] instanceof Exception)
					{
						$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
					}
					else
					{
						$app->enqueueMessage($errors[$i], 'warning');
					}
				}

				$this->setRedirect($return_url);

				return false;
			}
		}
		else
		{
			$data                    = array();
			$data['comment_name']    = $this->input->post->get('comment_name', $user->name, 'string');
			$data['comment_rate']    = $this->input->post->get('comment_rate', 0, 'int');
			$data['comment_comment'] = $this->input->post->get('comment_comment', null, 'string');
			$data['comment_good']    = $this->input->post->get('comment_good', null, 'string');
			$data['comment_name']    = $this->input->post->get('comment_bad', null, 'string');
		}
		$data['product_id'] = $model->_id;

		$comments_model->addComment($data);
		if (!isset($_SESSION['rated']) || !is_array($_SESSION['rated']))
		{
			$_SESSION['rated'] = array();
		}
		$_SESSION['rated'][$model->_id] = 1;

		$this->setMessage('Ваш отзыв принят');
		$this->setRedirect($return_url);

		return true;
	}

	public function get_product_price_with_properties()
	{
		$pid                = $this->input->get('id', 0, 'int');
		$val_prop_id        = $this->input->get('val_prop_id', 0, 'int');
		$prop_id            = $this->input->get('prop_id', 0, 'int');
		$selectedProperties = $this->input->get('properties', array(), 'array');

		$app               = JFactory::getApplication();
		$properties        = KSMProducts::getProperties($pid, $prop_id, $val_prop_id);
		$productProperties = KSMProducts::getProperties($pid);
		$prices            = KSMProducts::getProductPrices($pid);

		$price      = $prices->price;
		$price_type = $prices->price_type;
		$checked    = array();

		foreach ($productProperties as $property)
		{
			foreach ($selectedProperties as $selectedPropId => $selectedProperty)
			{
				foreach ($selectedProperty as $selectedValueId => $selectedValue)
				{
					if (isset($selectedValue['checked']))
					{
						$checked[$selectedValue['valueId']] = $selectedValue['checked'];
					}
					if ($property->property_id == $selectedValue['propId'] && ($val_prop_id != $property->values[$selectedValueId]->id))
					{
						$edit_priceC     = $property->values[$selectedValueId]->price;
						$edit_price_symC = substr($edit_priceC, 0, 1);
						KSMProducts::getCalcPriceAsProperties($edit_price_symC, $edit_priceC, $price);
						$property->values[$selectedValueId]->id . '-' . $price . "\n\t";
					}
				}
			}
		}

		foreach ($properties as $property)
		{
			$edit_price = null;
			if ($property->edit_price)
			{
				if ($property->view == 'checkbox')
				{
					$value = array_pop($property->values);
					if ($checked[$value->id])
					{
						$edit_price = $value->price;
					}
				}
				elseif ($property->view == 'select' || $property->view == 'radio')
				{
					if ($val_prop_id != 0)
					{
						$edit_price = $property->values[$val_prop_id]->price;
					}
				}
			}

			if ($edit_price)
			{
				$edit_price_sym = substr($edit_price, 0, 1);
				KSMProducts::getCalcPriceAsProperties($edit_price_sym, $edit_price, $price);
			}
		}

		$price     = KSMPrice::getPriceInCurrentCurrency($price, $price_type);
		$val_price = KSMPrice::showPriceWithTransform($price);

		$app->close($val_price . '^^^' . $price);
	}

	function add_favorites()
	{
		$app  = JFactory::getApplication();
		$id   = $app->input->getInt('id', 0);
		$user = KSUsers::getUser();
		if ($user->id != 0 && !in_array($id, $user->favorites))
		{
			$user->favorites[] = $id;
			$db                = JFactory::getDBO();
			echo $query = "update #__ksen_users set favorites='" . json_encode($user->favorites) . "' where id='$user->id'";
			$db->setQuery($query);
			$db->execute();
		}
		$app->close();
	}

	function add_watched()
	{
		$app  = JFactory::getApplication();
		$id   = $app->input->getInt('id', 0);
		$user = KSUsers::getUser();
		if ($user->id != 0 && !in_array($id, $user->watched))
		{
			$user->watched[] = $id;
			$db              = JFactory::getDBO();
			$query           = "update #__ksen_users set watched='" . json_encode($user->watched) . "' where id='$user->id'";
			$db->setQuery($query);
			$db->execute();
		}
		$app->close();
	}

}
