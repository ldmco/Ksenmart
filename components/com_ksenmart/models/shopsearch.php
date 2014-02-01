<?php defined('_JEXEC') or die;

jimport('joomla.application.component.modelkmadmin');

class KsenMartModelShopSearch extends JModelKMAdmin {
    
    public $_correct_string    = null;
    public $_limit_relevant    = null;
    public $_limit_categories  = null;
    public $_limit_manufacture = null;
    public $_limit_products    = null;
    
    public function __construct(){
        
        parent::__construct();
        $ajax_search = JRequest::getVar('ajax_search', 0);
        
        if($ajax_search){
            $this->params    = new JRegistry();
            $module          = JModuleHelper::getModule('km_simple_search');
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
    
    public function populateState() {
        $this->onExecuteBefore('populateState', array(&$this));
        $this->onExecuteAfter('populateState', array(&$this));
    }
    
    private function correctString($string){
        $this->onExecuteBefore('correctString', array(&$string));
        
        $search = array(
            "й","ц","у","к","е","н","г","ш","щ","з","х","ъ",
            "ф","ы","в","а","п","р","о","л","д","ж","э",
            "я","ч","с","м","и","т","ь","б","ю"
        );
        $replace = array(
            "q","w","e","r","t","y","u","i","o","p","[","]",
            "a","s","d","f","g","h","j","k","l",";","'",
            "z","x","c","v","b","n","m",",","."
        );
        
        $this->onExecuteAfter('correctString', array(&$replace, &$search, &$string));
        return str_replace($replace, $search, $string);
    }
    
    public function getProductSearch($value, $correct = false){
        $this->onExecuteBefore('getProductSearch', array(&$value, &$correct));
        
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
                WHERE MATCH
                    (p.title, p.content, p.introcontent, p.tag, c.title, p.product_code) 
                AGAINST 
                    ('".$morph_search."*' IN BOOLEAN MODE)
                GROUP BY
                    p.id
            ";
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
        
        $this->_db->setQuery($query, 0, $this->_limit_products);
        $results = $this->_db->loadObjectList();
        
        $this->onExecuteAfter('getProductSearch', array(&$results));
        return $results;
    }
    
    public function getProductsObject($p_ids){
        $this->onExecuteBefore('getProductsObject', array(&$p_ids));

        foreach ($p_ids as &$item) {
            $item = KMProducts::getProduct($item->id);
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
            WHERE MATCH
                (p.title, p.content, p.introcontent, p.tag, c.title, p.product_code) 
            AGAINST 
                ('".$morph_search."*' IN BOOLEAN MODE)
            GROUP BY
                p.id
        ";
        
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
    
    public function getCatSearch($value){
        $this->onExecuteBefore('getCatSearch', array(&$value));
        
        $morph_search = str_replace(' ', '* ', $value);
        
        $query = "
            SELECT 
                c.id AS cat_id, 
                c.title 
            FROM 
                #__ksenmart_categories AS c 
            WHERE 
                c.title 
            LIKE
                '".$value."%'
        ";
        
        $this->_db->setQuery($query, 0, $this->_limit_categories);
        $results = $this->_db->loadObjectList();
        
        $this->onExecuteAfter('getCatSearch', array(&$results));
        return $results;
    }
    
    public function getManufactureSearch($value){
        $this->onExecuteBefore('getManufactureSearch', array(&$value));

        $morph_search = str_replace(' ', '* ', $value);
        
        $query = "
            SELECT 
                m.id, 
                m.title 
            FROM 
                #__ksenmart_manufacturers AS m 
            WHERE 
                m.title 
            LIKE
                '".$value."%'
        ";
        
        $this->_db->setQuery($query, 0, $this->_limit_manufacture);
        $results = $this->_db->loadObjectList();
        
        $this->onExecuteAfter('getManufactureSearch', array(&$results));
        return $results;
    }
    

    public function getRelevantSearches($value){
        $this->onExecuteBefore('getRelevantSearches', array(&$value));

        $morph_search = str_replace(' ', '* ', $value);
        
        $query = "
            SELECT 
                id, title, hit  
            FROM 
                #__ksenmart_searches_query 
            WHERE 
                title 
            LIKE
                '".$value."%'
            GROUP BY 
                hit
            DESC
        ";
        
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
        
        $query->delete($this->_db->quoteName('#__ksenmart_searches_query'));
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

        $query = $this->_db->getQuery(true);
        $query
            ->select('id')
            ->from('#__ksenmart_searches_query')
            ->where("title ='".$value."'");
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