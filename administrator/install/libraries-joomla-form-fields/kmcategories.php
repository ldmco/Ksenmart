<?php
defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldKMCategories extends JFormFieldList
{
	public $type = 'KMCategories';

	protected function getOptions()
	{
		// Initialise variables.
		$options = array();

		$options = JHtml::_('kmcategories.options',0);
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
