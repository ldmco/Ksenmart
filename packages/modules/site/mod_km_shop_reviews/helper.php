<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class modKMShopreviewsHelper 
{

    public function getReviews($params) 
	{
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query
			->select('
				c.id,
				c.user_id as user, 
				c.comment, 
				c.date_add,
				c.rate,  
				u.name
			')
			->from('#__ksenmart_comments as c')
			->leftjoin('#__users as u on u.id = c.user_id')
			->where('type = '.$db->quote('shop_review'))
			->where('published = 1')
			->order('date_add desc')
		;
        $db->setQuery($query, 0, $params->get('count_review', 5));
        $reviews = $db->loadObjectList();
		
		$Itemid = self::getReviewsItemid();
		
		foreach($reviews as &$review)
		{
			$review->comment = mb_substr($review->comment, 0, $params->get('count_symbol', 200));
			$review->link = JRoute::_('index.php?option=com_ksenmart&view=comments&layout=review&id='.$review->id.'&Itemid='.$Itemid);
		}

        return $reviews;
    }
	
	public function getReviewsItemid()
	{
		$db = JFactory::getDBO();
		
		$query = $db->getQuery(true);
		$query
			->select('id')
			->from('#__menu')
			->where('link like ' . $db->quote('index.php?option=com_ksenmart&view=comments&layout=reviews'))
			->where('published=1')
		;
		$db->setQuery($query);
		$menuitemId = $db->loadResult();
		
		return $menuitemId;
	}
	
}