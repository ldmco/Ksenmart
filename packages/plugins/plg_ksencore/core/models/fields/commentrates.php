<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldCommentRates extends JFormField {
	
	protected $type = 'CommentRates';
	
	public function getInput() {
		$db = JFactory::getDBO();
		$html = '';
		
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_comment_rates')->order('ordering');
		$db->setQuery($query);
		$rates = $db->loadObjectList();
		
		foreach ($rates as $rate) {
			$value = isset($this->value[$rate->id]) ? $this->value[$rate->id]->value : 0;
			$html.= '<div class="row">';
			$html.= '	<label class="inputname">' . $rate->title . '</label>';
			$html.= '	<div class="rate">';
			
			for ($k = 1;$k < 6;$k++) {
				$html.= '	<label>';
				if (floor($value) >= $k) $html.= '	<img rel="' . $k . '" src="' . JURI::base() . 'components/com_ksenmart/assets/images/c-star.png" alt="" />';
				else $html.= '	<img rel="' . $k . '" src="' . JURI::base() . 'components/com_ksenmart/assets/images/c-star2.png" alt="" />';
				$html.= '		<input type="radio" name="' . $this->name . '[' . $rate->id . ']" onclick="setAddRate(this);" value="' . $k . '" ' . (floor($value) == $k ? 'checked' : '') . ' />';
				$html.= '	</label>';
			}
			$html.= '	</div>';
			$html.= '</div>';
		}
		
		$script = '
		function setAddRate(obj)
		{
			var rate=jQuery(obj).val();
			var rate_block=jQuery(obj).parents(".rate");
			rate_block.find("img").attr("src","components/com_ksenmart/assets/images/c-star2.png");
			for(var k=1;k<=rate;k++)
				rate_block.find("img[rel=\'"+k+"\']").attr("src","components/com_ksenmart/assets/images/c-star.png");
		}
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);
		
		return $html;
	}
}