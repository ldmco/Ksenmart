<?php defined('_JEXEC') or die;

class JFormFieldClearImageCache extends JFormField {
	
	protected $type = 'ClearImageCache';
	
	public function getInput() {
		$html = '';
		$html.= '<a style="margin-top:3px;" href="' . JRoute::_('index.php?option=com_ksenmart&task=settings.del_images_cache') . '">' . JText::_('ksm_clear') . '</a>';
		
		return $html;
	}
}