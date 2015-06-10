<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class JFormFieldClearImageCache extends JFormField {
	
	protected $type = 'ClearImageCache';
	
	public function getInput() {
		$extension = JRequest::getVar('extension', null);
		$html = '';
		$html.= '<a style="margin-top:3px;" href="' . JRoute::_('index.php?option=com_ksen&task=settings.del_images_cache&extension=' . $extension) . '">' . JText::_('KSM_CLEAR') . '</a>';
		
		return $html;
	}
}