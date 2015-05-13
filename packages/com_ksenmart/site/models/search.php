<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::import('models.modelkslist');
class KsenMartModelSearch extends JModelKSList {
    
    public $_correct_string    = null;
    public $_limit_relevant    = null;
    public $_limit_categories  = null;
    public $_limit_manufacture = null;
    public $_limit_products    = null;
    private $_search_fields    = array(
                                    'p.title',
                                    'p.introcontent', 
                                    'p.tag', 
                                    'c.title',
                                    'p.product_code'
                                );
    
    public function __construct(){
        
        parent::__construct();
        $ajax_search = JRequest::getVar('ajax_search', 0);
        if($ajax_search){
            $this->params    = new JRegistry();
            $module          = JModuleHelper::getModule('km_simple_search');
            if (!empty($module->params))
                $this->params->loadString($module->params);
            
            $this->_limit_relevant      = $this->params->get('count_relevants', 3);
            $this->_limit_categories    = $this->params->get('count_categories', 1);
            $this->_limit_manufacture   = $this->params->get('count_manufactured', 1);
            $this->_limit_products      = $this->params->get('count_result', 5);
        }else{
            $this->_limit_relevant      = $this->params->get('count_relevants', 3);
            $this->_limit_categories    = $this->params->get('count_categories', 1);
            $this->_limit_manufacture   = $this->params->get('count_manufactured', 1);
            $this->_limit_products      = 0;
        }
    }
    
    protected function populateState($ordering = null, $direction = null){
        $this->onExecuteBefore('populateState', array(&$this));

        parent::populateState($ordering, $direction);

        if(empty($this->_params)){
            $this->_params = JComponentHelper::getParams('com_ksenmart');
            $this->setState('params', $this->_params);
        }

        $limit = JRequest::getVar('limit', $this->_params->get('site_product_limit', 20));
        $this->setState('list.limit', $limit);
        $limitstart = JRequest::getVar('limitstart', 0);
        $this->setState('list.start', $limitstart);

        $this->onExecuteAfter('populateState', array(&$this));
    }
    
    private function correctString($string){
        $this->onExecuteBefore('correctString', array(&$string));
        
        $search = array(
            "й","ц","у","к","е","н","г","ш","щ","з","х","ъ",
            "ф","ы","в","а","п","р","о","л","д","ж","э",
            "я","ч","с","м","и","т","ь","б","ю",
            "Й","Ц","У","К","Е","Н","Г","Ш","Щ","З","Х","Ъ",
            "Ф","Ы","В","А","П","Р","О","Л","Д","Ж","Э",
            "Я","Ч","С","М","И","Т","Ь","Б","Ю",
        );
        $replace = array(
            "q","w","e","r","t","y","u","i","o","p","[","]",
            "a","s","d","f","g","h","j","k","l",";","'",
            "z","x","c","v","b","n","m",",",".",
            "Q","W","E","R","T","Y","U","I","O","P","[","]",
            "A","S","D","F","G","H","J","K","L",";","'",
            "Z","X","C","V","B","N","M",",",".",
        );
        
        $this->onExecuteAfter('correctString', array(&$replace, &$search, &$string));
        return str_replace($replace, $search, $string);
    }

    public function getItemsSearch($value, $correct = false){
        $this->onExecuteBefore('getProductSearch', array(&$value, &$correct));

        $this->setState('value', $value);
        $this->setState('correct', $correct);

        if ($correct) {
            
            $store       = $this->getStoreId();
            $this->query = array();

            unset($this->cache[$store]);
        }

        $results = $this->getItems();
        if (!$results) {
            $results = array();
        }

        $this->onExecuteAfter('getProductSearch', array(&$results));
        return $results;
    }

    protected function getListQuery(){

        $value = $this->getState('value', null);
        $correct = $this->getState('correct', $value);

        if(mb_strlen($value, 'utf-8') >= 4){
            if($correct){
                $value = $this->correctString($value);
                $this->_correct_string = $value;
            }
            $morph_search = str_replace(' ', '* ', $value);
            
            $query = "
                SELECT 
                    p.id, p.title, p.alias, p.price, i.filename, i.folder, pc.category_id 
                FROM 
                    #__ksenmart_products AS p 
                LEFT JOIN
                    #__ksenmart_products_categories AS pc ON p.id = pc.product_id
                LEFT JOIN
                    #__ksenmart_files AS i ON p.id=i.owner_id
                LEFT JOIN
                    #__ksenmart_categories AS c ON pc.category_id=c.id
                WHERE ";

            $count_search_fields = count($this->_search_fields) - 1;
            foreach ($this->_search_fields as $key => $value) {
                if($key != 0){
                    $query .= 'OR ';
                }
                $query .= 'MATCH
                    (' . $value . ') 
                AGAINST 
                    (\'*'.$morph_search.'*\' IN BOOLEAN MODE)';
            }
            $query .= ' GROUP BY p.id';
        }else{
            $query = $this->_db->getQuery(true);
            $query
                ->select('p.id, p.title, p.alias, p.price, i.filename, i.folder, pc.category_id')
                ->from('#__ksenmart_products AS p')
                ->leftjoin('#__ksenmart_products_categories AS pc ON p.id = pc.product_id')
                ->leftjoin('#__ksenmart_files AS i ON p.id=i.owner_id')
                ->leftjoin('#__ksenmart_categories AS c ON pc.category_id=c.id')
                ->where('p.title LIKE \'%'.$value.'%\' OR p.content LIKE \'%'.$value.'%\' OR p.introcontent LIKE \'%'.$value.'%\' OR c.title LIKE \'%'.$value.'%\' OR p.tag LIKE \'%'.$value.'%\'')
                ->where('p.published=1')
                ->order('p.title ASC')
                ->group('p.id')
            ;
        }

        return $query;
    }
    
    public function getProductsObject($p_ids){
        $this->onExecuteBefore('getProductsObject', array(&$p_ids));

        foreach ($p_ids as &$item) {
            $item = KSMProducts::getProduct($item->id);
        }
        
        $this->onExecuteAfter('getProductsObject', array(&$p_ids));
        return $p_ids;
    }
    
    public function setRelevants($value){
        $this->onExecuteBefore('setRelevants', array(&$value));

        $word_array = explode(' ', $value);
        $word_last  = $word_array[count($word_array)-1];
        
        if(mb_strlen($word_last) < 4){
            unset($word_array[count($word_array)-1]);
            sort($word_array);
        }else{
            $this->setSearchResult($value);
            $this->removeIrrelevantSearches($value);
        }
        
        $this->onExecuteAfter('setRelevants', array(&$this));
    }
    
    public function getCountRelevantsResult($value){
        $this->onExecuteBefore('getCountRelevantsResult', array(&$value));

        $morph_search   = str_replace(' ', '* ', $value);
        
        $query = "
            SELECT 
                COUNT(p.id) AS count 
            FROM 
                #__ksenmart_products AS p 
            LEFT JOIN
                #__ksenmart_products_categories AS pc ON p.id = pc.product_id
            LEFT JOIN
                #__ksenmart_categories AS c ON pc.category_id=c.id
            WHERE ";

        $count_search_fields = count($this->_search_fields) - 1;
        foreach ($this->_search_fields as $key => $value) {
            if($key != 0){
                $query .= 'OR ';
            }
            $query .= 'MATCH
                (' . $value . ') 
            AGAINST 
                (\''.$morph_search.'*\' IN BOOLEAN MODE)';
        }
        $query .= ' GROUP BY p.id';
        
        $this->_db->setQuery($query);
        $p_ids = $this->_db->loadObjectList();
        
        $this->onExecuteAfter('getCountRelevantsResult', array(&$p_ids));
        return count($p_ids);
    }
    
    public function getProductTotalCategory($id){
        $this->onExecuteBefore('getProductTotalCategory', array(&$id));

        $query  = $this->_db->getQuery(true);
        
        $query
            ->select('COUNT(p.id) AS count')
            ->from('#__ksenmart_products_categories AS pc')
            ->leftjoin('#__ksenmart_products AS p ON pc.product_id=p.id')
            ->where('p.published=1')
            ->where('pc.category_id='.$id)
        ;
        $this->_db->setQuery($query);
        $counts = $this->_db->loadObject();
        
        $this->onExecuteAfter('getProductTotalCategory', array(&$counts));
        return $counts->count;
    }
    
    public function getProductTotalManufacture($id){
        $this->onExecuteBefore('getProductTotalManufacture', array(&$id));

        $query  = $this->_db->getQuery(true);
        
        $query
            ->select('COUNT(p.id) AS count')
            ->from('#__ksenmart_products AS p')
            ->where('p.published=1')
            ->where('p.manufacturer='.$id)
        ;
        $this->_db->setQuery($query);
        $counts = $this->_db->loadObject();
        
        $this->onExecuteAfter('getProductTotalManufacture', array(&$counts));
        return $counts->count;
    }

    public function getProductManufacturs($ids){
        $this->onExecuteBefore('getProductManufacturs', array(&$ids));

        $manufacturers = array();
        if(!empty($ids)){
            $query  = $this->_db->getQuery(true);
            
            $query
                ->select('p.id')
                ->from('#__ksenmart_products AS p')
                ->where('p.published=1')
                ->where('p.manufacturer IN(' . implode(', ', $ids) . ')')
            ;
            $this->_db->setQuery($query, 0, $this->_limit_products);
            $manufacturers = $this->_db->loadObjectList();
        }
        $this->onExecuteAfter('getProductManufacturs', array(&$manufacturers));
        return $manufacturers;
    }
    
    public function getCatSearch($value){
        $this->onExecuteBefore('getCatSearch', array(&$value));
        
        $morph_search = str_replace(' ', '* ', $value);

        $value = $this->_db->q($this->_db->escape($value) . '%');
        $query = $this->_db->getQuery(true);

        $query
            ->select($this->_db->qn(array(
                'c.id',
                'c.title',
            ), array(
                'cat_id',
                'cat_title',
            )))
            ->from($this->_db->qn('#__ksenmart_categories', 'c'))
            ->where($this->_db->qn('c.title') . ' LIKE ' . $this->_db->q($value))
        ;
        
        $this->_db->setQuery($query, 0, $this->_limit_categories);
        $results = $this->_db->loadObjectList();
        
        $this->onExecuteAfter('getCatSearch', array(&$results));
        return $results;
    }
    
    public function getManufactureSearch($value){
        $this->onExecuteBefore('getManufactureSearch', array(&$value));

        $morph_search = str_replace(' ', '* ', $value);

        $value = $this->_db->q($this->_db->escape($value) . '%');
        $query = $this->_db->getQuery(true);
        
        $query
            ->select($this->_db->qn(array(
                'm.id',
                'm.title',
            )))
            ->from($this->_db->qn('#__ksenmart_manufacturers', 'm'))
            ->where($this->_db->qn('m.title') . ' LIKE ' . $this->_db->q($value))
        ;

        $this->_db->setQuery($query, 0, $this->_limit_manufacture);
        $results = $this->_db->loadObjectList();


        $this->onExecuteAfter('getManufactureSearch', array(&$results));
        return $results;
    }
    

    public function getRelevantSearches($value){
        $this->onExecuteBefore('getRelevantSearches', array(&$value));

        $morph_search = str_replace(' ', '* ', $value);

        $value = $this->_db->q($this->_db->escape($value) . '%');
        $query = $this->_db->getQuery(true);

        $query
            ->select($this->_db->qn(array(
                'r.id',
                'r.title',
                'r.hit',
            )))
            ->from($this->_db->qn('#__ksenmart_searches_query', 'r'))
            ->where($this->_db->qn('r.title') . ' LIKE ' . $this->_db->q($value))
            ->group($this->_db->qn('r.hit') . ' DESC')
        ;
        
        $this->_db->setQuery($query, 0, $this->_limit_relevant);
        $results = $this->_db->loadObjectList();
        
        $this->onExecuteAfter('getRelevantSearches', array(&$results));
        return $results;
    }
    
    private function removeIrrelevantSearches($value){
        $this->onExecuteBefore('removeIrrelevantSearches', array(&$value));

        $query = $this->_db->getQuery(true);
        
        $value_length   = mb_strlen($value);
        $conditions     = array();  
              
        for($i=1; $i <= $value_length-1; $i++){
            $title          = mb_substr($value, 0, $i*(-1));
            $conditions[]   = $this->_db->quote($title);
        }
        
        $query->delete(KSDb::quoteName('#__ksenmart_searches_query'));
        $query->where('title IN('.implode(', ', $conditions).')');
        $this->_db->setQuery($query);
        
        $result = $this->_db->query();
        $this->onExecuteAfter('removeIrrelevantSearches', array(&$result));        
    }
    
    private function setSearchResult($value){
        $this->onExecuteBefore('setSearchResult', array(&$value));

        $like_search_id = $this->getLikeSearchId($value);
        
        $query_insert = "
            INSERT INTO 
                #__ksenmart_searches_query 
            SET 
                hit = 1, 
                title='".$value."', 
                id=".$like_search_id." ON DUPLICATE KEY UPDATE hit = hit + 1
        ";

        $this->_db->setQuery($query_insert);
        try{
            $result = $this->_db->query();
            $this->onExecuteAfter('setSearchResult', array(&$result));
        }catch (Exception $e){}
    }
    
    private function getLikeSearchId($value){
        $this->onExecuteBefore('getLikeSearchId', array(&$value));

        $value = $this->_db->q($this->_db->escape($value) . '%');
        $query = $this->_db->getQuery(true);

        $query
            ->select($this->_db->qn(array(
                'r.id',
                'r.title',
                'r.hit',
            )))
            ->from($this->_db->qn('#__ksenmart_searches_query', 'r'))
            ->where($this->_db->qn('r.title') . '=' . $this->_db->q($value))
        ;
        
        $this->_db->setQuery($query, 0, 1);
        $results = $this->_db->loadObject();
        
        if($this->_db->getAffectedRows()){
            $this->onExecuteAfter('getLikeSearchId', array(&$results));
            return $results->id;
        }else{
            return 0;
        }
    }
}