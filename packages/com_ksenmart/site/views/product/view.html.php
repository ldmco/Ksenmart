<?php defined('_JEXEC') or die;

KSSystem::import('views.viewks');
class KsenMartViewProduct extends JViewKS {
    function display($tpl = null) {
        $app = JFactory::getApplication();
        $document = JFactory::getDocument();
        $this->params = JComponentHelper::getParams('com_ksenmart');
        $path = $app->getPathway();
        $model = $this->getModel();
        $this->state = $this->get('State');
        
        $document->addScript(JURI::base() . 'components/com_ksenmart/js/highslide/highslide-with-gallery.js', 'text/javascript', true);
        $document->addScript(JURI::base() . 'components/com_ksenmart/js/highslide.js', 'text/javascript', true);
        $document->addScript(JURI::base() . 'components/com_ksenmart/js/slides.min.jquery.js', 'text/javascript', true);
        
        $document->addStyleSheet(JURI::base() . 'components/com_ksenmart/js/highslide/highslide.css');
        $document->addStyleSheet(JURI::base() . 'components/com_ksenmart/css/slides.css');
		
        if ($model->_id && $this->getLayout() != 'comment_congratulation') {
            $this->product = $model->getProduct();
            if ($this->product) {
                if (!JFactory::getConfig()->get('config.caching', 0)) {
                    $cat_path = $this->get('CategoriesPath');
                    
                    foreach ($cat_path as $cat) {
                        $path->addItem($cat->title, $cat->link);
                    }
                }
                
                $this->images = $model->getImages();
                $this->related = KSMProducts::getRelated($this->product->id);
                $this->links = KSMProducts::getLinks($this->product->id);
                $title = $model->getProductTitle();
                
                $document->setTitle($title);
                $model->setProductMetaData();
                if ($this->product->type == 'set') {
                    $document->addScript(JURI::base() . 'components/com_ksenmart/js/set.js', 'text/javascript', true);
                    $this->set_related = KSMProducts::getSetRelated($this->product->id, true);
                } else $document->addScript(JURI::base() . 'components/com_ksenmart/js/product.js', 'text/javascript', true);
                
                if ($this->product->is_parent) {
                    $template = $this->params->get('parent_products_template', 'list');
                    if ($template == 'list') {
                        $this->childs_groups = $model->getChildsGroups();
                    } elseif ($template == 'select') {
                        $this->childs_titles = $model->getChildsTitles();
                        $this->childs_title = $model->getChildsTitle();
						if (!count($this->images))
						{
							$model->_id=$this->product->parent_id;
							$this->images   = $model->getImages();
							$model->_id=$this->product->id;
						}						
                    }
                    $this->setLayout('parent_product_' . $template);
                } elseif ($this->product->parent_id != 0) {
                    $parent = KSMProducts::getProduct($this->product->parent_id);
                    if ($this->params->get('parent_products_template', 'list') != 'list') {
                        $template = $this->params->get('parent_products_template', 'list');
                        $this->product->title = $parent->title;
                        $this->childs_titles = $model->getChildsTitles($this->product->parent_id);
                        $this->childs_title = $model->getChildsTitle($this->product->parent_id);
                        
                        $this->setLayout('parent_product_' . $template);
                    } else {
                        $this->setLayout($this->product->type);
                    }
                    if (!JFactory::getConfig()->get('config.caching', 0)) {
                        $path->addItem($parent->title, $parent->link);
                    }
                } else {
                    $this->setLayout($this->product->type);
                }
                if (!JFactory::getConfig()->get('config.caching', 0)) {
                    $path->addItem($this->product->title);
                }
            } else {
                $this->setLayout('no_product');
            }
        } else {
			if($this->getLayout() == 'product_comment_form'){
				$document->addScript(JURI::base() . 'components/com_ksenmart/js/product.js', 'text/javascript', true);
				$this->product->id = JRequest::getVar('id',0);
				$this->setLayout('product_comment_form');
			} elseif ($this->getLayout() == 'comment_congratulation'){
				echo '<script> window.parent.location.reload();</script>';
				$this->setLayout('product_comment_form');
			} else
				$this->setLayout('no_product');
        }
        
        parent::display($tpl);
    }
}