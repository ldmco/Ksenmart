<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('views.viewks');
class KsenMartViewSearch extends JViewKS {
    
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
                $pagination = $this->get('Pagination');
                $this->assign('pagination', $pagination);
            }
            
            $session     = JFactory::getSession();
            $layout_view = $session->get('layout', 'list_ext');
            $this->assignRef('layout_view', $layout_view);
            
            parent::display($tpl);
        }
    }
    
    private function getResult(){
        
        $Itemid = KSSystem::getShopItemid();

        $this->value  = trim(htmlspecialchars($this->value));
        
        if(!empty($this->value)){
            if(empty($this->model)){
                $this->model = $this->getModel('search');
            }
            $affected_rows = false;

            $p_ids = $this->model->getItemsSearch($this->value);
            
            if($p_ids){                
                $affected_rows = true;
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
                $p_ids = $this->model->getItemsSearch($this->value, true);
                if($p_ids){
                    $affected_rows = true;
                }
                
                if(!$cat_search){
                    $cat_search         = $this->model->getCatSearch($this->model->_correct_string);
                }
                if(!$manufacture_search){
                    $manufacture_search = $this->model->getManufactureSearch($this->model->_correct_string);
                }
                
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
            
            $mids = array();
            if(!empty($manufacture_search)){
                foreach($manufacture_search as $key => $item){
                    $mids[$item->id] = $item->id;
                    $item->product_total = $this->model->getProductTotalManufacture($item->id);
                    if(!$item->product_total){
                        unset($manufacture_search[$key]);
                    }
                }
                sort($manufacture_search);
            }
            
            $manufacture_products = $this->model->getProductManufacturs($mids);
            $p_ids = array_merge($p_ids, $manufacture_products);

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
            

            $products = $this->model->getProductsObject($p_ids);
            $this->assignRef('cat_search', $cat_search);
            $this->assignRef('manufacture_search', $manufacture_search);
            $this->assignRef('products', $products);
            
            $this->assignRef('shop_id', $Itemid);
        }
        
        return false;
    }
}