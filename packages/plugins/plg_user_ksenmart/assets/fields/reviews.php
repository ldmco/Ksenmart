<?php 
defined('_JEXEC') or die;

class JFormFieldReviews extends JFormField 
{

	protected $type = 'Reviews';
	
	public function getInput()
	{
		$view = new stdClass();
		$view->name = $this->name;		
		$view->shop_review = null;
		$view->comments = array();
		
		foreach($this->value as $comment)
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
		$html = KSSystem::loadPluginTemplate('ksenmart', 'user', $view, 'reviews_edit');
		
		return $html;
	}

}