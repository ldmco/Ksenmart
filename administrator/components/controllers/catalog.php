<?php defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class KsenMartControllerCatalog extends KsenMartController {

    function get_childs() {
        $db = JFactory::getDBO();
        $id = JRequest::getInt('id');
        $html = '';

        $model = $this->getModel('catalog');
        $view = $this->getView('catalog', 'html');
        $view->setModel($model, true);
        $query = $db->getQuery(true);
        $query->select('p.*')->from('#__ksenmart_products as p')->where('p.parent_id=' . $id)->order('p.ordering');
        $query = KMMedia::setItemMainImageToQuery($query);
        $db->setQuery($query);
        $items = $db->loadObjectList();
        foreach ($items as $item) {
            $item->small_img = KMMedia::resizeImage($item->filename, $item->folder, $model->params->get('admin_product_thumb_image_width'), $model->params->get('admin_product_thumb_image_heigth'), json_decode($item->params, true));
            $item->medium_img = KMMedia::resizeImage($item->filename, $item->folder, $model->params->get('admin_product_medium_image_width'), $model->params->get('admin_product_medium_image_heigth'), json_decode($item->params, true));
            $view->setLayout('default_item_form');
            $view->item = &$item;
            ob_start();
            $view->display();
            $html .= ob_get_contents();
            ob_end_clean();
        }

        $response = array(
            'html' => $html,
            'message' => array(),
            'errors' => 0);
        $response = json_encode($response);
        JFactory::getDocument()->setMimeEncoding('application/json');
        echo $response;
        JFactory::getApplication()->close();
    }

    function get_set_childs() {
        $db = JFactory::getDBO();
        $id = JRequest::getInt('id');
        $html = '';

        $model = $this->getModel('catalog');
        $view = $this->getView('catalog', 'html');
        $view->setModel($model, true);
        $query = $db->getQuery(true);
        $query->select('p.*,pp.product_id as set_id')->from('#__ksenmart_products as p')->innerjoin('#__ksenmart_products_relations as pp on pp.relative_id=p.id')->where('pp.product_id=' . $id)->where('pp.relation_type=' . $db->quote('set'))->order('p.ordering');
        $query = KMMedia::setItemMainImageToQuery($query);
        $db->setQuery($query);
        $items = $db->loadObjectList();
        foreach ($items as $item) {
            $item->small_img = KMMedia::resizeImage($item->filename, $item->folder, $model->params->get('admin_product_thumb_image_width'), $model->params->get('admin_product_thumb_image_heigth'), json_decode($item->params, true));
            $item->medium_img = KMMedia::resizeImage($item->filename, $item->folder, $model->params->get('admin_product_medium_image_width'), $model->params->get('admin_product_medium_image_heigth'), json_decode($item->params, true));
            $view->setLayout('default_item_form');
            $view->item = &$item;
            ob_start();
            $view->display();
            $html .= ob_get_contents();
            ob_end_clean();
        }

        $response = array(
            'html' => $html,
            'message' => array(),
            'errors' => 0);
        $response = json_encode($response);
        JFactory::getDocument()->setMimeEncoding('application/json');
        echo $response;
        JFactory::getApplication()->close();
    }

    function get_properties() {
        $categories = JRequest::getVar('categories', array());
        JArrayHelper::toInteger($categories);
        $model = $this->getModel('catalog');
        $product = $model->getProduct($categories);
        $model->form = 'product';
        $form = $model->getForm();
        if ($form) $form->bind($product);
        $response = array('html' => $form->getInput('properties'));
        $response = json_encode($response);
        JFactory::getDocument()->setMimeEncoding('application/json');
        echo $response;
        JFactory::getApplication()->close();
    }

    function delete_child_group() {
        $group_id = JRequest::getInt('group_id');
        $model = $this->getModel('catalog');
        $model->deleteChildGroup($group_id);
        JFactory::getApplication()->close();
    }
	
	public function get_search_items_html(){
		$ids = JRequest::getVar('ids');
		$items_tpl = JRequest::getVar('items_tpl');
		$html='';
		
		$model=$this->getModel('catalog');
		$view=$this->getView('catalog','html');
		$view->setModel($model,true);
		$items=$model->getProducts($ids);
		$total=count($items);
		if ($total>0)
		{
			$view->setLayout($items_tpl);
			foreach($items as $item)
			{
				$view->item=&$item;
				ob_start();
				$view->display();
				$html.=ob_get_contents();
				ob_end_clean();
			}	
		}

		$response=array(
			'html'=>$html,
			'total'=>$total
		);
		$response=json_encode($response);
		JFactory::getDocument()->setMimeEncoding('application/json');
		echo $response;
        JFactory::getApplication()->close();	
	}	

}
