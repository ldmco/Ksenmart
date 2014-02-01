<?php defined('_JEXEC') or die;

jimport('joomla.application.component.viewkm');

class KsenMartViewShopSearch extends JViewKM {
    
    public $value       = null;
    public $model       = null;
    public $ajax_search = null;
        
    public function display($tpl = null){
        
        $app         = JFactory::getApplication();
        $jinput      = $app->input;
        $this->value = $jinput->get('value', null, 'string');
        
        if(!empty($this->value)){
            $doc          = JFactory::getDocument();
            $params       = JComponentHelper::getParams('com_ksenmart');
            
            $path         = $app->getPathway();
            $this->state  = $this->get('State');
            $this->params = $params;
            
            $this->ajax_search  = $jinput->get('ajax_search', 0, 'int');
            
            $path->addItem(JText::_('KSM_SEARCH_PATHWAY_ITEM'), '');
            $doc->setTitle(JText::_('KSM_SEARCH_PATHWAY_ITEM').' - "'.$this->value.'"');
            $doc->addScript(JURI::base() . 'components/com_ksenmart/js/catalog.js', 'text/javascript', true);
            
            $this->getResult($this->value);
            if($this->ajax_search){
                $this->setLayout('module');
            }else{
                $this->setLayout('dafault');
            }
            
            $session     = JFactory::getSession();
            $layout_view = $session->get('layout', 'list_ext');
            $this->assignRef('layout_view', $layout_view);
            
            parent::display($tpl);
        }
    }
    
    private function getResult(){
        
        $Itemid = KMSystem::getShopItemid();

        $this->value  = trim(htmlspecialchars($this->value));
        
        if(!empty($this->value)){
            if(empty($this->model)){
                $this->model = $this->getModel('shopsearch');
            }
            $affected_rows = false;

            $p_ids = $this->model->getProductSearch($this->value);
            
            if($p_ids){                
                $affected_rows = true;
                $products = $this->model->getProductsObject($p_ids);
            }

            $cat_search         = $this->model->getCatSearch($this->value);
            $manufacture_search = $this->model->getManufactureSearch($this->value);
            if($this->ajax_search){
                $relevant_search    = $this->model->getRelevantSearches($this->value);
            }

            if($affected_rows){
                if(!$cat_search){
                    if(!is_numeric($this->value)){
                        $this->model->setRelevants($this->value);
                    }
                }
            }else{
                $p_ids = $this->model->getProductSearch($this->value, true);
                if($p_ids){
                    $affected_rows = true;
                    $products = $this->model->getProductsObject($p_ids);
                }
                
                $cat_search         = $this->model->getCatSearch($this->model->_correct_string);
                $manufacture_search = $this->model->getManufactureSearch($this->model->_correct_string);
                if($this->ajax_search){
                    $relevant_search    = $this->model->getRelevantSearches($this->model->_correct_string);
                }
                
                if($affected_rows){
                    if(!$cat_search){
                        if(!is_numeric($this->model->_correct_string)){
                            $this->model->setRelevants($this->model->_correct_string);
                        }
                    }
                }
            }
            
            if(!empty($this->model->_correct_string)){
                echo '<div class="correct">';
                echo 'Возможно вы ищите "<span>'.$this->model->_correct_string."</span>\"";
                echo '</div>';
            }
            
            if(!empty($results)){
                //$results = $this->model->setImages($results);
                //echo $this->model->generateSearchResult($results);
            }
            
            if(!empty($cat_search)){
                foreach($cat_search as $key => $item){
                    $item->product_total = $this->model->getProductTotalCategory($item->cat_id);
                    if(!$item->product_total){
                        unset($cat_search[$key]);
                    }
                }
                sort($cat_search);
            }
            
            if(!empty($manufacture_search)){
                foreach($manufacture_search as $key => $item){
                    $item->product_total = $this->model->getProductTotalManufacture($item->id);
                    if(!$item->product_total){
                        unset($manufacture_search[$key]);
                    }
                }
                sort($manufacture_search);
            }
            
            if($this->ajax_search){
                if(!empty($relevant_search)){
                    foreach($relevant_search as $key => $item){
                        $item->product_total = $this->model->getCountRelevantsResult($item->title);
                        if(!$item->product_total){
                            unset($relevant_search[$key]);
                        }
                    }
                    sort($relevant_search);
                }
                $this->assign('relevant_search', $relevant_search);
            }
            
            $this->assignRef('cat_search', $cat_search);
            $this->assignRef('manufacture_search', $manufacture_search);
            $this->assignRef('products', $products);
            
            $this->assignRef('shop_id', $Itemid);
        }
        
        return false;
    }
}